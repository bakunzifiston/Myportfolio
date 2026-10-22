<x-layouts.admin title="Social links">
    <x-admin.header title="Social links" description="Links without a URL are hidden automatically.">
        <a href="{{ route('admin.social-links.create') }}" class="btn-primary"><i data-lucide="plus"></i> New link</a>
    </x-admin.header>
    <x-admin.table>
        <x-slot:head><tr><th class="px-4 py-3">Platform</th><th class="px-4 py-3">URL</th><th class="px-4 py-3">Visible</th><th class="px-4 py-3"></th></tr></x-slot:head>
        @forelse ($links as $link)
            <tr>
                <td class="px-4 py-3 font-medium text-zinc-950 dark:text-white"><span class="inline-flex items-center gap-2"><x-site.social-icon :link="$link" /> {{ $link->label }}</span></td>
                <td class="px-4 py-3 break-all text-zinc-600 dark:text-zinc-400">{{ $link->url ?: 'Not set' }}</td>
                <td class="px-4 py-3"><x-admin.status :on="$link->is_visible && filled($link->url)" onLabel="Visible" /></td>
                <td class="px-4 py-3"><x-admin.row-actions :edit="route('admin.social-links.edit', $link)" :destroy="route('admin.social-links.destroy', $link)" /></td>
            </tr>
        @empty
            <tr><td colspan="4" class="px-4 py-8 text-center text-zinc-500">No links yet.</td></tr>
        @endforelse
    </x-admin.table>
</x-layouts.admin>
