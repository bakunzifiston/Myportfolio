<x-layouts.admin :title="$certification->exists ? 'Edit certification' : 'New certification'">
    <x-admin.header :title="$certification->exists ? 'Edit certification' : 'New certification'" />
    <form method="POST" action="{{ $certification->exists ? route('admin.certifications.update', $certification) : route('admin.certifications.store') }}" class="card grid max-w-3xl gap-5 p-6 sm:grid-cols-2">
        @csrf
        @if ($certification->exists) @method('PUT') @endif
        <x-admin.field name="name" label="Certification name" :value="$certification->name" required class="sm:col-span-2" />
        <x-admin.field name="issuer" label="Issuing organisation" :value="$certification->issuer" />
        <x-admin.field name="credential_id" label="Credential ID" :value="$certification->credential_id" />
        <x-admin.field name="issued_on" label="Issue date" type="date" :value="$certification->issued_on?->format('Y-m-d')" />
        <x-admin.field name="expires_on" label="Expiry date" type="date" :value="$certification->expires_on?->format('Y-m-d')" />
        <x-admin.field name="credential_url" label="Credential URL" type="url" :value="$certification->credential_url" class="sm:col-span-2" />
        <x-admin.field name="sort_order" label="Display order" type="number" :value="$certification->sort_order" />
        <x-admin.field name="is_published" label="Visible on the website and CV" type="checkbox" :value="$certification->is_published" class="sm:pt-7" />
        <div class="flex justify-end gap-3 sm:col-span-2"><a href="{{ route('admin.certifications.index') }}" class="btn-ghost">Cancel</a><button class="btn-primary">Save</button></div>
    </form>
</x-layouts.admin>
