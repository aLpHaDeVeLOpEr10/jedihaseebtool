<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Tool;
use App\Services\ToolEngine;
use Illuminate\Http\Request;

class ToolController extends Controller
{
    public function __construct(private ToolEngine $engine) {}

    public function index(Request $request)
    {
        $query = Tool::active()->with('category');

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('type')) {
            $query->where('tool_type', $request->type);
        }

        if ($request->filled('sort')) {
            match ($request->sort) {
                'popular' => $query->orderByDesc('view_count'),
                'newest'  => $query->latest(),
                'name'    => $query->orderBy('name'),
                default   => $query->ordered(),
            };
        } else {
            $query->ordered();
        }

        $tools      = $query->paginate(24)->withQueryString();
        $categories = Category::active()->withCount('activeTools')->ordered()->get();
        $toolTypes  = Tool::active()->distinct()->pluck('tool_type')->sort()->values();

        return view('public.tools.index', compact('tools', 'categories', 'toolTypes'));
    }

    public function show(string $slug)
    {
        $tool = Tool::active()
            ->with(['category', 'contents', 'faqs', 'inputs'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Increment view count
        $tool->incrementViews();

        $relatedTools = $tool->getRelatedTools(4);

        // Determine view
        $customView = 'tools.generated.' . $tool->slug;
        if ($tool->has_custom_blade && view()->exists($customView)) {
            return view($customView, compact('tool', 'relatedTools'));
        }

        return view('public.tools.dynamic', compact('tool', 'relatedTools'));
    }

    public function process(Request $request, string $slug)
    {
        $tool = Tool::active()->where('slug', $slug)->firstOrFail();

        // Rate limiting
        $key = 'tool_process_' . $request->ip() . '_' . $tool->id;
        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($key, 30)) {
            return response()->json(['success' => false, 'error' => 'Too many requests. Please slow down.'], 429);
        }
        \Illuminate\Support\Facades\RateLimiter::hit($key, 60);

        $result = $this->engine->process($tool, $request);
        $tool->incrementUses();

        return response()->json($result);
    }

    public function search(Request $request)
    {
        $q          = $request->q ?? '';
        $category   = $request->category ?? '';
        $type       = $request->type ?? '';

        $query = Tool::active()->with('category');

        if (!empty($q)) {
            $query->where(function ($q2) use ($q) {
                $q2->where('name', 'like', "%{$q}%")
                   ->orWhere('short_description', 'like', "%{$q}%")
                   ->orWhere('long_description', 'like', "%{$q}%")
                   ->orWhere('seo_keywords', 'like', "%{$q}%");
            });
        }

        if (!empty($category)) {
            $query->whereHas('category', fn($q2) => $q2->where('slug', $category));
        }

        if (!empty($type)) {
            $query->where('tool_type', $type);
        }

        $tools      = $query->ordered()->paginate(20)->withQueryString();
        $categories = Category::active()->ordered()->get();

        return view('public.search', compact('tools', 'q', 'category', 'type', 'categories'));
    }
}
