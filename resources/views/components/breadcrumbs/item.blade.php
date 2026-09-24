{{--
    One step of `<x-avian::breadcrumbs>`. With `href` it is a link; without
    one it is the current page. The separator between steps is drawn in CSS.
--}}
@props([
    'href' => null,
    'icon' => null,
    'navigate' => false,
])

<li {{ $attributes->class(['aui-breadcrumbs-item', 'is-current' => blank($href)]) }}>
    @if (filled($href))
        <a href="{{ $href }}" class="aui-breadcrumbs-link" @if ($navigate) wire:navigate @endif>
            @if (filled($icon))
                <i class="{{ $icon }}" aria-hidden="true"></i>
            @endif
            {{ $slot }}
        </a>
    @else
        <span class="aui-breadcrumbs-current" aria-current="page">
            @if (filled($icon))
                <i class="{{ $icon }}" aria-hidden="true"></i>
            @endif
            {{ $slot }}
        </span>
    @endif
</li>
