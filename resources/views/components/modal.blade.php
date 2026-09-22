@props([
    'name' => null,
    'title' => null,
    'size' => null,
    'open' => false,
    'closeOnEscape' => true,
    'closeOnOverlay' => true,
    'closeable' => true,
])

@php
    $config = [
        'name' => $name,
        'open' => (bool) $open,
        'closeOnEscape' => (bool) $closeOnEscape,
        'closeOnOverlay' => (bool) $closeOnOverlay,
    ];
@endphp

<div
    x-data="auiModal({{ Illuminate\Support\Js::from($config) }})"
    x-on:keydown.escape.window="escape()"
    x-cloak
    x-show="open"
    class="aui-modal-overlay"
    x-on:click="overlay($event)"
    role="dialog"
    aria-modal="true"
    @if (filled($name)) data-modal="{{ $name }}" @endif
>
    <div {{ $attributes->class(['aui-modal', 'aui-modal-'.$size => filled($size)]) }}>
        @if (filled($title) || isset($header) || $closeable)
            <div class="aui-modal-header">
                @isset($header)
                    {{ $header }}
                @else
                    <h2 class="aui-modal-title">{{ $title }}</h2>
                @endisset

                @if ($closeable)
                    <button type="button" class="aui-modal-close" x-on:click="hide()" aria-label="{{ __('avian-ui::messages.close') }}">
                        <i class="fas fa-xmark" aria-hidden="true"></i>
                    </button>
                @endif
            </div>
        @endif

        <div class="aui-modal-body">{{ $slot }}</div>

        @isset($footer)
            <div class="aui-modal-footer">{{ $footer }}</div>
        @endisset
    </div>
</div>
