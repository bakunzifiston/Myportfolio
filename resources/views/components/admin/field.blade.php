{{--
    Generic admin form field. type: text|email|url|tel|number|date|textarea|select|checkbox|file|lines
    "lines" edits an array as one item per line.
--}}
@props([
    'name',
    'label',
    'type' => 'text',
    'value' => null,
    'help' => null,
    'options' => [],
    'rows' => 4,
    'required' => false,
    'placeholder' => null,
    'accept' => null,
])
@php
    $id = 'field-'.str_replace(['[', ']', '.'], '-', $name);
    $errorKey = rtrim(str_replace(['[', ']'], ['.', ''], $name), '.');
    $current = old($name, $type === 'lines' && is_array($value) ? implode("\n", $value) : $value);
    $hasError = $errors->has($errorKey) || $errors->has($errorKey.'.*');
    $describedBy = trim(($help ? $id.'-help ' : '').($hasError ? $id.'-error' : ''));
@endphp
<div {{ $attributes->except('multiple') }}>
    @if ($type === 'checkbox')
        <input type="hidden" name="{{ $name }}" value="0">
        <label for="{{ $id }}" class="flex items-start gap-3 text-sm">
            <input id="{{ $id }}" type="checkbox" name="{{ $name }}" value="1" @checked($current)
                   class="mt-0.5 size-4 rounded border-zinc-300 text-accent-700 accent-accent-700 focus:ring-accent-600"
                   @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif>
            <span>
                <span class="font-medium text-zinc-900 dark:text-zinc-100">{{ $label }}</span>
                @if ($help)<span id="{{ $id }}-help" class="mt-0.5 block text-zinc-500 dark:text-zinc-400">{{ $help }}</span>@endif
            </span>
        </label>
    @else
        <label for="{{ $id }}" class="form-label">{{ $label }} @if ($required)<span class="text-red-600" aria-hidden="true">*</span>@endif</label>
        @if ($type === 'textarea' || $type === 'lines')
            <textarea id="{{ $id }}" name="{{ $name }}" rows="{{ $rows }}" class="form-input" @required($required) placeholder="{{ $placeholder }}"
                      @if ($hasError) aria-invalid="true" @endif @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif>{{ $current }}</textarea>
        @elseif ($type === 'select')
            <select id="{{ $id }}" name="{{ $name }}" class="form-input" @required($required)
                    @if ($hasError) aria-invalid="true" @endif @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif>
                @foreach ($options as $optionValue => $optionLabel)
                    <option value="{{ $optionValue }}" @selected((string) $current === (string) $optionValue)>{{ $optionLabel }}</option>
                @endforeach
            </select>
        @elseif ($type === 'file')
            <input id="{{ $id }}" type="file" name="{{ $name }}" @if ($accept) accept="{{ $accept }}" @endif {{ $attributes->only('multiple') }}
                   class="block w-full text-sm text-zinc-600 file:mr-4 file:rounded-lg file:border-0 file:bg-zinc-100 file:px-4 file:py-2 file:text-sm file:font-medium file:text-zinc-900 hover:file:bg-zinc-200 dark:text-zinc-400 dark:file:bg-zinc-800 dark:file:text-zinc-100"
                   @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif>
        @else
            <input id="{{ $id }}" type="{{ $type }}" name="{{ $name }}" value="{{ $current }}" class="form-input" @required($required) placeholder="{{ $placeholder }}"
                   @if ($hasError) aria-invalid="true" @endif @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif>
        @endif
        @if ($help)
            <p id="{{ $id }}-help" class="mt-1.5 text-xs text-zinc-500 dark:text-zinc-400">{{ $help }}</p>
        @endif
    @endif
    @if ($hasError)
        <p id="{{ $id }}-error" class="form-error">{{ $errors->first($errorKey) ?: $errors->first($errorKey.'.*') }}</p>
    @endif
</div>
