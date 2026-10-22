@php
    $tiles = $featuredProjects->values();
    $count = $tiles->count();

    // Bento layout: the lead tile spans two columns; the last tile widens if needed so every row is full.
    $spans = $tiles->map(function ($project, $i) use ($count) {
        $classes = [];
        if ($i === 0) {
            $classes[] = $count === 1 ? 'md:col-span-2 lg:col-span-3' : 'md:col-span-2';
        } elseif ($i === $count - 1) {
            if (($count - 1) % 2 === 1) {
                $classes[] = 'md:col-span-2';
            }
            $remainder = ($count - 2) % 3;
            if ($count > 2 && $remainder === 1) {
                $classes[] = 'lg:col-span-3';
            } elseif ($count > 2 && $remainder === 2) {
                $classes[] = 'lg:col-span-2';
            } elseif (($count - 1) % 2 === 1) {
                $classes[] = 'lg:col-span-1';
            }
        }

        return implode(' ', $classes);
    });
@endphp
<section id="projects" class="section bg-zinc-50/70 dark:bg-zinc-900/25" aria-labelledby="projects-heading">
    <div class="container-page">
        <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
            <x-site.section-heading eyebrow="Selected work" title="Featured projects" id="projects-heading">
                Initiatives and platforms I have contributed to, spanning language technology, digital transformation and digital learning.
            </x-site.section-heading>
            @if ($projectCount > $count)
                <a href="{{ route('projects.index') }}" class="btn-secondary reveal shrink-0">All {{ $projectCount }} projects <i data-lucide="arrow-right"></i></a>
            @endif
        </div>

        @if ($count)
            <div class="mt-14 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($tiles as $i => $project)
                    <x-site.project-tile :project="$project" :number="$i + 1" :lead="$i === 0" @class(['reveal', $spans[$i]]) />
                @endforeach
            </div>
        @else
            <p class="mt-12 text-zinc-600 dark:text-zinc-400">Project case studies will be published here soon.</p>
        @endif
    </div>
</section>
