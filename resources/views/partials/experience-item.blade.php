<li class="reveal card relative p-6">
    <div class="flex flex-col gap-1 sm:flex-row sm:items-start sm:justify-between sm:gap-4">
        <div>
            <h4 class="font-semibold text-zinc-950 dark:text-white">{{ $item->heading() }}</h4>
            @php
                $orgLine = collect([
                    $item->position ? $item->organization : null,
                    $item->employment_type,
                    $item->location,
                ])->filter()->implode(' · ');
            @endphp
            @if ($orgLine)
                <p class="mt-0.5 text-sm text-zinc-600 dark:text-zinc-400">{{ $orgLine }}</p>
            @endif
            @if ($item->project && $item->project->isPublished())
                <a href="{{ route('projects.show', $item->project) }}" class="link-arrow mt-1">{{ $item->project->title }} <i data-lucide="arrow-up-right" class="size-3.5"></i></a>
            @endif
        </div>
        @if ($item->period())
            <p class="shrink-0 font-mono text-xs text-zinc-500 dark:text-zinc-400">{{ $item->period() }}</p>
        @endif
    </div>

    @if ($item->summary)
        <p class="mt-4 text-sm leading-relaxed text-zinc-700 dark:text-zinc-300">{{ $item->summary }}</p>
    @endif

    @foreach (['responsibilities' => 'Responsibilities', 'contributions' => 'Key contributions'] as $field => $label)
        @if (filled($item->$field))
            <p class="mt-4 text-xs font-semibold tracking-wide text-zinc-500 uppercase dark:text-zinc-400">{{ $label }}</p>
            <ul class="mt-2 space-y-1.5 text-sm text-zinc-700 dark:text-zinc-300">
                @foreach ($item->$field as $line)
                    <li class="flex gap-2.5"><span class="mt-2 size-1 shrink-0 rounded-full bg-accent-600 dark:bg-accent-400"></span>{{ $line }}</li>
                @endforeach
            </ul>
        @endif
    @endforeach

    @if (filled($item->technologies))
        <ul class="mt-4 flex flex-wrap gap-1.5" aria-label="Technologies">
            @foreach ($item->technologies as $tech)
                <li class="tag">{{ $tech }}</li>
            @endforeach
        </ul>
    @endif
</li>
