@extends('layouts.admin')
@section('title', 'Message from ' . $contact->name)
@section('content')
<div class="max-w-2xl">
    <div class="card p-4 sm:p-6 mb-4">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-5">
            <div class="min-w-0">
                <h2 class="font-semibold text-gray-900 text-lg break-words">{{ $contact->subject ?: 'No subject' }}</h2>
                <p class="text-sm text-gray-500 mt-1 break-words">From: <strong>{{ $contact->name }}</strong> &lt;{{ $contact->email }}&gt;</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ $contact->created_at->format('D, M j, Y \a\t g:ia') }}</p>
            </div>
            <div class="flex gap-2 flex-shrink-0">
                <a href="mailto:{{ $contact->email }}" class="btn btn-primary btn-sm">Reply via Email</a>
                <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST"
                      onsubmit="return confirm('Delete?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </div>
        </div>
        <div class="bg-gray-50 rounded-xl p-4 sm:p-5 text-gray-700 leading-relaxed whitespace-pre-wrap break-words">{{ $contact->message }}</div>
    </div>
    <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary">← Back to Messages</a>
</div>
@endsection
