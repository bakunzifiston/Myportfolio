@props(['project', 'headingLevel' => 'h3', 'showFeatured' => true])
<article {{ $attributes->merge(['class' => 'group card relative flex flex-col overflow-hidden transition duration-300 hover:-translate-y-1 hover:border-zinc-300 hover:shadow-[0_20px_40px_-24px_rgb(0_0_0/0.25)] dark:hover:border-zinc-700 dark:hover:shadow-[0_20px_40px_-24px_rgb(0_0_0/0.9)]']) }}
         data-project-category="{{ $project->category?->slug ?? 'other' }}" data-tilt>
    <div class="aspect-[16/10]">
        <x-site.project-cover :project="$project" />
    </div>
    <div class="flex flex-1 flex-col border-t border-zinc-200/70 p-6 dark:border-zinc-800/80">
        <div class="flex items-center gap-2 text-xs">
            <span class="font-medium text-accent-700 dark:text-accent-400">{{ $project->category?->name ?? 'Project' }}</span>
            @if ($showFeatured && $project->is_featured)
                <span class="inline-flex items-center gap-1 text-zinc-500 dark:text-zinc-400"><span aria-hidden="true">·</span> Featured</span>
            @endif
        </div>
        <{{ $headingLevel }} class="mt-2 text-lg font-semibold tracking-tight">
            <a href="{{ route('projects.show', $project) }}" class="after:absolute after:inset-0 after:rounded-2xl focus:outline-none focus-visible:after:outline-2 focus-visible:after:outline-offset-2 focus-visible:after:outline-accent-600">
                {{ $project->title }}
            </a>
        </{{ $headingLevel }}>
        @if ($project->short_description)
            <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-zinc-600 dark:text-zinc-400">{{ $project->short_description }}</p>
        @else
            <p class="mt-2 text-sm leading-relaxed text-zinc-500 dark:text-zinc-500">Project summary coming soon.</p>
        @endif
        @if (filled($project->technologies))
            <ul class="mt-4 flex flex-wrap gap-1.5" aria-label="Technologies">
                @foreach (array_slice($project->technologies, 0, 4) as $tech)
                    <li class="tag">{{ $tech }}</li>
                @endforeach
                @if (count($project->technologies) > 4)
                    <li class="tag">+{{ count($project->technologies) - 4 }}</li>
                @endif
            </ul>
        @endif
        <span class="mt-auto inline-flex items-center gap-1.5 pt-5 text-sm font-medium text-zinc-500 transition group-hover:text-zinc-950 dark:text-zinc-400 dark:group-hover:text-white" aria-hidden="true">
            View project <i data-lucide="arrow-right" class="size-4 transition group-hover:translate-x-0.5"></i>
        </span>
    </div>
</article>
