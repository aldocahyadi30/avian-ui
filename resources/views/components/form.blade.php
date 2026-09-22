@props([
    'action' => null,
    'method' => 'POST',
    'csrf' => true,
    'files' => false,
])

@php
    $formMethod = strtoupper($method);
    $spoofed = ! in_array($formMethod, ['GET', 'POST'], true);
@endphp

<form
    {{ $attributes->class(['aui-form'])->merge([
        'method' => $spoofed ? 'POST' : $formMethod,
        'action' => $action,
        'enctype' => $files ? 'multipart/form-data' : null,
    ]) }}
>
    @if ($csrf && $formMethod !== 'GET')
        @csrf
    @endif

    @if ($spoofed)
        @method($formMethod)
    @endif

    {{ $slot }}
</form>
