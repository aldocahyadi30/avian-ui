@props([
    'for' => null,
    'required' => false,
])

<label {{ $attributes->class(['aui-label', 'aui-label-required' => $required])->merge(['for' => $for]) }}>{{ $slot }}</label>
