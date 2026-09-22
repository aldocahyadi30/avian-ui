@props([
    'title' => null,
    'subtitle' => null,
    'flush' => false,
    'padded' => true,
])

@php
    $hasHeader = filled($title) || filled($subtitle) || isset($actions) || isset($header);
@endphp

<div {{ $attributes->class(['aui-card', 'aui-card-flush' => $flush]) }}>
    @if ($hasHeader)
        <div class="aui-card-header">
            @isset($header)
                {{ $header }}
            @else
                <div>
                    @if (filled($title))
                        <h2 class="aui-card-title">{{ $title }}</h2>
                    @endif

                    @if (filled($subtitle))
                        <p class="aui-card-subtitle">{{ $subtitle }}</p>
                    @endif
                </div>
            @endisset

            @isset($actions)
                <div class="aui-card-actions">{{ $actions }}</div>
            @endisset
        </div>
    @endif

    @if ($padded)
        <div class="aui-card-body">{{ $slot }}</div>
    @else
        {{ $slot }}
    @endif

    @isset($footer)
        <div class="aui-card-footer">{{ $footer }}</div>
    @endisset
</div>
