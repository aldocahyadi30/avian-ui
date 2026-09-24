{{--
    Collapsible sections. Only one item stays open at a time unless
    `multiple` is set; mark the items that start open with `open`:

        <x-avian::accordion>
            <x-avian::accordion.item title="Shipping address" open>...</x-avian::accordion.item>
            <x-avian::accordion.item title="Billing address">...</x-avian::accordion.item>
        </x-avian::accordion>

    `flush` drops the outer border and radius, for an accordion that sits
    inside a card. State lives in Alpine, so a Livewire re-render keeps
    whichever items the user opened.
--}}
@props([
    'multiple' => false,
    'flush' => false,
])

<div
    x-data="auiAccordion({ multiple: @js((bool) $multiple) })"
    {{ $attributes->class(['aui-accordion', 'aui-accordion-flush' => $flush]) }}
>
    {{ $slot }}
</div>
