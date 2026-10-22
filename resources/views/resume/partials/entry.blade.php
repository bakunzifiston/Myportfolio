<div class="print-avoid-break">
    <div class="flex flex-col gap-0.5 sm:flex-row sm:items-baseline sm:justify-between sm:gap-6">
        <p class="font-semibold text-zinc-900 dark:text-zinc-100 print:text-black">
            {{ $item->heading() }}@if ($item->position && $item->organization)<span class="font-normal text-zinc-600 dark:text-zinc-400"> · {{ $item->organization }}</span>@endif
        </p>
        @if ($item->period())
            <p class="shrink-0 text-sm text-zinc-500">{{ $item->period() }}</p>
        @endif
    </div>
    @php $meta = collect([$item->employment_type, $item->location, $item->project?->title])->filter()->implode(' · '); @endphp
    @if ($meta)
        <p class="text-sm text-zinc-500">{{ $meta }}</p>
    @endif
    @if ($item->summary)
        <p class="mt-2 text-sm text-zinc-700 dark:text-zinc-300 print:text-black">{{ $item->summary }}</p>
    @endif
    @php $bullets = array_merge($item->responsibilities ?? [], $item->contributions ?? []); @endphp
    @if ($bullets)
        <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-zinc-700 marker:text-zinc-400 dark:text-zinc-300 print:text-black">
            @foreach ($bullets as $line)
                <li>{{ $line }}</li>
            @endforeach
        </ul>
    @endif
    @if (filled($item->technologies))
        <p class="mt-1.5 text-sm text-zinc-500">Technologies: {{ implode(', ', $item->technologies) }}</p>
    @endif
</div>
