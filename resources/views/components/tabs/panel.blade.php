@props([
    'name' => null,
])

<div {{ $attributes->class(['aui-tab-panel']) }} x-cloak x-show="isActive(@js($name))" role="tabpanel">
    {{ $slot }}
</div>
