{{--
    A paginated collection that renders its items as a list or as a grid of
    cards, with a built-in toggle between the two. The table's sibling for
    data that reads better as cards (products, files, people).

    Usage:
        <x-avian::datalist :paginator="$products" view="grid" :columns="3">
            @foreach ($products as $product)
                <x-avian::datalist.item
                    :title="$product->name"
                    :subtitle="$product->sku"
                    :image="$product->image_url"
                    :href="route('products.show', $product)"
                >
                    {{ $product->summary }}
                </x-avian::datalist.item>
            @endforeach
        </x-avian::datalist>

    The layout switch happens client-side: every item is rendered once and
    the container swaps `aui-datalist-list` / `aui-datalist-grid`, so items
    style themselves through descendant selectors and never need to know
    the current mode. The server renders the initial class too, so the
    first paint (and a page without Alpine) already shows `view`.

    `persist="products"` remembers the viewer's choice in localStorage under
    that key. With Livewire, `wire:model` binds the view through Alpine's
    `x-modelable`, so the server can read or change it like any property.
--}}
@props([
    'paginator' => null,
    'view' => 'list',
    'columns' => 3,
    'toggle' => true,
    'persist' => null,
    'empty' => null,
    'emptyText' => null,
    'emptyIcon' => 'fas fa-inbox',
])

@php
    $initialView = $view === 'grid' ? 'grid' : 'list';
    $gridColumns = max(1, min(4, (int) $columns));

    $modelAttributes = $attributes->whereStartsWith('wire:model');
    $rootAttributes = $attributes->except(array_keys($modelAttributes->getAttributes()));

    $showEmpty = $empty !== false && $slot->isEmpty();
    $hasToolbar = $toggle || isset($toolbar);
@endphp

<div
    x-data="auiDatalist({ view: @js($initialView), persist: @js($persist) })"
    x-modelable="view"
    {{ $modelAttributes }}
    {{ $rootAttributes->class(['aui-datalist']) }}
>
    @if ($hasToolbar)
        <div class="aui-datalist-toolbar">
            <div class="aui-datalist-toolbar-start">
                {{ $toolbar ?? '' }}
            </div>

            @if ($toggle)
                <div class="aui-datalist-toggle" role="group" aria-label="{{ __('avian-ui::messages.view') }}">
                    <button
                        type="button"
                        @class(['aui-datalist-toggle-button', 'is-active' => $initialView === 'list'])
                        x-bind:class="{ 'is-active': view === 'list' }"
                        x-bind:aria-pressed="view === 'list'"
                        x-on:click="set('list')"
                        aria-pressed="{{ $initialView === 'list' ? 'true' : 'false' }}"
                        aria-label="{{ __('avian-ui::messages.list_view') }}"
                        title="{{ __('avian-ui::messages.list_view') }}"
                    >
                        <i class="fas fa-list" aria-hidden="true"></i>
                    </button>
                    <button
                        type="button"
                        @class(['aui-datalist-toggle-button', 'is-active' => $initialView === 'grid'])
                        x-bind:class="{ 'is-active': view === 'grid' }"
                        x-bind:aria-pressed="view === 'grid'"
                        x-on:click="set('grid')"
                        aria-pressed="{{ $initialView === 'grid' ? 'true' : 'false' }}"
                        aria-label="{{ __('avian-ui::messages.grid_view') }}"
                        title="{{ __('avian-ui::messages.grid_view') }}"
                    >
                        <i class="fas fa-grip" aria-hidden="true"></i>
                    </button>
                </div>
            @endif
        </div>
    @endif

    @if ($showEmpty)
        <div class="aui-datalist-empty">
            @if ($empty instanceof \Illuminate\View\ComponentSlot)
                {{ $empty }}
            @else
                <x-avian-ui::empty
                    :icon="$emptyIcon"
                    :title="$empty ?? __('avian-ui::messages.no_results')"
                    :text="$emptyText"
                />
            @endif
        </div>
    @else
        <div
            @class([
                'aui-datalist-items',
                'aui-datalist-cols-'.$gridColumns,
                'aui-datalist-list' => $initialView === 'list',
                'aui-datalist-grid' => $initialView === 'grid',
            ])
            {{-- Object syntax: Alpine also removes the server-rendered class
                 when it turns false, which a plain string binding would not. --}}
            x-bind:class="{ 'aui-datalist-list': view !== 'grid', 'aui-datalist-grid': view === 'grid' }"
        >
            {{ $slot }}
        </div>
    @endif

    @if ($paginator !== null)
        <x-avian-ui::pagination :paginator="$paginator" />
    @endif
</div>
