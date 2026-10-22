<x-layouts.admin title="Education">
    <x-admin.header title="Education" description="Entries are shown as studies unless their status is “Qualification awarded”, which requires explicit confirmation.">
        <a href="{{ route('admin.education.create') }}" class="btn-primary"><i data-lucide="plus"></i> New entry</a>
    </x-admin.header>
    <x-admin.table>
        <x-slot:head><tr><th class="px-4 py-3">Shown as</th><th class="px-4 py-3">Institution</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Visible</th><th class="px-4 py-3"></th></tr></x-slot:head>
        @forelse ($entries as $entry)
            <tr>
                <td class="px-4 py-3"><a href="{{ route('admin.education.edit', $entry) }}" class="font-medium text-zinc-950 hover:underline dark:text-white">{{ $entry->displayTitle() }}</a><span class="block text-xs text-zinc-500">{{ $entry->locationLabel() ?? 'Location not set' }}</span></td>
                <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">{{ $entry->institution ?: 'Not set' }}</td>
                <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">{{ $entry->statusLabel() }}</td>
                <td class="px-4 py-3"><x-admin.status :on="$entry->is_published" /></td>
                <td class="px-4 py-3"><x-admin.row-actions :edit="route('admin.education.edit', $entry)" :destroy="route('admin.education.destroy', $entry)" /></td>
            </tr>
        @empty
            <tr><td colspan="5" class="px-4 py-8 text-center text-zinc-500">No entries yet.</td></tr>
        @endforelse
    </x-admin.table>
</x-layouts.admin>
