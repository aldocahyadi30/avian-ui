@props([
    'icon' => 'fas fa-inbox',
    'title' => null,
    'text' => null,
])

<div {{ $attributes->class(['aui-empty']) }}>
    @if (filled($icon))
        <div class="aui-empty-icon"><i class="{{ $icon }}" aria-hidden="true"></i></div>
    @endif

    @if (filled($title))
        <p class="aui-empty-title">{{ $title }}</p>
    @endif

    @if (filled($text))
        <p class="aui-empty-text">{{ $text }}</p>
    @endif

    {{ $slot }}
</div>
