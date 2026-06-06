<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Tool;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_tools'      => Tool::count(),
            'active_tools'     => Tool::where('status', 'active')->count(),
            'total_categories' => Category::count(),
            'total_views'      => Tool::sum('view_count'),
            'total_uses'       => Tool::sum('use_count'),
            'unread_contacts'  => Contact::where('is_read', false)->count(),
            'featured_tools'   => Tool::where('is_featured', true)->count(),
        ];

        $recentTools = Tool::with('category')->latest()->limit(5)->get();
        $topTools    = Tool::with('category')->orderByDesc('view_count')->limit(5)->get();
        $recentContacts = Contact::latest()->limit(5)->get();

        $toolsByType = Tool::selectRaw('tool_type, count(*) as count')
            ->groupBy('tool_type')
            ->orderByDesc('count')
            ->get();

        $toolsByCategory = Category::withCount('tools')
            ->orderByDesc('tools_count')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 'recentTools', 'topTools', 'recentContacts', 'toolsByType', 'toolsByCategory'
        ));
    }
}
