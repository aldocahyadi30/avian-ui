{{--
    An `icon-only` button renders as a square button with no visible text —
    pass `label` so it still gets an accessible name (there is no visible
    text for assistive tech to read otherwise):

        <x-avian::button icon="fas fa-pen" icon-only label="Edit" />

    `navigate` adds `wire:navigate` to an `href` button for Livewire's
    SPA-style page swap. It is opt-in rather than automatic whenever `href`
    is set — an external link, a `mailto:`/`tel:` link or an on-page `#anchor`
    would break under `wire:navigate`, so only ask for it on same-app links:

        <x-avian::button href="{{ route('dashboard') }}" navigate>Dashboard</x-avian::button>

    `outline` and `ghost` are shapes, not colors on their own — pair either
    with `color` (`primary`, `secondary`, `success`, `warning`, `danger`,
    `info`) to pick one. Without `color` they fall back to `primary`
    (outline) or `secondary` (ghost); `color` is ignored on every other
    variant, which is already a color (`primary`, `success`, ...):

        <x-avian::button variant="outline" color="danger">Remove</x-avian::button>
        <x-avian::button variant="ghost" color="success">Approve</x-avian::button>

    `confirm` holds the click back behind `<x-avian::confirm>` (placed once
    in the layout) and replays it once the user agrees, so `wire:click`,
    `href` and form submits behave as usual after a yes. Tune the dialog with
    `data-aui-confirm-title`, `data-aui-confirm-text` (the yes button),
    `data-aui-cancel-text` and `data-aui-confirm-variant`:

        <x-avian::button variant="danger" wire:click="delete({{ $id }})" confirm="Delete this order?">Delete</x-avian::button>
--}}
@props([
    'variant' => 'primary',
    'color' => null,
    'size' => null,
    'type' => 'button',
    'href' => null,
    'navigate' => false,
    'icon' => null,
    'iconRight' => null,
    'iconOnly' => false,
    'label' => null,
    'loading' => false,
    'block' => false,
    'disabled' => false,
    'modal' => null,
    'confirm' => null,
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

    // `color` only applies to the "shape" variants — every other variant is
    // already a color of its own (primary, success, light, link, ...).
    $resolvedVariant = in_array($variant, ['outline', 'ghost'], true) && filled($color)
        ? $variant.'-'.$color
        : $variant;

    $classes = [
        'aui-btn',
        'aui-btn-'.$resolvedVariant,
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
        'wire:navigate' => $tag === 'a' && $navigate,
        'disabled' => $tag === 'button' && ($disabled || $loading),
        'aria-disabled' => $tag === 'a' && ($disabled || $loading) ? 'true' : null,
        'aria-label' => $iconOnly ? $label : null,
        'x-data' => $opens === null ? null : '{}',
        'x-on:click' => $opens,
        'data-aui-confirm' => filled($confirm) ? $confirm : null,
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
