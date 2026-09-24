{{--
    A trail of links back up the page hierarchy. Pass `items` as
    `label => url` pairs — the last entry is the current page, rendered as
    plain text with `aria-current="page"` (its url may be null):

        <x-avian::breadcrumbs :items="[
            'Dashboard' => route('dashboard'),
            'Orders' => route('orders.index'),
            $order->number => null,
        ]" />

    A list of arrays works too, for icons or labels that repeat:

        <x-avian::breadcrumbs :items="[
            ['label' => 'Home', 'href' => route('home'), 'icon' => 'fas fa-house'],
            ['label' => 'Settings'],
        ]" />

    Or write the trail yourself with `<x-avian::breadcrumbs.item>` children —
    an item without `href` renders as the current page:

        <x-avian::breadcrumbs>
            <x-avian::breadcrumbs.item href="/" icon="fas fa-house">Home</x-avian::breadcrumbs.item>
            <x-avian::breadcrumbs.item>Settings</x-avian::breadcrumbs.item>
        </x-avian::breadcrumbs>

    `navigate` adds `wire:navigate` to every generated link. Place it right
    above `<x-avian::page-header>`; the spacing between the two is built in.
--}}
@props([
    'items' => null,
    'navigate' => false,
])

@php
    $trail = collect($items ?? [])
        ->map(fn ($item, $key) => is_array($item)
            ? ['label' => $item['label'] ?? '', 'href' => $item['href'] ?? null, 'icon' => $item['icon'] ?? null]
            : ['label' => (string) $key, 'href' => $item, 'icon' => null])
        ->values();
@endphp

<nav {{ $attributes->class(['aui-breadcrumbs'])->merge(['aria-label' => __('avian-ui::messages.breadcrumb')]) }}>
    <ol class="aui-breadcrumbs-list">
        @if ($items !== null)
            @foreach ($trail as $item)
                <x-avian-ui::breadcrumbs.item
                    :href="$loop->last ? null : $item['href']"
                    :icon="$item['icon']"
                    :navigate="$navigate"
                >{{ $item['label'] }}</x-avian-ui::breadcrumbs.item>
            @endforeach
        @else
            {{ $slot }}
        @endif
    </ol>
</nav>
