{{--
    A horizontal rule between blocks of content, optionally with a label:

        <x-avian::divider />
        <x-avian::divider label="or" />
        <x-avian::divider align="left">Shipping details</x-avian::divider>

    `align` places the label (`center` by default, `left`, `right`).
    `vertical` draws a thin upright line for use inside a flex row, e.g.
    between toolbar buttons.
--}}
@props([
    'label' => null,
    'align' => 'center',
    'vertical' => false,
])

@php
    $text = $slot->isNotEmpty() ? $slot : $label;
@endphp

@if ($vertical)
    <span {{ $attributes->class(['aui-divider-vertical']) }} role="separator" aria-orientation="vertical"></span>
@elseif (filled($text))
    <div {{ $attributes->class(['aui-divider', 'aui-divider-labelled', 'aui-divider-'.$align => $align !== 'center']) }} role="separator">
        <span class="aui-divider-label">{{ $text }}</span>
    </div>
@else
    <hr {{ $attributes->class(['aui-divider']) }}>
@endif
