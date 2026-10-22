<section id="projects" class="section border-t border-zinc-200/80 bg-zinc-50/70 dark:border-zinc-800 dark:bg-zinc-900/30" aria-labelledby="projects-heading">
    <div class="container-page">
        <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
            <x-site.section-heading eyebrow="Selected work" title="Featured projects" id="projects-heading">
                Initiatives and platforms I have contributed to, spanning digital transformation, information systems and digital learning.
            </x-site.section-heading>
            @if ($projectCount > $featuredProjects->count())
                <a href="{{ route('projects.index') }}" class="btn-secondary shrink-0">All {{ $projectCount }} projects <i data-lucide="arrow-right"></i></a>
            @endif
        </div>

        @if ($featuredProjects->isNotEmpty())
            <div @class([
                'mt-12 grid gap-6 sm:grid-cols-2',
                // Two columns avoid a lone card on the last row when there are 2 or 4 featured projects.
                'lg:grid-cols-3' => ! in_array($featuredProjects->count(), [2, 4], true),
            ])>
                @foreach ($featuredProjects as $project)
                    <x-site.project-card :project="$project" class="reveal" />
                @endforeach
            </div>
        @else
            <p class="mt-12 text-zinc-600 dark:text-zinc-400">Project case studies will be published here soon.</p>
        @endif
    </div>
</section>
