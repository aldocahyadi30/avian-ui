@props([
    'tabs' => [],
    'active' => null,
])

@php
    $activeTab = $active ?? array_key_first($tabs);
@endphp

<div {{ $attributes }} x-data="auiTabs({ active: @js($activeTab) })">
    <div class="aui-tabs" role="tablist">
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
