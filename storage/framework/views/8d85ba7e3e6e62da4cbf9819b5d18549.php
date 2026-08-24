

<?php $__env->startSection('title', $tool->seo_title); ?>
<?php $__env->startSection('description', $tool->seo_description); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50">
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                <?php echo e($tool->icon); ?> <?php echo e($tool->name); ?>

            </h1>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8"
         x-data="notesApp()"
         x-init="loadNotes()">

        <div class="grid grid-cols-12 gap-6 h-[600px]">
            
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

<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\New folder\jedihaseebtool\resources\views\tools\generated\notes-app.blade.php ENDPATH**/ ?>