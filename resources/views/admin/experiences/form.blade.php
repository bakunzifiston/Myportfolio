<x-layouts.admin :title="$experience->exists ? 'Edit experience' : 'New experience'">
    <x-admin.header :title="$experience->exists ? 'Edit experience' : 'New experience'" description="Record only confirmed roles and responsibilities. A position is required before an entry can be published." />
    <form method="POST" action="{{ $experience->exists ? route('admin.experiences.update', $experience) : route('admin.experiences.store') }}" class="space-y-6">
        @csrf
        @if ($experience->exists) @method('PUT') @endif
        <section class="card grid gap-5 p-6 sm:grid-cols-2">
            <x-admin.field name="type" label="Type" type="select" :value="$experience->type" :options="\App\Models\Experience::TYPES" required />
            <x-admin.field name="project_id" label="Related project" type="select" :value="$experience->project_id" :options="['' => '— None —'] + $projects->pluck('title', 'id')->all()" />
            <x-admin.field name="position" label="Position / role" :value="$experience->position" />
            <x-admin.field name="organization" label="Organisation" :value="$experience->organization" help="Required for work experience." />
            <x-admin.field name="employment_type" label="Employment type" :value="$experience->employment_type" placeholder="Full-time, Contract, Consultancy…" />
            <x-admin.field name="location" label="Location" :value="$experience->location" placeholder="City, Country or Remote" />
            <x-admin.field name="start_date" label="Start date" type="date" :value="$experience->start_date?->format('Y-m-d')" />
            <x-admin.field name="end_date" label="End date" type="date" :value="$experience->end_date?->format('Y-m-d')" />
            <x-admin.field name="is_current" label="I currently hold this role" type="checkbox" :value="$experience->is_current" class="sm:col-span-2" />
        </section>
        <section class="card grid gap-5 p-6 sm:grid-cols-2">
            <x-admin.field name="summary" label="Summary" type="textarea" rows="3" :value="$experience->summary" class="sm:col-span-2" />
            <x-admin.field name="responsibilities" label="Responsibilities" type="lines" rows="6" :value="$experience->responsibilities" help="One per line." />
            <x-admin.field name="contributions" label="Contributions" type="lines" rows="6" :value="$experience->contributions" help="One per line." />
            <x-admin.field name="technologies" label="Technologies used" type="lines" rows="4" :value="$experience->technologies" help="One per line." />
            <div class="space-y-5">
                <x-admin.field name="sort_order" label="Display order" type="number" :value="$experience->sort_order" />
                <x-admin.field name="is_published" label="Published" type="checkbox" :value="$experience->is_published" help="Show this entry on the website and CV." />
            </div>
        </section>
        <div class="flex justify-end gap-3"><a href="{{ route('admin.experiences.index') }}" class="btn-ghost">Cancel</a><button class="btn-primary">Save</button></div>
    </form>
</x-layouts.admin>
