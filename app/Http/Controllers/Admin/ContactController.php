<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $query = Contact::latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('subject', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            match ($request->status) {
                'unread'   => $query->where('is_read', false),
                'read'     => $query->where('is_read', true),
                'replied'  => $query->where('is_replied', true),
            };
        }

        $contacts = $query->paginate(20)->withQueryString();
        $unreadCount = Contact::where('is_read', false)->count();

        return view('admin.contacts.index', compact('contacts', 'unreadCount'));
    }

    public function show(Contact $contact)
    {
        $contact->update(['is_read' => true]);
        return view('admin.contacts.show', compact('contact'));
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return redirect()->route('admin.contacts.index')->with('success', 'Message deleted.');
    }

    public function markRead(Contact $contact)
    {
        $contact->update(['is_read' => true]);
        return back()->with('success', 'Marked as read.');
    }
}
