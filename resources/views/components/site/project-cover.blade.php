@props(['project', 'size' => 'card'])
@php
    $url = $project->thumbnailUrl();
    $sizes = $size === 'hero' ? '(min-width: 1024px) 1100px, 100vw' : '(min-width: 1024px) 360px, (min-width: 640px) 50vw, 100vw';
@endphp
@if ($url)
    <img src="{{ $url }}" alt="{{ $project->thumbnail_alt ?: 'Screenshot of '.$project->title }}"
         {{ $attributes->merge(['class' => 'h-full w-full object-cover']) }}
         sizes="{{ $sizes }}" loading="{{ $size === 'hero' ? 'eager' : 'lazy' }}" decoding="async">
@else
    {{-- Generated cover: a neutral placeholder, not a screenshot of the project. --}}
    <div {{ $attributes->merge(['class' => 'project-cover relative flex h-full w-full items-center justify-center overflow-hidden']) }}
         style="--hue: {{ $project->coverHue() }}" role="img" aria-label="{{ $project->title }} project cover">
        <span class="font-mono font-semibold tracking-tight {{ $size === 'hero' ? 'text-6xl sm:text-7xl' : 'text-4xl' }}">{{ $project->initials() }}</span>
        <span class="absolute bottom-3 left-4 font-mono text-[11px] tracking-wide opacity-70">/{{ $project->slug }}</span>
    </div>
@endif
