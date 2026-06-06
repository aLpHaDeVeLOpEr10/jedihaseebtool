<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Setting;
use App\Models\Tool;

class HomeController extends Controller
{
    public function index()
    {
        $featuredTools = Tool::active()
            ->featured()
            ->with('category')
            ->ordered()
            ->limit(12)
            ->get();

        $categories = Category::active()
            ->withCount(['activeTools'])
            ->ordered()
            ->get();

        $totalTools = Tool::active()->count();
        $totalCategories = Category::active()->count();

        $recentTools = Tool::active()
            ->with('category')
            ->latest()
            ->limit(8)
            ->get();

        $popularTools = Tool::active()
            ->with('category')
            ->orderByDesc('view_count')
            ->limit(8)
            ->get();

        return view('public.home', compact(
            'featuredTools',
            'categories',
            'totalTools',
            'totalCategories',
            'recentTools',
            'popularTools',
        ));
    }

    public function about()
    {
        return view('public.about');
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function contactSubmit(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:200',
            'subject' => 'nullable|string|max:200',
            'message' => 'required|string|max:5000',
        ]);

        \App\Models\Contact::create([
            ...$validated,
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Thank you for your message! We\'ll get back to you soon.');
    }

    public function privacy()
    {
        return view('public.privacy');
    }

    public function terms()
    {
        return view('public.terms');
    }

    public function page(\App\Models\Page $page)
    {
        abort_unless($page->status === 'published', 404);
        return view('public.page', compact('page'));
    }
}
