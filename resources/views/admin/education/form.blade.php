<x-layouts.admin :title="$entry->exists ? 'Edit education' : 'New education entry'">
    <x-admin.header :title="$entry->exists ? 'Edit education' : 'New education entry'" />
    <form method="POST" action="{{ $entry->exists ? route('admin.education.update', $entry) : route('admin.education.store') }}" class="space-y-6">
        @csrf
        @if ($entry->exists) @method('PUT') @endif
        <section class="card grid gap-5 p-6 sm:grid-cols-2">
            <x-admin.field name="level" label="Level" type="select" :value="$entry->level" :options="\App\Models\Education::LEVELS" required />
            <x-admin.field name="field_of_study" label="Field of study" :value="$entry->field_of_study" placeholder="Computer Science" />
            <x-admin.field name="institution" label="Institution" :value="$entry->institution" help="Leave empty until confirmed." />
            <x-admin.field name="city" label="City" :value="$entry->city" />
            <x-admin.field name="country" label="Country" :value="$entry->country" />
            <div class="grid grid-cols-2 gap-4">
                <x-admin.field name="start_year" label="Start year" type="number" :value="$entry->start_year" />
                <x-admin.field name="end_year" label="End year" type="number" :value="$entry->end_year" />
            </div>
            <x-admin.field name="description" label="Description" type="textarea" rows="3" :value="$entry->description" class="sm:col-span-2" />
        </section>

        <section class="card grid gap-5 p-6 sm:grid-cols-2">
            <h2 class="font-semibold sm:col-span-2">Completion status</h2>
            <x-admin.field name="status" label="Status" type="select" :value="$entry->status" :options="\App\Models\Education::STATUSES" required
                           help="Use “Qualification awarded” only for a completed programme with the qualification conferred." />
            <x-admin.field name="qualification_title" label="Official qualification title" :value="$entry->qualification_title"
                           help="e.g. “BSc in Computer Science”. Shown publicly only when the status is “Qualification awarded”." />
            <x-admin.field name="confirm_awarded" label="I confirm this programme was completed and the qualification was awarded" type="checkbox" :value="$entry->isAwarded()" class="sm:col-span-2" />
            <x-admin.field name="sort_order" label="Display order" type="number" :value="$entry->sort_order" />
            <x-admin.field name="is_published" label="Visible on the website and CV" type="checkbox" :value="$entry->is_published" class="sm:pt-7" />
        </section>
        <div class="flex justify-end gap-3"><a href="{{ route('admin.education.index') }}" class="btn-ghost">Cancel</a><button class="btn-primary">Save</button></div>
    </form>
</x-layouts.admin>
