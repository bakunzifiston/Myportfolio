<x-layouts.admin title="Certifications">
    <x-admin.header title="Certifications" description="The certifications section only appears on the site and CV once at least one visible entry exists.">
        <a href="{{ route('admin.certifications.create') }}" class="btn-primary"><i data-lucide="plus"></i> New certification</a>
    </x-admin.header>
    <x-admin.table>
        <x-slot:head><tr><th class="px-4 py-3">Name</th><th class="px-4 py-3">Issuer</th><th class="px-4 py-3">Issued</th><th class="px-4 py-3">Visible</th><th class="px-4 py-3"></th></tr></x-slot:head>
        @forelse ($certifications as $cert)
            <tr>
                <td class="px-4 py-3 font-medium text-zinc-950 dark:text-white">{{ $cert->name }}</td>
                <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">{{ $cert->issuer ?? '—' }}</td>
                <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">{{ $cert->issued_on?->format('M Y') ?? '—' }}</td>
                <td class="px-4 py-3"><x-admin.status :on="$cert->is_published" /></td>
                <td class="px-4 py-3"><x-admin.row-actions :edit="route('admin.certifications.edit', $cert)" :destroy="route('admin.certifications.destroy', $cert)" /></td>
            </tr>
        @empty
            <tr><td colspan="5" class="px-4 py-8 text-center text-zinc-500">No certifications yet.</td></tr>
        @endforelse
    </x-admin.table>
</x-layouts.admin>
