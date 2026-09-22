@props([
    'title' => null,
    'subtitle' => null,
])

<div {{ $attributes->class(['aui-page-header']) }}>
    <div>
        @if (filled($title))
            <h1 class="aui-page-title">{{ $title }}</h1>
        @endif

        @if (filled($subtitle))
            <p class="aui-page-subtitle">{{ $subtitle }}</p>
        @endif

        {{ $slot }}
    </div>

    @isset($actions)
        <div class="aui-page-actions">{{ $actions }}</div>
    @endisset
</div>
