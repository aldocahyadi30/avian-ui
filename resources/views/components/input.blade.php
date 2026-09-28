{{--
    `prepend` and `append` attach buttons to the edges of the input — a search
    box with its submit button, a copy-to-clipboard field, a -/+ stepper.
    Match the button `size` to the input's:

        <x-avian::input name="q" icon="fas fa-search" placeholder="Search orders">
            <x-slot:append>
                <x-avian::button type="submit">Search</x-avian::button>
            </x-slot:append>
        </x-avian::input>

    They sit outside the text addons: prepend, prefix, input, suffix, append.
--}}
@props([
    'name' => null,
    'id' => null,
    'type' => 'text',
    'value' => null,
    'label' => null,
    'hint' => null,
    'error' => null,
    'errorBag' => null,
    'required' => false,
    'size' => null,
    'prefix' => null,
    'suffix' => null,
    'icon' => null,
    'numeric' => false,
    'field' => true,
])

@php
    $avianUi = app(\AvianUi\AvianUi\AvianUi::class);

    $inputError = $error ?? $avianUi->errorFor($name, $errorBag);
    $inputId = $id ?? (filled($name) ? 'aui-'.str_replace(['[', ']', '.', '_'], '-', trim((string) $name, '[]')) : null);

    $wired = $attributes->whereStartsWith('wire:model')->isNotEmpty();
    $inputValue = $value;

    if ($inputValue === null && ! $wired && ! in_array($type, ['password', 'file'], true)) {
        $inputValue = $avianUi->oldValue($name);
    }

    // The money mask formats as the user types (thousands separators, a
    // decimal point) — a native `type="number"` input rejects those
    // characters, so `numeric` needs a plain text field instead.
    $inputType = $numeric ? 'text' : $type;

    $prepended = isset($prepend) && $prepend->isNotEmpty();
    $appended = isset($append) && $append->isNotEmpty();

    $grouped = filled($prefix) || filled($suffix) || filled($icon) || $prepended || $appended;
@endphp

<x-avian-ui::field
    :bare="! $field"
    :label="$label"
    :for="$inputId"
    :hint="$hint"
    :error="$inputError"
    :required="$required"
>
    @if ($grouped)
        <div @class([
            'aui-input-group',
            'aui-input-group-prefixed' => filled($prefix),
            'aui-input-group-suffixed' => filled($suffix),
            'aui-input-group-icon' => filled($icon),
            'aui-input-group-prepended' => $prepended,
            'aui-input-group-appended' => $appended,
        ])>
            @if ($prepended)
                <div {{ $prepend->attributes->class(['aui-input-addon', 'aui-input-addon-prepend']) }}>
                    {{ $prepend }}
                </div>
            @endif

            @if (filled($prefix))
                <span class="aui-input-affix aui-input-affix-prefix">{{ $prefix }}</span>
            @endif

            {{-- The icon is positioned against this wrapper, so it stays inside
                 the text box instead of landing on a prefix or prepended button. --}}
            @if (filled($icon))
                <div class="aui-input-control">
                    <i class="aui-input-icon {{ $icon }}" aria-hidden="true"></i>
            @endif
    @endif

    <input
        {{ $attributes->class([
            'aui-input',
            'aui-input-'.$size => filled($size),
            'aui-input-invalid' => filled($inputError),
        ])->merge([
            'type' => $inputType,
            'name' => $name,
            'id' => $inputId,
            'value' => $inputValue,
            'required' => $required,
            'inputmode' => $numeric ? 'decimal' : null,
            'aria-invalid' => filled($inputError) ? 'true' : null,
        ]) }}
        @if ($numeric) x-data="{}" x-mask:dynamic="$money($input)" @endif
    >

    @if ($grouped)
            @if (filled($icon))
                </div>
            @endif

            @if (filled($suffix))
                <span class="aui-input-affix aui-input-affix-suffix">{{ $suffix }}</span>
            @endif

            @if ($appended)
                <div {{ $append->attributes->class(['aui-input-addon', 'aui-input-addon-append']) }}>
                    {{ $append }}
                </div>
            @endif
        </div>
    @endif
</x-avian-ui::field>
