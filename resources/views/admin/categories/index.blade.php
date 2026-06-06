@extends('layouts.admin')
@section('title', 'Manage Categories')

@section('header_actions')
<a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">+ New Category</a>
@endsection

@section('content')
<div class="card overflow-hidden">
    <div class="p-4 border-b border-gray-100">
        <form action="{{ route('admin.categories.index') }}" method="GET" class="flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search categories..." class="form-input flex-1">
            <button type="submit" class="btn btn-primary">Search</button>
            @if(request('search'))
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Clear</a>
            @endif
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Category</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Slug</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Tools</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Order</th>
                    <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($categories as $cat)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">{{ $cat->icon }}</span>
                            <div>
                                <p class="font-medium text-gray-900">{{ $cat->name }}</p>
                                @if($cat->description)
                                <p class="text-xs text-gray-400 truncate max-w-xs">{{ $cat->description }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 font-mono text-xs text-gray-500">{{ $cat->slug }}</td>
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('admin.tools.index', ['category' => $cat->id]) }}"
                           class="badge badge-primary hover:bg-brand-200 transition-colors">
                            {{ $cat->tools_count }}
                        </a>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="badge {{ $cat->is_active ? 'badge-success' : 'badge-gray' }}">
                            {{ $cat->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center text-gray-500">{{ $cat->sort_order }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('categories.show', $cat) }}" target="_blank"
                               class="btn btn-secondary btn-sm">View</a>
                            <a href="{{ route('admin.categories.edit', $cat) }}"
                               class="btn btn-primary btn-sm">Edit</a>
                            <form action="{{ route('admin.categories.toggle-status', $cat) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-secondary btn-sm">
                                    {{ $cat->is_active ? 'Hide' : 'Show' }}
                                </button>
                            </form>
                            <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST"
                                  onsubmit="return confirm('Delete this category? Tools must be removed first.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Del</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-12 text-gray-400">
                        No categories yet. <a href="{{ route('admin.categories.create') }}" class="text-brand-600">Create one →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-100">
        {{ $categories->withQueryString()->links() }}
    </div>
</div>
@endsection
