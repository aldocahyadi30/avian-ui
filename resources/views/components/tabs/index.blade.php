{{--
    `variant` styles the tab list itself: omit it (or pass `line`) for the
    default underlined tabs, `pill` for standalone rounded buttons, or
    `segmented` for a grouped segmented-control look.
--}}
@props([
    'tabs' => [],
    'active' => null,
    'variant' => null,
])

@php
    $activeTab = $active ?? array_key_first($tabs);
@endphp

<div {{ $attributes }} x-data="auiTabs({ active: @js($activeTab) })">
    <div @class([
        'aui-tabs',
        'aui-tabs-'.$variant => filled($variant) && $variant !== 'line',
    ]) role="tablist">
        @foreach ($tabs as $key => $label)
            <button
                type="button"
                role="tab"
                class="aui-tab"
                x-bind:class="{ 'aui-tab-active': isActive(@js($key)) }"
                x-bind:aria-selected="isActive(@js($key))"
                x-on:click="select(@js($key))"
            >{{ $label }}</button>
        @endforeach
    </div>

    {{ $slot }}
</div>
