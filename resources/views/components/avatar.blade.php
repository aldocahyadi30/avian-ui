@props([
    'src' => null,
    'name' => null,
    'size' => null,
    'initials' => null,
])

@php
    $letters = $initials ?? (filled($name)
        ? mb_strtoupper(collect(preg_split('/\s+/', trim((string) $name)))
            ->filter()
            ->take(2)
            ->map(fn (string $part): string => mb_substr($part, 0, 1))
            ->implode(''))
        : null);
@endphp

<span {{ $attributes->class(['aui-avatar', 'aui-avatar-'.$size => filled($size)]) }}>
    @if (filled($src))
        <img src="{{ $src }}" alt="{{ $name }}">
    @else
        {{ $letters }}
    @endif
</span>
