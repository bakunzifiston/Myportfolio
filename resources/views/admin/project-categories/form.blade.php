<x-layouts.admin :title="$category->exists ? 'Edit category' : 'New category'">
    <x-admin.header :title="$category->exists ? 'Edit category' : 'New category'" />
    <form method="POST" action="{{ $category->exists ? route('admin.project-categories.update', $category) : route('admin.project-categories.store') }}" class="card grid max-w-2xl gap-5 p-6">
        @csrf
        @if ($category->exists) @method('PUT') @endif
        <x-admin.field name="name" label="Name" :value="$category->name" required />
        <x-admin.field name="slug" label="Slug" :value="$category->slug" help="Leave empty to generate from the name." />
        <x-admin.field name="sort_order" label="Display order" type="number" :value="$category->sort_order" />
        <div class="flex justify-end gap-3"><a href="{{ route('admin.project-categories.index') }}" class="btn-ghost">Cancel</a><button class="btn-primary">Save</button></div>
    </form>
</x-layouts.admin>
