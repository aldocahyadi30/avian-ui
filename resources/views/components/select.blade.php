@props([
    'name' => null,
    'id' => null,
    'value' => null,
    'options' => [],
    'placeholder' => null,
    'label' => null,
    'hint' => null,
    'error' => null,
    'errorBag' => null,
    'required' => false,
    'size' => null,
    'field' => true,
])

@php
    $avianUi = app(\AvianUi\AvianUi\AvianUi::class);

    $inputError = $error ?? $avianUi->errorFor($name, $errorBag);
    $inputId = $id ?? (filled($name) ? 'aui-'.str_replace(['[', ']', '.', '_'], '-', trim((string) $name, '[]')) : null);

    $wired = $attributes->whereStartsWith('wire:model')->isNotEmpty();
    $selected = $value;

    if ($selected === null && ! $wired) {
        $selected = $avianUi->oldValue($name);
    }

    $selected = is_array($selected) ? array_map('strval', $selected) : $selected;

    $isSelected = function ($option) use ($selected): bool {
        if (is_array($selected)) {
            return in_array((string) $option, $selected, true);
        }

        return $selected !== null && (string) $option === (string) $selected;
    };
@endphp

<x-avian-ui::field
    :bare="! $field"
    :label="$label"
    :for="$inputId"
    :hint="$hint"
    :error="$inputError"
    :required="$required"
>
    <select
        {{ $attributes->class([
            'aui-select',
            'aui-select-'.$size => filled($size),
            'aui-select-invalid' => filled($inputError),
        ])->merge([
            'name' => $name,
            'id' => $inputId,
            'required' => $required,
            'aria-invalid' => filled($inputError) ? 'true' : null,
        ]) }}
    >
        @if (filled($placeholder))
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected($isSelected($optionValue))>{{ $optionLabel }}</option>
        @endforeach

        {{ $slot }}
    </select>
</x-avian-ui::field>
