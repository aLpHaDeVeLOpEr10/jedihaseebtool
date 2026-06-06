<?php

namespace App\Services;

use App\Models\Tool;
use Illuminate\Support\Facades\File;

class BladeGeneratorService
{
    public function generate(Tool $tool): string
    {
        $path = resource_path('views/tools/generated/' . $tool->slug . '.blade.php');

        // Create directory if needed
        if (!File::exists(dirname($path))) {
            File::makeDirectory(dirname($path), 0755, true);
        }

        $content = $this->buildBladeContent($tool);
        File::put($path, $content);

        // Update tool record
        $tool->update([
            'blade_path'      => 'tools.generated.' . $tool->slug,
            'has_custom_blade' => true,
        ]);

        return $path;
    }

    private function buildBladeContent(Tool $tool): string
    {
        $toolType = $tool->tool_type;

        // Choose template based on tool type
        $templateMethod = match ($toolType) {
            'calculator'   => 'calculatorTemplate',
            'converter'    => 'converterTemplate',
            'generator'    => 'generatorTemplate',
            'text'         => 'textTemplate',
            'productivity' => 'productivityTemplate',
            default        => 'genericTemplate',
        };

        return $this->$templateMethod($tool);
    }

    private function genericTemplate(Tool $tool): string
    {
        $name  = addslashes($tool->name);
        $slug  = $tool->slug;
        $type  = $tool->tool_type;
        $icon  = $tool->icon;
        $desc  = addslashes($tool->short_description ?? '');

        return <<<BLADE
@extends('layouts.public')

@section('title', \$tool->seo_title)
@section('description', \$tool->seo_description)

@section('content')
<div class="min-h-screen bg-gray-50">
    {{-- Hero Section --}}
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
            <div class="flex items-center gap-4 mb-4">
                <div class="tool-icon bg-brand-100 text-brand-600 text-3xl w-14 h-14">
                    {{ \$tool->icon }}
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ \$tool->name }}</h1>
                    <p class="text-gray-500 mt-1">{{ \$tool->short_description }}</p>
                </div>
            </div>
            <x-breadcrumb :items="[
                ['label' => 'Home', 'url' => url('/')],
                ['label' => \$tool->category->name, 'url' => route('categories.show', \$tool->category)],
                ['label' => \$tool->name]
            ]"/>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
        <div class="grid gap-8 lg:grid-cols-3">
            {{-- Main Tool Area --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Tool Card --}}
                <div class="card p-6"
                     x-data="toolRunner('$slug')"
                     x-init="init()">

                    <h2 class="text-lg font-semibold text-gray-900 mb-5">Use the Tool</h2>

                    <form @submit.prevent="submit()" class="space-y-4">
                        @foreach(\$tool->inputs->where('is_visible', true) as \$input)
                            <x-tool-input :input="\$input" />
                        @endforeach

                        @if(\$tool->inputs->isEmpty())
                            <div class="alert alert-info">
                                This tool's input form is being configured. Check back soon.
                            </div>
                        @endif

                        <button type="submit" class="btn btn-primary w-full btn-lg"
                                :disabled="loading">
                            <span x-show="!loading">⚡ Run Tool</span>
                            <span x-show="loading" class="flex items-center gap-2">
                                <span class="spinner"></span> Processing...
                            </span>
                        </button>
                    </form>

                    {{-- Results --}}
                    <div x-show="result" x-cloak class="mt-6 result-animate">
                        <x-tool-result />
                    </div>

                    {{-- Error --}}
                    <div x-show="error" x-cloak class="mt-4">
                        <div class="alert alert-error" x-text="error"></div>
                    </div>
                </div>

                {{-- Long Description --}}
                @if(\$tool->long_description)
                <div class="card p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">About This Tool</h2>
                    <div class="tool-prose">
                        {!! nl2br(e(\$tool->long_description)) !!}
                    </div>
                </div>
                @endif

                {{-- FAQs --}}
                @if(\$tool->faqs->count() > 0)
                <div class="card p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Frequently Asked Questions</h2>
                    <x-faq-list :faqs="\$tool->faqs" />
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Category --}}
                <div class="card p-5">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Category</h3>
                    <a href="{{ route('categories.show', \$tool->category) }}"
                       class="flex items-center gap-3 p-3 rounded-xl bg-brand-50 hover:bg-brand-100 transition-colors">
                        <span class="text-xl">{{ \$tool->category->icon }}</span>
                        <span class="font-medium text-brand-700">{{ \$tool->category->name }}</span>
                    </a>
                </div>

                {{-- Related Tools --}}
                @if(\$relatedTools->count() > 0)
                <div class="card p-5">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Related Tools</h3>
                    <div class="space-y-2">
                        @foreach(\$relatedTools as \$related)
                        <a href="{{ route('tools.show', \$related) }}"
                           class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-gray-50 transition-colors group">
                            <span class="text-lg">{{ \$related->icon }}</span>
                            <span class="text-sm text-gray-700 group-hover:text-brand-600 transition-colors">{{ \$related->name }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
BLADE;
    }

    private function calculatorTemplate(Tool $tool): string
    {
        return $this->genericTemplate($tool);
    }

    private function converterTemplate(Tool $tool): string
    {
        return $this->genericTemplate($tool);
    }

    private function generatorTemplate(Tool $tool): string
    {
        return $this->genericTemplate($tool);
    }

    private function textTemplate(Tool $tool): string
    {
        return $this->genericTemplate($tool);
    }

    private function productivityTemplate(Tool $tool): string
    {
        $slug = $tool->slug;

        if (str_contains($slug, 'todo') || str_contains($slug, 'to-do')) {
            return $this->todoTemplate($tool);
        }

        if (str_contains($slug, 'notes')) {
            return $this->notesTemplate($tool);
        }

        return $this->genericTemplate($tool);
    }

    private function todoTemplate(Tool $tool): string
    {
        return <<<'BLADE'
@extends('layouts.public')

@section('title', $tool->seo_title)
@section('description', $tool->seo_description)

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 py-10">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                {{ $tool->icon }} {{ $tool->name }}
            </h1>
            <p class="text-gray-500 mt-2">{{ $tool->short_description }}</p>
        </div>
    </div>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 py-10"
         x-data="todoApp()"
         x-init="loadTodos()">

        <div class="card p-6">
            {{-- Add Todo Form --}}
            <form @submit.prevent="addTodo()" class="flex gap-3 mb-6">
                <input type="text"
                       x-model="newTodo"
                       placeholder="Add a new task..."
                       class="form-input flex-1"
                       required>
                <button type="submit" class="btn btn-primary">Add</button>
            </form>

            {{-- Filter Tabs --}}
            <div class="flex gap-2 mb-4">
                <button @click="filter='all'" :class="filter==='all' ? 'btn-primary' : 'btn-secondary'" class="btn btn-sm">
                    All (<span x-text="todos.length"></span>)
                </button>
                <button @click="filter='active'" :class="filter==='active' ? 'btn-primary' : 'btn-secondary'" class="btn btn-sm">
                    Active (<span x-text="todos.filter(t=>!t.done).length"></span>)
                </button>
                <button @click="filter='done'" :class="filter==='done' ? 'btn-primary' : 'btn-secondary'" class="btn btn-sm">
                    Done (<span x-text="todos.filter(t=>t.done).length"></span>)
                </button>
            </div>

            {{-- Todo List --}}
            <ul class="space-y-2">
                <template x-for="todo in filteredTodos" :key="todo.id">
                    <li class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 hover:border-brand-200 transition-colors group">
                        <input type="checkbox"
                               :checked="todo.done"
                               @change="toggleTodo(todo.id)"
                               class="w-5 h-5 rounded text-brand-600">
                        <span :class="todo.done ? 'line-through text-gray-400' : 'text-gray-700'"
                              class="flex-1 text-sm" x-text="todo.text"></span>
                        <span class="text-xs text-gray-400" x-text="todo.date"></span>
                        <button @click="deleteTodo(todo.id)"
                                class="opacity-0 group-hover:opacity-100 text-red-400 hover:text-red-600 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </li>
                </template>
                <li x-show="filteredTodos.length === 0" class="text-center py-8 text-gray-400">
                    No tasks here. Add one above!
                </li>
            </ul>

            {{-- Footer --}}
            <div x-show="todos.length > 0" class="mt-4 flex items-center justify-between text-sm text-gray-500">
                <span x-text="`${todos.filter(t=>t.done).length} of ${todos.length} completed`"></span>
                <button @click="clearDone()" class="text-red-500 hover:text-red-700">Clear completed</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function todoApp() {
    return {
        todos: [],
        newTodo: '',
        filter: 'all',
        get filteredTodos() {
            if (this.filter === 'active') return this.todos.filter(t => !t.done);
            if (this.filter === 'done') return this.todos.filter(t => t.done);
            return this.todos;
        },
        loadTodos() {
            const saved = localStorage.getItem('jedisebi_todos');
            this.todos = saved ? JSON.parse(saved) : [];
        },
        saveTodos() {
            localStorage.setItem('jedisebi_todos', JSON.stringify(this.todos));
        },
        addTodo() {
            if (!this.newTodo.trim()) return;
            this.todos.unshift({
                id: Date.now(),
                text: this.newTodo.trim(),
                done: false,
                date: new Date().toLocaleDateString()
            });
            this.newTodo = '';
            this.saveTodos();
        },
        toggleTodo(id) {
            const todo = this.todos.find(t => t.id === id);
            if (todo) todo.done = !todo.done;
            this.saveTodos();
        },
        deleteTodo(id) {
            this.todos = this.todos.filter(t => t.id !== id);
            this.saveTodos();
        },
        clearDone() {
            this.todos = this.todos.filter(t => !t.done);
            this.saveTodos();
        }
    };
}
</script>
@endpush
@endsection
BLADE;
    }

    private function notesTemplate(Tool $tool): string
    {
        return <<<'BLADE'
@extends('layouts.public')

@section('title', $tool->seo_title)
@section('description', $tool->seo_description)

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                {{ $tool->icon }} {{ $tool->name }}
            </h1>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8"
         x-data="notesApp()"
         x-init="loadNotes()">

        <div class="grid grid-cols-12 gap-6 h-[600px]">
            {{-- Sidebar: Note List --}}
            <div class="col-span-4 card flex flex-col overflow-hidden">
                <div class="p-4 border-b border-gray-100 flex items-center gap-2">
                    <input type="text" x-model="search" placeholder="Search notes..."
                           class="form-input flex-1 text-sm">
                    <button @click="newNote()" class="btn btn-primary btn-sm">+ New</button>
                </div>
                <ul class="flex-1 overflow-y-auto divide-y divide-gray-50">
                    <template x-for="note in filteredNotes" :key="note.id">
                        <li @click="select(note.id)"
                            :class="current?.id === note.id ? 'bg-brand-50 border-l-2 border-brand-500' : 'hover:bg-gray-50'"
                            class="p-4 cursor-pointer transition-colors">
                            <p class="font-medium text-sm text-gray-800 truncate" x-text="note.title || 'Untitled'"></p>
                            <p class="text-xs text-gray-400 mt-1 truncate" x-text="note.content.substring(0, 60)"></p>
                            <p class="text-xs text-gray-300 mt-1" x-text="note.updated"></p>
                        </li>
                    </template>
                    <li x-show="filteredNotes.length === 0" class="p-6 text-center text-gray-400 text-sm">
                        No notes found.
                    </li>
                </ul>
            </div>

            {{-- Editor --}}
            <div class="col-span-8 card flex flex-col overflow-hidden">
                <template x-if="current">
                    <div class="flex-1 flex flex-col">
                        <div class="p-4 border-b border-gray-100 flex items-center gap-2">
                            <input type="text" x-model="current.title" @input="save()"
                                   placeholder="Note title..."
                                   class="form-input flex-1 font-medium">
                            <button @click="deleteNote(current.id)" class="btn btn-danger btn-sm">Delete</button>
                        </div>
                        <textarea x-model="current.content" @input="save()"
                                  placeholder="Start writing..."
                                  class="flex-1 p-4 resize-none border-0 focus:ring-0 text-sm text-gray-700 outline-none"></textarea>
                        <div class="px-4 py-2 border-t border-gray-100 text-xs text-gray-400 flex justify-between">
                            <span x-text="`${current.content.split(' ').filter(w=>w).length} words`"></span>
                            <span x-text="`Saved ${current.updated}`"></span>
                        </div>
                    </div>
                </template>
                <template x-if="!current">
                    <div class="flex-1 flex items-center justify-center">
                        <div class="text-center text-gray-400">
                            <p class="text-4xl mb-3">📝</p>
                            <p class="text-sm">Select a note or create a new one</p>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function notesApp() {
    return {
        notes: [],
        current: null,
        search: '',
        get filteredNotes() {
            if (!this.search) return this.notes;
            return this.notes.filter(n =>
                n.title.toLowerCase().includes(this.search.toLowerCase()) ||
                n.content.toLowerCase().includes(this.search.toLowerCase())
            );
        },
        loadNotes() {
            const saved = localStorage.getItem('jedisebi_notes');
            this.notes = saved ? JSON.parse(saved) : [];
            if (this.notes.length > 0) this.select(this.notes[0].id);
        },
        saveNotes() {
            localStorage.setItem('jedisebi_notes', JSON.stringify(this.notes));
        },
        newNote() {
            const note = { id: Date.now(), title: '', content: '', updated: new Date().toLocaleString() };
            this.notes.unshift(note);
            this.current = note;
            this.saveNotes();
        },
        select(id) {
            this.current = this.notes.find(n => n.id === id) || null;
        },
        save() {
            if (!this.current) return;
            this.current.updated = new Date().toLocaleString();
            const idx = this.notes.findIndex(n => n.id === this.current.id);
            if (idx !== -1) this.notes[idx] = { ...this.current };
            this.saveNotes();
        },
        deleteNote(id) {
            if (!confirm('Delete this note?')) return;
            this.notes = this.notes.filter(n => n.id !== id);
            this.current = this.notes[0] || null;
            this.saveNotes();
        }
    };
}
</script>
@endpush
@endsection
BLADE;
    }

    public function delete(Tool $tool): bool
    {
        $path = resource_path('views/tools/generated/' . $tool->slug . '.blade.php');
        if (File::exists($path)) {
            File::delete($path);
            return true;
        }
        return false;
    }

    public function exists(Tool $tool): bool
    {
        $path = resource_path('views/tools/generated/' . $tool->slug . '.blade.php');
        return File::exists($path);
    }
}
