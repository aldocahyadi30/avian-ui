{{--
    One entry of `<x-avian::datalist>`. The same markup serves both layouts:
    a row (media, text, meta, actions side by side) in list view and a card
    (media on top, actions in a footer) in grid view.

        <x-avian::datalist.item :title="$file->name" subtitle="PDF · 2 MB" icon="fas fa-file-pdf">
            <x-slot:meta>
                <x-avian::badge variant="success" dot>Signed</x-avian::badge>
            </x-slot:meta>

            <x-slot:actions>
                <x-avian::button icon="fas fa-download" icon-only label="Download" size="sm" variant="light" />
            </x-slot:actions>
        </x-avian::datalist.item>

    With `href` the title becomes a link whose hit area is stretched over the
    whole item, so the entire row/card is clickable while buttons in the
    `actions` slot (raised above that overlay) keep working on their own.
--}}
@props([
    'title' => null,
    'subtitle' => null,
    'image' => null,
    'icon' => null,
    'href' => null,
    'navigate' => false,
])

@php
    $hasMedia = isset($media) || filled($image) || filled($icon);
@endphp

<div {{ $attributes->class(['aui-datalist-item', 'aui-datalist-item-link' => filled($href)]) }}>
    @if ($hasMedia)
        <div class="aui-datalist-media">
            @isset($media)
                {{ $media }}
            @elseif (filled($image))
                <img src="{{ $image }}" alt="{{ $title }}" loading="lazy">
            @else
                <i class="{{ $icon }}" aria-hidden="true"></i>
            @endisset
        </div>
    @endif

    <div class="aui-datalist-body">
        @if (filled($title))
            <p class="aui-datalist-title">
                @if (filled($href))
                    <a href="{{ $href }}" @if ($navigate) wire:navigate @endif>{{ $title }}</a>
                @else
                    {{ $title }}
                @endif
            </p>
        @endif

        @if (filled($subtitle))
            <p class="aui-datalist-subtitle">{{ $subtitle }}</p>
        @endif

        @if ($slot->isNotEmpty())
            <div class="aui-datalist-content">{{ $slot }}</div>
        @endif
    </div>

    @if (isset($meta) || isset($actions))
        <div class="aui-datalist-aside">
            @isset($meta)
                <div class="aui-datalist-meta">{{ $meta }}</div>
            @endisset

            @isset($actions)
                <div class="aui-datalist-actions">{{ $actions }}</div>
            @endisset
        </div>
    @endif
</div>
