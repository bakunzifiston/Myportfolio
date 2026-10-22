{{-- Bento tile for the home page's featured projects. The lead tile is wider and tells more of the story. --}}
@props(['project', 'number', 'lead' => false])
@php $image = $project->thumbnailUrl(); @endphp
<article {{ $attributes->merge(['class' => 'tile group relative flex flex-col overflow-hidden rounded-3xl border border-zinc-200/80 bg-white p-7 transition duration-300 hover:-translate-y-0.5 hover:border-zinc-300 sm:p-8 dark:border-zinc-800/80 dark:bg-zinc-900/50 dark:hover:border-zinc-700']) }}
         style="--hue: {{ $project->coverHue() }}" data-spotlight>
    <div class="tile-glow pointer-events-none absolute inset-0" aria-hidden="true"></div>

    <div class="relative flex items-start justify-between gap-6">
        <p class="flex items-center gap-3 text-xs">
            <span class="font-mono text-zinc-400 dark:text-zinc-500">{{ str_pad($number, 2, '0', STR_PAD_LEFT) }}</span>
            <span class="font-medium tracking-wide text-accent-700 uppercase dark:text-accent-400">{{ $project->category?->name ?? 'Project' }}</span>
        </p>
        @if ($image && ! $lead)
            <img src="{{ $image }}" alt="" class="size-14 shrink-0 rounded-2xl object-cover ring-1 ring-zinc-200 dark:ring-zinc-800" loading="lazy" decoding="async">
        @else
            <span @class(['preview-mark grid shrink-0 place-items-center rounded-2xl font-mono font-semibold tracking-tight', 'size-16 text-xl' => $lead, 'size-12 text-base' => ! $lead]) aria-hidden="true">{{ $project->initials() }}</span>
        @endif
    </div>

    <div @class(['relative mt-auto', 'grid gap-8 pt-10 lg:grid-cols-[1.1fr_1fr] lg:items-end' => $lead, 'pt-10' => ! $lead])>
        <div>
            <h3 @class(['font-semibold tracking-tight', 'text-3xl sm:text-4xl' => $lead, 'text-xl' => ! $lead])>
                <a href="{{ route('projects.show', $project) }}" class="after:absolute after:inset-0 after:rounded-3xl focus:outline-none focus-visible:after:outline-2 focus-visible:after:outline-offset-2 focus-visible:after:outline-accent-600">{{ $project->title }}</a>
            </h3>
            @if ($project->short_description)
                <p @class(['mt-3 leading-relaxed text-zinc-600 dark:text-zinc-400', 'text-base sm:text-lg' => $lead, 'line-clamp-3 text-sm' => ! $lead])>{{ $project->short_description }}</p>
            @else
                <p class="mt-3 text-sm text-zinc-500">Project summary coming soon.</p>
            @endif
            @if (filled($project->technologies))
                <ul class="mt-5 flex flex-wrap gap-1.5" aria-label="Technologies">
                    @foreach (array_slice($project->technologies, 0, $lead ? 6 : 3) as $tech)<li class="tag">{{ $tech }}</li>@endforeach
                </ul>
            @endif
        </div>

        @if ($lead)
            @if ($image)
                <img src="{{ $image }}" alt="{{ $project->thumbnail_alt ?: 'Screenshot of '.$project->title }}" class="aspect-[16/10] w-full rounded-2xl object-cover object-top ring-1 ring-zinc-200 dark:ring-zinc-800" loading="lazy" decoding="async">
            @elseif (filled($project->key_features))
                <ul class="grid gap-3 border-l border-zinc-200 pl-6 text-sm text-zinc-700 dark:border-zinc-800 dark:text-zinc-300">
                    @foreach (array_slice($project->key_features, 0, 4) as $feature)
                        <li class="flex gap-2.5"><i data-lucide="check" class="mt-0.5 size-4 text-accent-700 dark:text-accent-400"></i>{{ $feature }}</li>
                    @endforeach
                </ul>
            @endif
        @endif
    </div>

    <div class="relative mt-7 flex items-center justify-between border-t border-zinc-200/70 pt-5 text-sm dark:border-zinc-800/80">
        <span class="font-medium text-zinc-600 transition group-hover:text-zinc-950 dark:text-zinc-400 dark:group-hover:text-white">{{ $lead ? 'Read the case study' : 'View project' }}</span>
        <span class="grid size-9 place-items-center rounded-full border border-zinc-200 text-zinc-500 transition duration-300 group-hover:border-accent-600 group-hover:bg-accent-600 group-hover:text-white dark:border-zinc-700 dark:text-zinc-400 dark:group-hover:border-accent-400 dark:group-hover:bg-accent-400 dark:group-hover:text-zinc-950" aria-hidden="true">
            <i data-lucide="arrow-up-right" class="size-4 transition duration-300 group-hover:rotate-45"></i>
        </span>
    </div>
</article>
