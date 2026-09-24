{{--
    One row of `<x-avian::multi-select>`'s dropdown. Rendered automatically for
    every entry of the parent's `options`, or by hand for custom row markup:

        <x-avian::multi-select.option :value="$user->id" :label="$user->name">
            <strong>{{ $user->name }}</strong> <small>{{ $user->email }}</small>
        </x-avian::multi-select.option>

    `label` is the plain text the chip shows once this row is picked; the slot
    is only what the dropdown draws. `selected` (the parent's value list) only
    drives the server-rendered `active` class — Alpine keeps it in sync after.
--}}
@props([
    'value' => null,
    'label' => null,
    'selected' => [],
])

@php
    $isActive = in_array((string) $value, array_map('strval', (array) $selected), true);
@endphp

<button
    type="button"
    wire:key="aui-multiselect-option-{{ $value }}"
    class="aui-combobox-item{{ $isActive ? ' active' : '' }}"
    data-value="{{ $value }}"
    data-label="{{ $label }}"
    :class="{ active: isSelected(@js((string) $value)) }"
    x-bind:aria-selected="isSelected(@js((string) $value))"
    x-init="remember(@js((string) $value), @js((string) $label))"
    x-on:click="toggleValue(@js((string) $value), @js((string) $label))"
    role="option"
    {{ $attributes }}
>
    <span>{{ $slot->isNotEmpty() ? $slot : $label }}</span>
    <i class="fas fa-check aui-combobox-item-check" aria-hidden="true"></i>
</button>
