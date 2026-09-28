{{--
    Lays buttons out in a row. `attached` joins them into one segmented
    control — shared borders, only the outer corners rounded — for view
    switchers and filters. Mark the current one with the button's `active`:

        <x-avian::button-group attached label="View">
            <x-avian::button variant="light" icon="fas fa-list" active>List</x-avian::button>
            <x-avian::button variant="light" icon="fas fa-grip">Grid</x-avian::button>
        </x-avian::button-group>

    `label` becomes the group's aria-label so assistive tech announces what
    the buttons belong to.
--}}
@props([
    'attached' => false,
    'vertical' => false,
    'label' => null,
])

<div {{ $attributes->class([
    'aui-btn-group',
    'aui-btn-group-attached' => $attached,
    'aui-btn-group-vertical' => $vertical,
])->merge([
    'role' => 'group',
    'aria-label' => $label,
]) }}>
    {{ $slot }}
</div>
