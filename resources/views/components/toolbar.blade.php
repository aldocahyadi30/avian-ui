{{--
    A row of controls above a table or list: filters and search on the left,
    actions in the `end` slot pushed to the right. It wraps onto a second
    line on narrow screens instead of overflowing:

        <x-avian::toolbar label="Orders">
            <x-avian::input name="q" icon="fas fa-search" placeholder="Search" :field="false" />
            <x-avian::button-group attached>...</x-avian::button-group>

            <x-slot:end>
                <x-avian::button icon="fas fa-plus">New order</x-avian::button>
            </x-slot:end>
        </x-avian::toolbar>
--}}
@props([
    'label' => null,
])

<div {{ $attributes->class(['aui-toolbar'])->merge([
    'role' => 'toolbar',
    'aria-label' => $label,
]) }}>
    <div class="aui-toolbar-start">
        {{ $slot }}
    </div>

    @isset($end)
        <div {{ $end->attributes->class(['aui-toolbar-end']) }}>
            {{ $end }}
        </div>
    @endisset
</div>
