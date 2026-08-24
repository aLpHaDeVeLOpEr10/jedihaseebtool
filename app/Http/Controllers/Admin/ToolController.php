<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Tool;
use App\Models\ToolContent;
use App\Models\ToolFaq;
use App\Services\BladeGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ToolController extends Controller
{
    public function __construct(private BladeGeneratorService $bladeGen) {}

    public function index(Request $request)
    {
        $query = Tool::with(['category'])->withTrashed();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('tool_type', $request->type);
        }

        $tools      = $query->latest()->paginate(20)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $toolTypes  = Tool::withTrashed()->distinct()->pluck('tool_type')->sort()->values();

        return view('admin.tools.index', compact('tools', 'categories', 'toolTypes'));
    }

    public function create()
    {
        $categories = Category::active()->orderBy('name')->get();
        $toolTypes  = ['calculator', 'converter', 'generator', 'text', 'file', 'productivity', 'game', 'generic'];

        return view('admin.tools.create', compact('categories', 'toolTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                => 'required|string|max:200',
            'slug'                => 'nullable|string|max:200|unique:tools,slug',
            'category_id'         => 'required|exists:categories,id',
            'short_description'   => 'nullable|string|max:500',
            'long_description'    => 'nullable|string',
            'icon'                => 'nullable|string|max:10',
            'color'               => 'nullable|string|max:20',
            'status'              => 'required|in:active,inactive,draft',
            'is_featured'         => 'boolean',
            'tool_type'           => 'required|string|max:50',
            'input_schema'        => 'nullable|json',
            'output_schema'       => 'nullable|json',
            'engine_class'        => 'nullable|string|max:200',
            'engine_method'       => 'nullable|string|max:200',
            // SEO fields
            'seo_title'           => 'nullable|string|max:200',
            'seo_description'     => 'nullable|string|max:500',
            'seo_keywords'        => 'nullable|string|max:500',
            'canonical_url'       => 'nullable|url|max:500',
            'og_title'            => 'nullable|string|max:200',
            'og_description'      => 'nullable|string|max:500',
            'og_image'            => 'nullable|url|max:500',
            'twitter_title'       => 'nullable|string|max:200',
            'twitter_description' => 'nullable|string|max:500',
            'robots'              => 'nullable|string|max:100',
            'schema_markup'       => 'nullable|string',
            'sort_order'          => 'nullable|integer',
        ]);

        // Normalise empty strings → null so DB stays clean and accessors fall back correctly
        foreach (['seo_title','seo_description','seo_keywords','canonical_url','og_title','og_description','og_image','twitter_title','twitter_description','robots','schema_markup'] as $f) {
            if (isset($validated[$f]) && $validated[$f] === '') {
                $validated[$f] = null;
            }
        }

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Ensure slug uniqueness
        $baseSlug = $validated['slug'];
        $i = 1;
        while (Tool::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $baseSlug . '-' . $i++;
        }

        $validated['is_featured'] = $request->boolean('is_featured');
        $tool = Tool::create($validated);

        // Save input fields
        if ($request->filled('inputs')) {
            $this->saveInputs($tool, json_decode($request->inputs, true) ?? []);
        }

        // Save FAQs
        if ($request->filled('faqs')) {
            $this->saveFaqs($tool, json_decode($request->faqs, true) ?? []);
        }

        // Save content sections
        if ($request->filled('contents')) {
            $this->saveContents($tool, json_decode($request->contents, true) ?? []);
        }

        // Generate Blade file
        if ($request->boolean('generate_blade')) {
            try {
                $this->bladeGen->generate($tool);
            } catch (\Exception $e) {
                // Non-fatal
            }
        }

        return redirect()->route('admin.tools.edit', $tool)
            ->with('success', 'Tool "' . $tool->name . '" created successfully!');
    }

    public function edit(Tool $tool)
    {
        $tool->load(['category', 'contents', 'faqs', 'inputs']);
        $categories = Category::orderBy('name')->get();
        $toolTypes  = ['calculator', 'converter', 'generator', 'text', 'file', 'productivity', 'game', 'generic'];
        $bladeExists = $this->bladeGen->exists($tool);

        $bladeContent = '';
        if ($bladeExists) {
            $path = resource_path('views/tools/generated/' . $tool->slug . '.blade.php');
            $bladeContent = file_get_contents($path);
        }

        return view('admin.tools.edit', compact('tool', 'categories', 'toolTypes', 'bladeExists', 'bladeContent'));
    }

    public function update(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'name'                => 'required|string|max:200',
            'slug'                => 'required|string|max:200|unique:tools,slug,' . $tool->id,
            'category_id'         => 'required|exists:categories,id',
            'short_description'   => 'nullable|string|max:500',
            'long_description'    => 'nullable|string',
            'icon'                => 'nullable|string|max:10',
            'color'               => 'nullable|string|max:20',
            'status'              => 'required|in:active,inactive,draft',
            'is_featured'         => 'boolean',
            'tool_type'           => 'required|string|max:50',
            'engine_class'        => 'nullable|string|max:200',
            'engine_method'       => 'nullable|string|max:200',
            // SEO fields
            'seo_title'           => 'nullable|string|max:200',
            'seo_description'     => 'nullable|string|max:500',
            'seo_keywords'        => 'nullable|string|max:500',
            'canonical_url'       => 'nullable|url|max:500',
            'og_title'            => 'nullable|string|max:200',
            'og_description'      => 'nullable|string|max:500',
            'og_image'            => 'nullable|url|max:500',
            'twitter_title'       => 'nullable|string|max:200',
            'twitter_description' => 'nullable|string|max:500',
            'robots'              => 'nullable|string|max:100',
            'schema_markup'       => 'nullable|string',
            'sort_order'          => 'nullable|integer',
        ]);

        // Normalise empty strings → null so DB stays clean and accessors fall back correctly
        foreach (['seo_title','seo_description','seo_keywords','canonical_url','og_title','og_description','og_image','twitter_title','twitter_description','robots','schema_markup'] as $f) {
            if (isset($validated[$f]) && $validated[$f] === '') {
                $validated[$f] = null;
            }
        }

        $validated['is_featured'] = $request->boolean('is_featured');
        $tool->update($validated);

        // Update inputs
        if ($request->filled('inputs')) {
            $tool->inputs()->delete();
            $this->saveInputs($tool, json_decode($request->inputs, true) ?? []);
        }

        // Update FAQs
        if ($request->filled('faqs')) {
            $tool->faqs()->delete();
            $this->saveFaqs($tool, json_decode($request->faqs, true) ?? []);
        }

        // Update content sections
        if ($request->filled('contents')) {
            $tool->contents()->delete();
            $this->saveContents($tool, json_decode($request->contents, true) ?? []);
        }

        // Update blade content
        if ($request->filled('blade_content')) {
            $path = resource_path('views/tools/generated/' . $tool->slug . '.blade.php');
            if (!is_dir(dirname($path))) {
                mkdir(dirname($path), 0755, true);
            }
            file_put_contents($path, $request->blade_content);
            $tool->update(['has_custom_blade' => true]);
        }

        return back()->with('success', 'Tool updated successfully!');
    }

    public function destroy(Tool $tool)
    {
        $tool->delete();
        return redirect()->route('admin.tools.index')->with('success', 'Tool deleted.');
    }

    public function restore(int $id)
    {
        Tool::withTrashed()->findOrFail($id)->restore();
        return back()->with('success', 'Tool restored.');
    }

    public function toggleStatus(Tool $tool)
    {
        $tool->update([
            'status' => $tool->status === 'active' ? 'inactive' : 'active'
        ]);
        return back()->with('success', 'Tool status updated.');
    }

    public function toggleFeatured(Tool $tool)
    {
        $tool->update(['is_featured' => !$tool->is_featured]);
        return back()->with('success', 'Featured status updated.');
    }

    public function generateBlade(Tool $tool)
    {
        $this->bladeGen->generate($tool);
        return back()->with('success', 'Blade file generated for ' . $tool->name);
    }

    private function saveInputs(Tool $tool, array $inputs): void
    {
        foreach ($inputs as $i => $input) {
            if (empty($input['field_name'])) continue;
            $tool->inputs()->create([
                'field_name'    => $input['field_name'],
                'field_label'   => $input['field_label'] ?? $input['field_name'],
                'field_type'    => $input['field_type'] ?? 'text',
                'placeholder'   => $input['placeholder'] ?? null,
                'default_value' => $input['default_value'] ?? null,
                'required'      => (bool) ($input['required'] ?? false),
                'options'       => !empty($input['options']) ? json_encode($input['options']) : null,
                'help_text'     => $input['help_text'] ?? null,
                'sort_order'    => $i,
            ]);
        }
    }

    private function saveFaqs(Tool $tool, array $faqs): void
    {
        foreach ($faqs as $i => $faq) {
            if (empty($faq['question'])) continue;
            $tool->faqs()->create([
                'question'   => $faq['question'],
                'answer'     => $faq['answer'] ?? '',
                'sort_order' => $i,
            ]);
        }
    }

    private function saveContents(Tool $tool, array $contents): void
    {
        foreach ($contents as $i => $section) {
            if (empty($section['content'])) continue;
            $tool->contents()->create([
                'section_key' => $section['section_key'] ?? 'section_' . ($i + 1),
                'title'       => $section['title'] ?? null,
                'content'     => $section['content'],
                'sort_order'  => isset($section['sort_order']) ? (int) $section['sort_order'] : $i,
                'is_visible'  => isset($section['is_visible']) ? (bool) $section['is_visible'] : true,
            ]);
        }
    }
}
