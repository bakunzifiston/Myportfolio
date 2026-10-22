<x-layouts.admin :title="$link->exists ? 'Edit link' : 'New link'">
    <x-admin.header :title="$link->exists ? 'Edit '.$link->label : 'New link'" />
    <form method="POST" action="{{ $link->exists ? route('admin.social-links.update', $link) : route('admin.social-links.store') }}" class="card grid max-w-3xl gap-5 p-6 sm:grid-cols-2">
        @csrf
        @if ($link->exists) @method('PUT') @endif
        <x-admin.field name="platform" label="Platform key" :value="$link->platform" required help="e.g. linkedin, github, x, website" />
        <x-admin.field name="label" label="Label" :value="$link->label" required />
        <x-admin.field name="url" label="Profile URL" type="url" :value="$link->url" placeholder="https://" class="sm:col-span-2" />
        <x-admin.field name="icon" label="Icon" :value="$link->icon" help="github, linkedin and x have brand icons; anything else shows a link icon." />
        <x-admin.field name="sort_order" label="Display order" type="number" :value="$link->sort_order" />
        <x-admin.field name="is_visible" label="Visible" type="checkbox" :value="$link->is_visible" />
        <div class="flex justify-end gap-3 sm:col-span-2"><a href="{{ route('admin.social-links.index') }}" class="btn-ghost">Cancel</a><button class="btn-primary">Save</button></div>
    </form>
</x-layouts.admin>
