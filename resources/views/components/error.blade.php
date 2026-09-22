@props([
    'name' => null,
    'errorBag' => null,
])

@php
    $message = app(\AvianUi\AvianUi\AvianUi::class)->errorFor($name, $errorBag);
@endphp

@if (filled($message) || $slot->isNotEmpty())
    <span {{ $attributes->class(['aui-error']) }}>{{ $slot->isNotEmpty() ? $slot : $message }}</span>
@endif
