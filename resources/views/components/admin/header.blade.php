@props(['title', 'description' => null])
<div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
        <h1 class="text-2xl font-semibold tracking-tight">{{ $title }}</h1>
        @if ($description)
            <p class="mt-1 max-w-2xl text-sm text-zinc-600 dark:text-zinc-400">{{ $description }}</p>
        @endif
    </div>
    @if ($slot->isNotEmpty())
        <div class="flex shrink-0 gap-2">{{ $slot }}</div>
    @endif
</div>
