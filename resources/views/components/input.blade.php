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

    $grouped = filled($prefix) || filled($suffix) || filled($icon);
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
        ])>
            @if (filled($icon))
                <i class="aui-input-icon {{ $icon }}" aria-hidden="true"></i>
            @endif

            @if (filled($prefix))
                <span class="aui-input-affix aui-input-affix-prefix">{{ $prefix }}</span>
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
            @if (filled($suffix))
                <span class="aui-input-affix aui-input-affix-suffix">{{ $suffix }}</span>
            @endif
        </div>
    @endif
</x-avian-ui::field>
