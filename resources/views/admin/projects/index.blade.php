<x-layouts.admin title="Projects">
    <x-admin.header title="Projects" description="Drafts are never shown publicly. Featured projects appear first on the home page.">
        <a href="{{ route('admin.projects.create') }}" class="btn-primary"><i data-lucide="plus"></i> New project</a>
    </x-admin.header>

    <x-admin.table>
        <x-slot:head>
            <tr><th class="px-4 py-3">Title</th><th class="px-4 py-3">Category</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Order</th><th class="px-4 py-3"><span class="sr-only">Actions</span></th></tr>
        </x-slot:head>
        @forelse ($projects as $project)
            <tr>
                <td class="px-4 py-3">
                    <a href="{{ route('admin.projects.edit', $project) }}" class="font-medium text-zinc-950 hover:underline dark:text-white">{{ $project->title }}</a>
                    @if ($project->is_featured)<i data-lucide="star" class="ml-1 size-3.5 text-amber-500" aria-label="Featured"></i>@endif
                    <span class="block font-mono text-xs text-zinc-500">/projects/{{ $project->slug }}</span>
                </td>
                <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">{{ $project->category?->name ?? '—' }}</td>
                <td class="px-4 py-3"><x-admin.status :on="$project->isPublished()" offLabel="Draft" /></td>
                <td class="px-4 py-3 font-mono text-zinc-500">{{ $project->sort_order }}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-end gap-1">
                        @if ($project->isPublished())
                            <a href="{{ route('projects.show', $project) }}" target="_blank" class="btn-ghost px-2.5 py-1.5" aria-label="View on site"><i data-lucide="eye" class="size-4"></i></a>
                        @endif
                        <x-admin.row-actions :edit="route('admin.projects.edit', $project)" :destroy="route('admin.projects.destroy', $project)" confirm="Delete this project and its images? This cannot be undone." />
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="px-4 py-8 text-center text-zinc-500">No projects yet.</td></tr>
        @endforelse
    </x-admin.table>
</x-layouts.admin>
