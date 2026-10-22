@props(['on', 'onLabel' => 'Published', 'offLabel' => 'Hidden'])
@if ($on)
    <span class="badge bg-emerald-50 text-emerald-800 ring-1 ring-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:ring-emerald-900">{{ $onLabel }}</span>
@else
    <span class="badge bg-zinc-100 text-zinc-600 ring-1 ring-zinc-200 dark:bg-zinc-800 dark:text-zinc-400 dark:ring-zinc-700">{{ $offLabel }}</span>
@endif
