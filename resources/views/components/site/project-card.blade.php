@props(['project', 'headingLevel' => 'h3'])
<article {{ $attributes->merge(['class' => 'group card relative flex flex-col overflow-hidden transition duration-300 hover:-translate-y-0.5 hover:border-zinc-300 hover:shadow-[0_12px_32px_-16px_rgb(0_0_0/0.18)] dark:hover:border-zinc-700']) }}
         data-project-category="{{ $project->category?->slug ?? 'other' }}">
    <div class="aspect-[16/10] overflow-hidden border-b border-zinc-200/80 dark:border-zinc-800">
        <x-site.project-cover :project="$project" class="transition duration-500 group-hover:scale-[1.02]" />
    </div>
    <div class="flex flex-1 flex-col p-6">
        <div class="flex flex-wrap items-center gap-2 text-xs">
            <span class="font-medium text-accent-700 dark:text-accent-400">{{ $project->category?->name ?? 'Project' }}</span>
            @if ($project->is_featured)
                <span class="text-zinc-300 dark:text-zinc-600" aria-hidden="true">•</span>
                <span class="inline-flex items-center gap-1 text-zinc-500 dark:text-zinc-400"><i data-lucide="star" class="size-3"></i> Featured</span>
            @endif
        </div>
        <{{ $headingLevel }} class="mt-2 text-lg font-semibold tracking-tight">
            <a href="{{ route('projects.show', $project) }}" class="after:absolute after:inset-0 focus:outline-none focus-visible:after:rounded-2xl focus-visible:after:outline-2 focus-visible:after:outline-accent-600">
                {{ $project->title }}
            </a>
        </{{ $headingLevel }}>
        @if ($project->short_description)
            <p class="mt-2 line-clamp-3 text-sm leading-relaxed text-zinc-600 dark:text-zinc-400">{{ $project->short_description }}</p>
        @else
            <p class="mt-2 text-sm leading-relaxed text-zinc-500 italic dark:text-zinc-500">Project summary coming soon.</p>
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
        <span class="mt-auto inline-flex items-center gap-1.5 pt-5 text-sm font-medium text-zinc-900 dark:text-zinc-100" aria-hidden="true">
            View project <i data-lucide="arrow-right" class="size-4 transition group-hover:translate-x-0.5"></i>
        </span>
    </div>
</article>
