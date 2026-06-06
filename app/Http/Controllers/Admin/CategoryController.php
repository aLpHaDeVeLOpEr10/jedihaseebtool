<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::withCount('tools');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $categories = $query->orderBy('sort_order')->orderBy('name')->paginate(20)->withQueryString();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:200',
            'slug'            => 'nullable|string|max:200|unique:categories,slug',
            'description'     => 'nullable|string',
            'icon'            => 'nullable|string|max:10',
            'color'           => 'nullable|string|max:20',
            'is_active'       => 'boolean',
            'sort_order'      => 'nullable|integer',
            'seo_title'       => 'nullable|string|max:200',
            'seo_description' => 'nullable|string|max:500',
            'seo_keywords'    => 'nullable|string|max:500',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $baseSlug = $validated['slug'];
        $i = 1;
        while (Category::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $baseSlug . '-' . $i++;
        }

        $validated['is_active'] = $request->boolean('is_active');
        Category::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Category created!');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:200',
            'slug'            => 'required|string|max:200|unique:categories,slug,' . $category->id,
            'description'     => 'nullable|string',
            'icon'            => 'nullable|string|max:10',
            'color'           => 'nullable|string|max:20',
            'is_active'       => 'boolean',
            'sort_order'      => 'nullable|integer',
            'seo_title'       => 'nullable|string|max:200',
            'seo_description' => 'nullable|string|max:500',
            'seo_keywords'    => 'nullable|string|max:500',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $category->update($validated);

        return back()->with('success', 'Category updated!');
    }

    public function destroy(Category $category)
    {
        if ($category->tools()->count() > 0) {
            return back()->with('error', 'Cannot delete category with tools. Move or delete tools first.');
        }
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted.');
    }

    public function toggleStatus(Category $category)
    {
        $category->update(['is_active' => !$category->is_active]);
        return back()->with('success', 'Category status updated.');
    }
}
