<x-layouts.admin title="Project categories">
    <x-admin.header title="Project categories" description="Used for project filters. Deleting a category keeps its projects (they become uncategorised).">
        <a href="{{ route('admin.project-categories.create') }}" class="btn-primary"><i data-lucide="plus"></i> New category</a>
    </x-admin.header>
    <x-admin.table>
        <x-slot:head><tr><th class="px-4 py-3">Name</th><th class="px-4 py-3">Slug</th><th class="px-4 py-3">Projects</th><th class="px-4 py-3">Order</th><th class="px-4 py-3"></th></tr></x-slot:head>
        @forelse ($categories as $category)
            <tr>
                <td class="px-4 py-3 font-medium text-zinc-950 dark:text-white">{{ $category->name }}</td>
                <td class="px-4 py-3 font-mono text-xs text-zinc-500">{{ $category->slug }}</td>
                <td class="px-4 py-3">{{ $category->projects_count }}</td>
                <td class="px-4 py-3 font-mono text-zinc-500">{{ $category->sort_order }}</td>
                <td class="px-4 py-3"><x-admin.row-actions :edit="route('admin.project-categories.edit', $category)" :destroy="route('admin.project-categories.destroy', $category)" /></td>
            </tr>
        @empty
            <tr><td colspan="5" class="px-4 py-8 text-center text-zinc-500">No categories yet.</td></tr>
        @endforelse
    </x-admin.table>
</x-layouts.admin>
