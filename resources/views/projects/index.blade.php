<x-layouts.site title="Projects" description="Projects by {{ $settings['full_name'] ?? 'Bakunzi Bake Fiston' }} in digital transformation, information systems, e-learning and online platforms." active="projects">
    <section class="relative overflow-hidden border-b border-zinc-200/80 dark:border-zinc-800">
        <div class="bg-grid pointer-events-none absolute inset-0 [mask-image:radial-gradient(ellipse_60%_80%_at_70%_0%,black,transparent)]" aria-hidden="true"></div>
        <div class="container-page relative py-16 sm:py-20">
            <nav aria-label="Breadcrumb" class="text-sm text-zinc-500 dark:text-zinc-400">
                <a href="{{ route('home') }}" class="hover:text-zinc-900 dark:hover:text-white">Home</a>
                <span class="mx-2" aria-hidden="true">/</span>
                <span aria-current="page" class="text-zinc-900 dark:text-white">Projects</span>
            </nav>
            <h1 class="mt-6 text-4xl font-semibold tracking-tight sm:text-5xl">Projects</h1>
            <p class="mt-4 max-w-2xl text-lg text-zinc-600 dark:text-zinc-400">
                A portfolio of initiatives and platforms I have contributed to, from digital transformation programmes to digital learning.
            </p>
        </div>
    </section>

    <section class="container-page py-12 sm:py-16" aria-label="Project gallery">
        @if ($categories->isNotEmpty())
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div data-project-filters class="-mx-1 flex flex-wrap gap-2" role="group" aria-label="Filter projects by category">
                    @php
                        $filters = collect([['slug' => 'all', 'name' => 'All']])
                            ->concat($categories->map(fn ($c) => ['slug' => $c->slug, 'name' => $c->name]))
                            ->when($hasUncategorised, fn ($f) => $f->push(['slug' => 'other', 'name' => 'Other']));
                    @endphp
                    @foreach ($filters as $filter)
                        <a href="{{ $filter['slug'] === 'all' ? route('projects.index') : route('projects.index', ['category' => $filter['slug']]) }}"
                           data-filter="{{ $filter['slug'] }}" role="button"
                           aria-pressed="{{ $activeCategory === $filter['slug'] ? 'true' : 'false' }}"
                           class="rounded-full border border-zinc-200 px-4 py-1.5 text-sm font-medium text-zinc-700 transition hover:border-zinc-300 hover:text-zinc-950 aria-pressed:border-zinc-950 aria-pressed:bg-zinc-950 aria-pressed:text-white dark:border-zinc-800 dark:text-zinc-300 dark:hover:border-zinc-700 dark:hover:text-white dark:aria-pressed:border-white dark:aria-pressed:bg-white dark:aria-pressed:text-zinc-950">
                            {{ $filter['name'] }}
                        </a>
                    @endforeach
                </div>
                <p class="text-sm text-zinc-500 dark:text-zinc-400" data-projects-count aria-live="polite">{{ $projects->count() }} {{ \Illuminate\Support\Str::plural('project', $projects->count()) }}</p>
            </div>
        @endif

        <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($projects as $project)
                @php $slug = $project->category?->slug ?? 'other'; @endphp
                <x-site.project-card :project="$project" headingLevel="h2" :hidden="$activeCategory !== 'all' && $activeCategory !== $slug" />
            @endforeach
        </div>

        <p data-projects-empty @class(['py-16 text-center text-zinc-500 dark:text-zinc-400']) @if ($projects->isNotEmpty() && ($activeCategory === 'all' || $projects->contains(fn ($p) => ($p->category?->slug ?? 'other') === $activeCategory))) hidden @endif>
            No projects in this category yet.
        </p>
    </section>
</x-layouts.site>
