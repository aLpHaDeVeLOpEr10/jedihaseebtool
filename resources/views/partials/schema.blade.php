@php
    /**
     * Site-wide structured data.
     *
     * Emits one @graph per page so every node can cross-reference the others
     * by @id: the Organization is the publisher of the WebSite, and every tool
     * page's SoftwareApplication points back at the same Organization.
     *
     * Nodes:
     *   Organization + WebSite   — always
     *   SoftwareApplication      — tool pages
     *   BreadcrumbList           — tool and category pages
     *   FAQPage                  — tool pages that have visible FAQs
     *   CollectionPage/ItemList  — category pages and the tools index
     */
    $siteName = \App\Models\Setting::get('site_name', config('app.name', 'Toolsearch'));
    $orgId    = url('/') . '#organization';
    $siteId   = url('/') . '#website';

    $graph = [
        [
            '@type'       => 'Organization',
            '@id'         => $orgId,
            'name'        => $siteName,
            'url'         => url('/'),
            'logo'        => [
                '@type'  => 'ImageObject',
                'url'    => url('/icon-512.png'),
                'width'  => 512,
                'height' => 512,
            ],
            'image'       => url('/og-default.png'),
            'description' => \App\Models\Setting::get('site_description',
                'Free browser-based calculators, converters, generators and file tools.'),
        ],
        [
            '@type'     => 'WebSite',
            '@id'       => $siteId,
            'url'       => url('/'),
            'name'      => $siteName,
            'publisher' => ['@id' => $orgId],
            'inLanguage' => 'en',
            'potentialAction' => [
                '@type'  => 'SearchAction',
                'target' => [
                    '@type'       => 'EntryPoint',
                    'urlTemplate' => url('/search') . '?q={search_term_string}',
                ],
                'query-input' => 'required name=search_term_string',
            ],
        ],
    ];

    // ── Tool pages ────────────────────────────────────────────────
    // Route-guarded: Blade renders the child view before the layout, so a
    // $tool left over from a listing page's foreach would otherwise leak in
    // and describe the wrong entity.
    if (request()->routeIs('tools.show') && isset($tool) && $tool instanceof \App\Models\Tool) {
        $toolUrl = route('tools.show', $tool->slug);

        $graph[] = [
            '@type'       => 'SoftwareApplication',
            '@id'         => $toolUrl . '#app',
            'name'        => $tool->name,
            'url'         => $toolUrl,
            'description' => $tool->seo_description,
            'applicationCategory'    => 'UtilityApplication',
            'applicationSubCategory' => $tool->category->name ?? null,
            'operatingSystem'        => 'Any (web browser)',
            'browserRequirements'    => 'Requires JavaScript',
            'isAccessibleForFree'    => true,
            'inLanguage'             => 'en',
            'dateModified'           => optional($tool->updated_at)->toAtomString(),
            'offers'                 => [
                '@type'         => 'Offer',
                'price'         => '0',
                'priceCurrency' => 'USD',
            ],
            'publisher' => ['@id' => $orgId],
            'isPartOf'  => ['@id' => $siteId],
        ];

        $crumbs = [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Tools', 'item' => route('tools.index')],
        ];
        if ($tool->category) {
            $crumbs[] = [
                '@type'    => 'ListItem',
                'position' => 3,
                'name'     => $tool->category->name,
                'item'     => route('categories.show', $tool->category->slug),
            ];
        }
        $crumbs[] = [
            '@type'    => 'ListItem',
            'position' => count($crumbs) + 1,
            'name'     => $tool->name,
            'item'     => $toolUrl,
        ];
        $graph[] = ['@type' => 'BreadcrumbList', '@id' => $toolUrl . '#breadcrumb', 'itemListElement' => $crumbs];

        $visibleFaqs = $tool->relationLoaded('faqs')
            ? $tool->faqs->where('is_visible', true)
            : $tool->faqs()->where('is_visible', true)->get();

        if ($visibleFaqs->count() > 0) {
            $graph[] = [
                '@type'      => 'FAQPage',
                '@id'        => $toolUrl . '#faq',
                'mainEntity' => $visibleFaqs->map(fn ($faq) => [
                    '@type'          => 'Question',
                    'name'           => $faq->question,
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq->answer],
                ])->values()->all(),
            ];
        }
    }

    // ── Category pages ────────────────────────────────────────────
    // Guarded on the model type: the search view also exposes a $category
    // variable, but there it is the raw filter string.
    if (request()->routeIs('categories.show') && isset($category) && $category instanceof \App\Models\Category && isset($tools)) {
        $catUrl = route('categories.show', $category->slug);

        $graph[] = [
            '@type'       => 'CollectionPage',
            '@id'         => $catUrl . '#collection',
            'name'        => $category->seo_title,
            'url'         => $catUrl,
            'description' => $category->seo_description,
            'isPartOf'    => ['@id' => $siteId],
            'mainEntity'  => [
                '@type'           => 'ItemList',
                'numberOfItems'   => method_exists($tools, 'total') ? $tools->total() : $tools->count(),
                'itemListElement' => collect($tools->items() ?? $tools)->values()
                    ->map(fn ($item, $i) => [
                        '@type'    => 'ListItem',
                        'position' => $i + 1,
                        'name'     => $item->name,
                        'url'      => route('tools.show', $item->slug),
                    ])->all(),
            ],
        ];

        $graph[] = [
            '@type' => 'BreadcrumbList',
            '@id'   => $catUrl . '#breadcrumb',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Categories', 'item' => route('categories.index')],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $category->name, 'item' => $catUrl],
            ],
        ];
    }

    $payload = ['@context' => 'https://schema.org', '@graph' => array_map(
        fn ($node) => array_filter($node, fn ($v) => $v !== null && $v !== ''),
        $graph
    )];
@endphp
<script type="application/ld+json">{!! json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
