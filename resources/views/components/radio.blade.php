@props([
    'name' => null,
    'id' => null,
    'value' => null,
    'label' => null,
    'hint' => null,
    'error' => null,
    'errorBag' => null,
    'checked' => false,
    'inline' => false,
    'field' => false,
])

@php
    $avianUi = app(\AvianUi\AvianUi\AvianUi::class);

    $inputError = $error ?? ($field ? $avianUi->errorFor($name, $errorBag) : null);
    $inputId = $id ?? (filled($name) ? 'aui-'.str_replace(['[', ']', '.', '_'], '-', trim((string) $name, '[]')).'-'.$value : null);
@endphp

<x-avian-ui::field :bare="! $field" :error="$inputError">
    <label @class(['aui-check', 'aui-check-inline' => $inline]) @if (filled($inputId)) for="{{ $inputId }}" @endif>
        <input
            {{ $attributes->class(['aui-check-input'])->merge([
                'type' => 'radio',
                'name' => $name,
                'id' => $inputId,
                'value' => $value,
                'checked' => (bool) $checked,
            ]) }}
        >

        @if (filled($label) || filled($hint) || $slot->isNotEmpty())
            <span class="aui-check-body">
                <span class="aui-check-label">{{ $label ?? $slot }}</span>

                @if (filled($hint))
                    <span class="aui-check-hint">{{ $hint }}</span>
                @endif
            </span>
        @endif
    </label>
</x-avian-ui::field>
