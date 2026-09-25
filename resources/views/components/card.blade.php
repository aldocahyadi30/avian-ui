{{--
    A surface with an optional header (title, subtitle, actions), body and
    footer.

    `collapsible` adds a chevron to the header that folds the body and footer
    away, leaving only the header; clicking the header outside its actions
    does the same. `collapsed` starts it folded, and `persist="orders"`
    remembers the viewer's choice in localStorage under that key. State lives
    in Alpine, so a Livewire re-render keeps whatever the viewer picked.
--}}
@props([
    'title' => null,
    'subtitle' => null,
    'flush' => false,
    'padded' => true,
    'collapsible' => false,
    'collapsed' => false,
    'persist' => null,
])

@php
    $collapsed = $collapsible && $collapsed;
    $hasHeader = $collapsible || filled($title) || filled($subtitle) || isset($actions) || isset($header);
@endphp

<div
    @if ($collapsible)
        x-data="auiCard({ collapsed: @js($collapsed), persist: @js($persist) })"
        x-id="['aui-card']"
        x-bind:class="{ 'is-collapsed': collapsed }"
    @endif
    {{ $attributes->class([
        'aui-card',
        'aui-card-flush' => $flush,
        'aui-card-collapsible' => $collapsible,
        'is-collapsed' => $collapsed,
    ]) }}
>
    @if ($hasHeader)
        <div class="aui-card-header" @if ($collapsible) x-on:click="headerClick($event)" @endif>
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

            @if (isset($actions) || $collapsible)
                <div class="aui-card-actions">
                    {{ $actions ?? '' }}

                    @if ($collapsible)
                        <button
                            type="button"
                            class="aui-card-toggle"
                            x-on:click="toggle()"
                            x-bind:aria-expanded="(! collapsed).toString()"
                            x-bind:aria-controls="$id('aui-card')"
                            aria-expanded="{{ $collapsed ? 'false' : 'true' }}"
                            aria-label="{{ filled($title) ? $title : __('avian-ui::messages.toggle') }}"
                        >
                            <i class="fas fa-chevron-down aui-card-toggle-icon" aria-hidden="true"></i>
                        </button>
                    @endif
                </div>
            @endif
        </div>
    @endif

    @if ($collapsible)
        <div
            class="aui-card-content"
            x-show="! collapsed"
            x-bind:id="$id('aui-card')"
            @if ($collapsed) style="display: none" @endif
        >
    @endif

    @if ($padded)
        <div class="aui-card-body">{{ $slot }}</div>
    @else
        {{ $slot }}
    @endif

    @isset($footer)
        <div class="aui-card-footer">{{ $footer }}</div>
    @endisset

    @if ($collapsible)
        </div>
    @endif
</div>
