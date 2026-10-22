@props(['eyebrow', 'title', 'id' => null])
<div {{ $attributes->merge(['class' => 'reveal']) }}>
    <p class="eyebrow">{{ $eyebrow }}</p>
    <h2 @if ($id) id="{{ $id }}" @endif class="section-title">{{ $title }}</h2>
    @if ($slot->isNotEmpty())
        <p class="section-lead">{{ $slot }}</p>
    @endif
</div>
