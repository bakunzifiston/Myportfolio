<x-layouts.admin :title="$category->exists ? 'Edit skills' : 'New skill category'">
    <x-admin.header :title="$category->exists ? 'Edit '.$category->name : 'New skill category'" />
    <form method="POST" action="{{ $category->exists ? route('admin.skill-categories.update', $category) : route('admin.skill-categories.store') }}" class="card grid max-w-3xl gap-5 p-6 sm:grid-cols-2">
        @csrf
        @if ($category->exists) @method('PUT') @endif
        <x-admin.field name="name" label="Category name" :value="$category->name" required />
        <x-admin.field name="slug" label="Slug" :value="$category->slug" help="Leave empty to generate from the name." />
        <x-admin.field name="icon" label="Icon" :value="$category->icon" help="A Lucide icon name, e.g. database, server, code-xml, network." />
        <x-admin.field name="sort_order" label="Display order" type="number" :value="$category->sort_order" />
        <x-admin.field name="description" label="Short description" :value="$category->description" class="sm:col-span-2" />
        <x-admin.field name="skills" label="Skills" type="lines" rows="10" :value="$category->skills?->pluck('name')->all()" help="One skill per line, in display order. Removing a line removes the skill." class="sm:col-span-2" />
        <div class="flex justify-end gap-3 sm:col-span-2"><a href="{{ route('admin.skill-categories.index') }}" class="btn-ghost">Cancel</a><button class="btn-primary">Save</button></div>
    </form>
</x-layouts.admin>
