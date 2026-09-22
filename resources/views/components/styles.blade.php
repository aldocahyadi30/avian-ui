@props([
    'themes' => null,
])

@php
    $avianUi = app(\AvianUi\AvianUi\AvianUi::class);
    $withThemes = $themes === null ? (bool) config('avian-ui.assets.themes', true) : (bool) $themes;
@endphp

<link rel="stylesheet" href="{{ $avianUi->styleUrl() }}" {{ $attributes }}>
@if ($withThemes)
    <link rel="stylesheet" href="{{ $avianUi->themeStyleUrl() }}">
@endif
