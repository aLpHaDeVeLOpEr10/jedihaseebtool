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