<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Tool;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::active()
            ->withCount('activeTools')
            ->ordered()
            ->get();

        return view('public.categories.index', compact('categories'));
    }

    public function show(string $slug, Request $request)
    {
        $category = Category::active()
            ->where('slug', $slug)
            ->firstOrFail();

        $query = Tool::active()
            ->where('category_id', $category->id)
            ->with('category');

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

        $tools = $query->paginate(24)->withQueryString();

        $relatedCategories = Category::active()
            ->where('id', '!=', $category->id)
            ->withCount('activeTools')
            ->ordered()
            ->limit(8)
            ->get();

        return view('public.categories.show', compact('category', 'tools', 'relatedCategories'));
    }
}
