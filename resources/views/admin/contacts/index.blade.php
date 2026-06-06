@extends('layouts.admin')
@section('title', 'Messages')
@section('content')
<div class="card overflow-hidden">
    <div class="p-4 border-b border-gray-100 flex items-center justify-between">
        <p class="text-sm text-gray-500">{{ $unreadCount }} unread message{{ $unreadCount !== 1 ? 's' : '' }}</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">From</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Subject</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Date</th>
                    <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($contacts as $contact)
                <tr class="hover:bg-gray-50 {{ !$contact->is_read ? 'font-medium' : '' }}">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            @if(!$contact->is_read)
                            <span class="w-2 h-2 rounded-full bg-brand-500 flex-shrink-0"></span>
                            @endif
                            <div>
                                <p class="text-gray-900">{{ $contact->name }}</p>
                                <p class="text-xs text-gray-400">{{ $contact->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $contact->subject ?: 'No subject' }}</td>
                    <td class="px-4 py-3 text-gray-400 text-xs">{{ $contact->created_at->diffForHumans() }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.contacts.show', $contact) }}" class="btn btn-primary btn-sm">View</a>
                            <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST"
                                  onsubmit="return confirm('Delete this message?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Del</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-12 text-gray-400">No messages yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-100">{{ $contacts->links() }}</div>
</div>
@endsection
