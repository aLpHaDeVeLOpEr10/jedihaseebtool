@extends('layouts.admin')
@section('title', 'Manage Tools')

@section('header_actions')
<a href="{{ route('admin.tools.create') }}" class="btn btn-primary btn-sm">+ New Tool</a>
@endsection

@section('content')
{{-- Filters --}}
<div class="card p-4 mb-6">
    <form action="{{ route('admin.tools.index') }}" method="GET" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-48">
            <label class="form-label">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Tool name..." class="form-input">
        </div>
        <div class="w-44">
            <label class="form-label">Category</label>
            <select name="category" class="form-input">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-36">
            <label class="form-label">Status</label>
            <select name="status" class="form-input">
                <option value="">All Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
            </select>
        </div>
        <div class="w-36">
            <label class="form-label">Type</label>
            <select name="type" class="form-input">
                <option value="">All Types</option>
                @foreach($toolTypes as $type)
                <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('admin.tools.index') }}" class="btn btn-secondary">Clear</a>
        </div>
    </form>
</div>

{{-- Table --}}
<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tool</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Views</th>
                    <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($tools as $tool)
                <tr class="hover:bg-gray-50 transition-colors {{ $tool->trashed() ? 'opacity-50' : '' }}">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center text-base flex-shrink-0"
                                 style="background: {{ $tool->color }}22">
                                {{ $tool->icon }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $tool->name }}</p>
                                <p class="text-xs text-gray-400 font-mono">{{ $tool->slug }}</p>
                            </div>
                            @if($tool->is_featured)
                            <span class="text-yellow-400 text-xs">★</span>
                            @endif
                            @if($tool->has_custom_blade)
                            <span class="badge badge-primary text-xs">custom</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        {{ $tool->category->name ?? '—' }}
                    </td>
                    <td class="px-4 py-3">
                        <span class="badge badge-gray capitalize">{{ $tool->tool_type }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="badge {{ match($tool->status) { 'active' => 'badge-success', 'inactive' => 'badge-gray', 'draft' => 'badge-warning', default => 'badge-gray' } }}">
                            {{ $tool->status }}
                        </span>
                        @if($tool->trashed())
                        <span class="badge badge-danger ml-1">deleted</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right text-gray-600">
                        {{ number_format($tool->view_count) }}
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-2">
                            @if($tool->trashed())
                            <form action="{{ route('admin.tools.restore', $tool->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-secondary btn-sm">Restore</button>
                            </form>
                            @else
                            <a href="{{ route('tools.show', $tool->slug) }}" target="_blank"
                               class="btn btn-secondary btn-sm">View</a>
                            <a href="{{ route('admin.tools.edit', $tool) }}" class="btn btn-primary btn-sm">Edit</a>
                            <form action="{{ route('admin.tools.toggle-status', $tool) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-secondary btn-sm">
                                    {{ $tool->status === 'active' ? 'Disable' : 'Enable' }}
                                </button>
                            </form>
                            <form action="{{ route('admin.tools.destroy', $tool) }}" method="POST"
                                  onsubmit="return confirm('Delete this tool?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Del</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-12 text-gray-400">
                        No tools found. <a href="{{ route('admin.tools.create') }}" class="text-brand-600 hover:underline">Create your first tool →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-100">
        {{ $tools->withQueryString()->links() }}
    </div>
</div>
@endsection
