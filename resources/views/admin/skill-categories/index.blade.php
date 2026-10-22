<x-layouts.admin title="Skills">
    <x-admin.header title="Skills" description="Skills are grouped into categories and shown as labels, without proficiency ratings.">
        <a href="{{ route('admin.skill-categories.create') }}" class="btn-primary"><i data-lucide="plus"></i> New category</a>
    </x-admin.header>
    <div class="grid gap-4 md:grid-cols-2">
        @forelse ($categories as $category)
            <div class="card p-5">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <span class="grid size-9 place-items-center rounded-lg bg-accent-50 text-accent-700 dark:bg-accent-950/50 dark:text-accent-300"><i data-lucide="{{ $category->icon ?: 'layers' }}"></i></span>
                        <h2 class="font-semibold">{{ $category->name }}</h2>
                    </div>
                    <x-admin.row-actions :edit="route('admin.skill-categories.edit', $category)" :destroy="route('admin.skill-categories.destroy', $category)" confirm="Delete this category and all of its skills?" />
                </div>
                <ul class="mt-4 flex flex-wrap gap-1.5">
                    @foreach ($category->skills as $skill)<li class="tag">{{ $skill->name }}</li>@endforeach
                </ul>
            </div>
        @empty
            <p class="text-zinc-500">No skill categories yet.</p>
        @endforelse
    </div>
</x-layouts.admin>
