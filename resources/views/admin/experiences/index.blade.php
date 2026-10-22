<x-layouts.admin title="Experience">
    <x-admin.header title="Experience" description="Work experience and project-based contributions. Unpublished entries, and entries without a position or organisation, are never shown publicly.">
        <a href="{{ route('admin.experiences.create', ['type' => 'project']) }}" class="btn-secondary"><i data-lucide="plus"></i> Project contribution</a>
        <a href="{{ route('admin.experiences.create') }}" class="btn-primary"><i data-lucide="plus"></i> Work experience</a>
    </x-admin.header>
    <x-admin.table>
        <x-slot:head><tr><th class="px-4 py-3">Entry</th><th class="px-4 py-3">Type</th><th class="px-4 py-3">Period</th><th class="px-4 py-3">Status</th><th class="px-4 py-3"></th></tr></x-slot:head>
        @forelse ($experiences as $item)
            <tr>
                <td class="px-4 py-3">
                    <a href="{{ route('admin.experiences.edit', $item) }}" class="font-medium text-zinc-950 hover:underline dark:text-white">{{ $item->position ?: 'Position not set' }}</a>
                    <span class="block text-xs text-zinc-500">{{ collect([$item->organization, $item->project?->title])->filter()->implode(' · ') ?: '—' }}</span>
                </td>
                <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">{{ \App\Models\Experience::TYPES[$item->type] ?? $item->type }}</td>
                <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">{{ $item->period() ?? '—' }}</td>
                <td class="px-4 py-3"><x-admin.status :on="$item->is_published" offLabel="Unpublished" /></td>
                <td class="px-4 py-3"><x-admin.row-actions :edit="route('admin.experiences.edit', $item)" :destroy="route('admin.experiences.destroy', $item)" /></td>
            </tr>
        @empty
            <tr><td colspan="5" class="px-4 py-8 text-center text-zinc-500">No entries yet.</td></tr>
        @endforelse
    </x-admin.table>
</x-layouts.admin>
