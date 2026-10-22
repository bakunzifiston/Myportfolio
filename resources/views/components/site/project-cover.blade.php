{{--
    Consistent project preview: a browser window resting in a soft stage. Shows the uploaded thumbnail
    when there is one; otherwise a neutral generated layout (never presented as a real screenshot).
--}}
@props(['project', 'size' => 'card'])
@php
    $url = $project->thumbnailUrl();
    $hero = $size === 'hero';
    $sizes = $hero ? '(min-width: 1024px) 1100px, 100vw' : '(min-width: 1024px) 560px, (min-width: 640px) 50vw, 100vw';
@endphp
<div {{ $attributes->merge(['class' => 'preview-stage relative h-full w-full overflow-hidden']) }} style="--hue: {{ $project->coverHue() }}">
    <div data-tilt-target @class([
        'preview-frame absolute inset-x-[7%] top-[12%] -bottom-3 flex flex-col overflow-hidden rounded-t-xl border border-b-0 transition-transform duration-500 ease-out',
        'inset-x-[6%] top-[10%]' => $hero,
    ])>
        <div class="flex h-7 shrink-0 items-center gap-1.5 border-b px-3 preview-bar">
            <span class="size-2 rounded-full bg-current opacity-25"></span>
            <span class="size-2 rounded-full bg-current opacity-25"></span>
            <span class="size-2 rounded-full bg-current opacity-25"></span>
            <span class="ml-2 truncate rounded-md px-2 py-0.5 font-mono text-[10px] preview-url">/projects/{{ $project->slug }}</span>
        </div>

        @if ($url)
            <img src="{{ $url }}" alt="{{ $project->thumbnail_alt ?: 'Screenshot of '.$project->title }}"
                 class="min-h-0 w-full flex-1 object-cover object-top" sizes="{{ $sizes }}"
                 loading="{{ $hero ? 'eager' : 'lazy' }}" decoding="async">
        @else
            <div class="relative flex flex-1 flex-col p-[6%]" role="img" aria-label="{{ $project->title }} project preview">
                <div class="flex items-center gap-3">
                    <span @class(['preview-mark grid shrink-0 place-items-center rounded-xl font-mono font-semibold tracking-tight', 'size-14 text-lg sm:size-16 sm:text-xl' => $hero, 'size-10 text-sm' => ! $hero])>{{ $project->initials() }}</span>
                    <span class="min-w-0">
                        <span @class(['block truncate font-semibold tracking-tight preview-title', 'text-xl sm:text-2xl' => $hero, 'text-sm' => ! $hero])>{{ $project->title }}</span>
                        <span class="mt-0.5 block truncate text-[11px] preview-muted">{{ $project->category?->name ?? 'Project' }}</span>
                    </span>
                </div>
                <div class="mt-[6%] grid flex-1 grid-cols-3 gap-[4%]">
                    <span class="preview-block col-span-2 rounded-lg"></span>
                    <span class="preview-block rounded-lg"></span>
                    <span class="preview-block rounded-lg"></span>
                    <span class="preview-block col-span-2 rounded-lg"></span>
                </div>
            </div>
        @endif
    </div>
</div>
