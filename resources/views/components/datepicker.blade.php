{{--
    Renders a plain text input carrying the `flatpickr-input` hook class and
    `data-fp-*` attributes. Flatpickr itself is not bundled by this package —
    the host application loads it and upgrades the hook class on page load
    (and again after `livewire:navigated`, for a Livewire SPA-style page):

        document.querySelectorAll('.flatpickr-input').forEach((input) => {
            flatpickr(input, {
                mode: input.dataset.fpMode,
                dateFormat: input.dataset.fpDateFormat,
                enableTime: input.dataset.fpEnableTime === 'true',
                minDate: input.dataset.fpMinDate || null,
                maxDate: input.dataset.fpMaxDate || null,
            });
        });

    Flatpickr marks the input `readonly` by default (typing is disabled in
    favor of the calendar popup), which is why it renders with the same
    dimmed `.aui-input[readonly]` styling as a disabled field — pass
    `allowInput: true` to flatpickr's config if the host app wants a typable
    field instead.

    Usage:
        <x-avian::datepicker name="start_date" label="Start date" />
        <x-avian::datepicker name="range" label="Date range" mode="range" />
        <x-avian::datepicker name="datetime" label="Appointment" enable-time date-format="Y-m-d H:i" />
--}}
@props([
    'name' => null,
    'id' => null,
    'value' => null,
    'label' => null,
    'hint' => null,
    'error' => null,
    'errorBag' => null,
    'required' => false,
    'size' => null,
    'placeholder' => null,
    'mode' => 'single',
    'enableTime' => false,
    'dateFormat' => 'd/m/Y',
    'minDate' => null,
    'maxDate' => null,
    'field' => true,
    'disabled' => false,
])

@php
    $avianUi = app(\AvianUi\AvianUi\AvianUi::class);

    $inputError = $error ?? $avianUi->errorFor($name, $errorBag);
    $inputId = $id ?? (filled($name) ? 'aui-'.str_replace(['[', ']', '.', '_'], '-', trim((string) $name, '[]')) : null);

    $wired = $attributes->whereStartsWith('wire:model')->isNotEmpty();
    $inputValue = $value;

    if ($inputValue === null && ! $wired) {
        $inputValue = $avianUi->oldValue($name);
    }
@endphp

<x-avian-ui::field
    :bare="! $field"
    :label="$label"
    :for="$inputId"
    :hint="$hint"
    :error="$inputError"
    :required="$required"
>
    <input
        type="text"
        autocomplete="off"
        data-fp-mode="{{ $mode }}"
        data-fp-date-format="{{ $dateFormat }}"
        @if ($enableTime) data-fp-enable-time="true" @endif
        @if ($minDate) data-fp-min-date="{{ $minDate }}" @endif
        @if ($maxDate) data-fp-max-date="{{ $maxDate }}" @endif
        {{ $attributes->class([
            'aui-input',
            'aui-input-'.$size => filled($size),
            'aui-input-invalid' => filled($inputError),
            'flatpickr-input',
        ])->merge([
            'name' => $name,
            'id' => $inputId,
            'value' => $inputValue,
            'placeholder' => $placeholder,
            'required' => $required,
            'disabled' => $disabled,
            'aria-invalid' => filled($inputError) ? 'true' : null,
        ]) }}
    >
</x-avian-ui::field>
