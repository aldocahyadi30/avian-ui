@props([
    'href' => null,
    'icon' => null,
    'danger' => false,
    'type' => 'button',
])

@php
    $tag = filled($href) ? 'a' : 'button';
@endphp

<{{ $tag }}
    {{ $attributes->class(['aui-dropdown-item', 'aui-dropdown-item-danger' => $danger])->merge([
        'type' => $tag === 'button' ? $type : null,
        'href' => $href,
    ]) }}
>
    @if (filled($icon))
        <i class="{{ $icon }}" aria-hidden="true"></i>
    @endif

    {{ $slot }}
</{{ $tag }}>
