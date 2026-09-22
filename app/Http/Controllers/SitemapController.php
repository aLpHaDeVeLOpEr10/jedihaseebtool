<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Page;
use App\Models\Tool;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    /**
     * XML sitemap of every indexable URL.
     *
     * Excluded on purpose: /search (noindex), filtered and sorted list URLs,
     * /login, /admin, /up and any category that currently has no active tools.
     */
    public function index()
    {
        $xml = Cache::remember('sitemap.xml', 3600, function () {
            $urls = [];

            $urls[] = ['loc' => url('/'), 'lastmod' => $this->latestChange(), 'changefreq' => 'daily'];
            $urls[] = ['loc' => route('tools.index'), 'lastmod' => $this->latestChange(), 'changefreq' => 'daily'];
            $urls[] = ['loc' => route('categories.index'), 'lastmod' => $this->latestChange(), 'changefreq' => 'weekly'];

            Category::active()
                ->whereHas('activeTools')
                ->select('slug', 'updated_at')
                ->get()
                ->each(function ($category) use (&$urls) {
                    $urls[] = [
                        'loc'        => route('categories.show', $category->slug),
                        'lastmod'    => $category->updated_at,
                        'changefreq' => 'weekly',
                    ];
                });

            Tool::active()
                ->select('slug', 'updated_at')
                ->ordered()
                ->get()
                ->each(function ($tool) use (&$urls) {
                    $urls[] = [
                        'loc'        => route('tools.show', $tool->slug),
                        'lastmod'    => $tool->updated_at,
                        'changefreq' => 'monthly',
                    ];
                });

            Page::published()
                ->select('slug', 'updated_at')
                ->get()
                ->each(function ($page) use (&$urls) {
                    $urls[] = [
                        'loc'        => route('pages.show', $page->slug),
                        'lastmod'    => $page->updated_at,
                        'changefreq' => 'yearly',
                    ];
                });

            foreach (['about', 'contact', 'privacy', 'terms'] as $name) {
                $urls[] = ['loc' => route($name), 'lastmod' => null, 'changefreq' => 'yearly'];
            }

            return view('sitemap', compact('urls'))->render();
        });

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=utf-8']);
    }

    /**
     * robots.txt — points at the sitemap and closes the crawl traps.
     */
    public function robots()
    {
        $lines = [
            'User-agent: *',
            'Allow: /',
            '',
            // The admin path is deliberately not listed here: robots.txt is
            // public, so naming it would advertise it. Those pages carry a
            // noindex meta tag instead.
            '# Internal search results and faceted list URLs',
            'Disallow: /search',
            'Disallow: /*?q=',
            'Disallow: /*?sort=',
            'Disallow: /*?type=',
            'Disallow: /*?category=',
            '',
            '# Infrastructure',
            'Disallow: /up',
            'Disallow: /*.sql$',
            '',
            'Sitemap: ' . url('/sitemap.xml'),
            '',
        ];

        return response(implode("\n", $lines), 200, ['Content-Type' => 'text/plain; charset=utf-8']);
    }

    /**
     * Most recent tool change — used as lastmod for the listing pages.
     */
    protected function latestChange(): ?string
    {
        $latest = Tool::active()->max('updated_at');

        return $latest ? \Illuminate\Support\Carbon::parse($latest)->toAtomString() : null;
    }
}
