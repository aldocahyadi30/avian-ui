{{--
    One section of `<x-avian::accordion>`. The header is `title` (plus an
    optional `subtitle` and `icon`), or a `title` slot for custom markup.

        <x-avian::accordion.item title="Payment" subtitle="Card ending 4242" icon="fas fa-credit-card">
            ...
        </x-avian::accordion.item>

    `name` is only needed to open an item from outside, through the parent's
    `expand('payment')` / `collapse('payment')`; otherwise Alpine's `$id`
    gives each item its own key. A closed item is hidden inline on first
    paint so it does not flash open before Alpine starts.
--}}
@props([
    'title' => null,
    'subtitle' => null,
    'icon' => null,
    'name' => null,
    'open' => false,
])

<div
    x-data="{ key: @js($name) ?? $id('aui-accordion') }"
    x-init="if (@js((bool) $open)) expand(key)"
    x-bind:class="{ 'is-open': isOpen(key) }"
    {{ $attributes->class(['aui-accordion-item', 'is-open' => $open]) }}
>
    <h3 class="aui-accordion-heading">
        <button
            type="button"
            class="aui-accordion-trigger"
            x-on:click="toggle(key)"
            x-bind:id="key + '-trigger'"
            x-bind:aria-controls="key + '-panel'"
            x-bind:aria-expanded="isOpen(key)"
            aria-expanded="{{ $open ? 'true' : 'false' }}"
        >
            @if (filled($icon))
                <i class="aui-accordion-icon {{ $icon }}" aria-hidden="true"></i>
            @endif

            <span class="aui-accordion-label">
                <span class="aui-accordion-title">{{ $title }}</span>

                @if (filled($subtitle))
                    <span class="aui-accordion-subtitle">{{ $subtitle }}</span>
                @endif
            </span>

            <i class="fas fa-chevron-down aui-accordion-arrow" aria-hidden="true"></i>
        </button>
    </h3>

    <div
        class="aui-accordion-panel"
        role="region"
        x-show="isOpen(key)"
        x-bind:id="key + '-panel'"
        x-bind:aria-labelledby="key + '-trigger'"
        @unless ($open) style="display: none" @endunless
    >
        <div class="aui-accordion-body">{{ $slot }}</div>
    </div>
</div>
