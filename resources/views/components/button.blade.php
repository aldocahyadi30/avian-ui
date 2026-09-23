{{--
    An `icon-only` button renders as a square button with no visible text —
    pass `label` so it still gets an accessible name (there is no visible
    text for assistive tech to read otherwise):

        <x-avian::button icon="fas fa-pen" icon-only label="Edit" />
--}}
@props([
    'variant' => 'primary',
    'size' => null,
    'type' => 'button',
    'href' => null,
    'icon' => null,
    'iconRight' => null,
    'iconOnly' => false,
    'label' => null,
    'loading' => false,
    'block' => false,
    'disabled' => false,
    'modal' => null,
])

@php
    $tag = filled($href) ? 'a' : 'button';

    /*
     * Alpine only initialises trees rooted at x-data, so a trigger that lives
     * outside every component needs its own empty scope before $dispatch works.
     */
    $opens = filled($modal)
        ? '$dispatch(\'aui-modal-open\', { name: '.Illuminate\Support\Js::from($modal).' })'
        : null;

    $classes = [
        'aui-btn',
        'aui-btn-'.$variant,
        'aui-btn-'.$size => filled($size),
        'aui-btn-icon' => $iconOnly,
        'aui-btn-block' => $block,
        'aui-btn-loading' => $loading,
    ];
@endphp

<{{ $tag }}
    {{ $attributes->class($classes)->merge([
        'type' => $tag === 'button' ? $type : null,
        'href' => $href,
        'disabled' => $tag === 'button' && ($disabled || $loading),
        'aria-disabled' => $tag === 'a' && ($disabled || $loading) ? 'true' : null,
        'aria-label' => $iconOnly ? $label : null,
        'x-data' => $opens === null ? null : '{}',
        'x-on:click' => $opens,
    ]) }}
>
    @if ($loading)
        <span class="aui-spinner aui-spinner-sm" aria-hidden="true"></span>
    @elseif (filled($icon))
        <i class="{{ $icon }}" aria-hidden="true"></i>
    @endif

    @unless ($iconOnly)
        {{ $slot }}

        @if (filled($iconRight))
            <i class="{{ $iconRight }}" aria-hidden="true"></i>
        @endif
    @endunless
</{{ $tag }}>
