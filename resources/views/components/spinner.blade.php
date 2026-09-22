@props([
    'size' => null,
])

<span {{ $attributes->class(['aui-spinner', 'aui-spinner-'.$size => filled($size)])->merge(['role' => 'status']) }} aria-hidden="true"></span>
