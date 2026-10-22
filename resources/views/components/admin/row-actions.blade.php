@props(['edit', 'destroy', 'confirm' => 'Delete this item? This cannot be undone.'])
<div class="flex justify-end gap-1">
    <a href="{{ $edit }}" class="btn-ghost px-2.5 py-1.5" aria-label="Edit"><i data-lucide="pencil" class="size-4"></i></a>
    <form method="POST" action="{{ $destroy }}" data-confirm="{{ $confirm }}">
        @csrf @method('DELETE')
        <button class="btn-ghost px-2.5 py-1.5 text-red-600 hover:bg-red-50 hover:text-red-700 dark:text-red-400 dark:hover:bg-red-950/40" aria-label="Delete"><i data-lucide="trash-2" class="size-4"></i></button>
    </form>
</div>
