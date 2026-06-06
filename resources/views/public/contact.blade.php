@extends('layouts.public')
@section('title', 'Contact Us - ' . \App\Models\Setting::get('site_name', 'JEDISEBITOOL'))
@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 py-16">
    <div class="text-center mb-10">
        <h1 class="text-4xl font-bold text-gray-900 mb-3">Contact Us</h1>
        <p class="text-gray-500">Have a question, suggestion, or found a bug? We'd love to hear from you.</p>
    </div>

    @if(session('success'))
    <div class="alert-success alert mb-6">{{ session('success') }}</div>
    @endif

    <div class="card p-8">
        <form action="{{ route('contact.submit') }}" method="POST" class="space-y-5">
            @csrf
            <div class="grid sm:grid-cols-2 gap-5">
                <div>
                    <label class="form-label">Your Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-input" required>
                    @error('name')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Email Address *</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-input" required>
                    @error('email')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>
            <div>
                <label class="form-label">Subject</label>
                <input type="text" name="subject" value="{{ old('subject') }}" class="form-input">
            </div>
            <div>
                <label class="form-label">Message *</label>
                <textarea name="message" rows="6" class="form-input" required>{{ old('message') }}</textarea>
                @error('message')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <button type="submit" class="btn btn-primary w-full btn-lg">Send Message</button>
        </form>
    </div>

    <div class="mt-8 grid sm:grid-cols-2 gap-4">
        <div class="card p-5 text-center">
            <div class="text-2xl mb-2">📧</div>
            <p class="font-semibold text-gray-800 text-sm">Email</p>
            <a href="mailto:{{ \App\Models\Setting::get('contact_email', 'hello@jedisebitool.com') }}" class="text-brand-600 text-sm hover:underline">
                {{ \App\Models\Setting::get('contact_email', 'hello@jedisebitool.com') }}
            </a>
        </div>
        <div class="card p-5 text-center">
            <div class="text-2xl mb-2">⏱️</div>
            <p class="font-semibold text-gray-800 text-sm">Response Time</p>
            <p class="text-gray-500 text-sm">Usually within 24-48 hours</p>
        </div>
    </div>
</div>
@endsection
