{{--
    One column heading inside `<x-avian::table>`. Renders a plain `<th>`, or a
    sort toggle when given a `sort` key.

    Usage:
        <x-avian::table.heading sort="name">Name</x-avian::table.heading>

    The current sort is read from the parent table's `sort-by` /
    `sort-direction` props, falling back to the `?sort=` / `?direction=`
    query string. Clicking the heading sorts by that column ascending, or
    flips the direction when it is already the sorted column, and drops the
    page parameter so the results start from page 1.

    Inside a Livewire component the toggle becomes a button that calls
    `sortBy('<column>')` on the component instead of following a link.
--}}
@aware([
    'sortBy' => null,
    'sortDirection' => null,
    'sortParam' => 'sort',
    'directionParam' => 'direction',
    'paginator' => null,
    'livewire' => null,
])

@props([
    'sort' => null,
    'align' => null,
])

@php
    $sortable = filled($sort);

    if ($sortable) {
        $livewire ??= class_exists(\Livewire\Livewire::class) && \Livewire\Livewire::current() !== null;

        $sortBy ??= request()->query($sortParam);
        $sortDirection = strtolower((string) ($sortDirection ?? request()->query($directionParam))) === 'desc' ? 'desc' : 'asc';

        $active = is_string($sortBy) && $sortBy === $sort;
        $nextDirection = $active && $sortDirection === 'asc' ? 'desc' : 'asc';

        $pageName = $paginator instanceof \Illuminate\Contracts\Pagination\Paginator ? $paginator->getPageName() : 'page';

        $icon = match (true) {
            ! $active => 'fas fa-sort',
            $sortDirection === 'desc' => 'fas fa-sort-down',
            default => 'fas fa-sort-up',
        };
    }
@endphp

<th {{ $attributes->class([
    'aui-table-align-'.$align => filled($align),
    'aui-table-sortable' => $sortable,
    'aui-table-sorted' => $sortable && $active,
])->merge([
    'aria-sort' => $sortable ? ($active ? ($sortDirection === 'desc' ? 'descending' : 'ascending') : 'none') : null,
]) }}>
    @if ($sortable)
        @if ($livewire)
            <button type="button" class="aui-table-sort" wire:click="sortBy(@js($sort))">
                {{ $slot }}
                <i class="aui-table-sort-icon {{ $icon }}" aria-hidden="true"></i>
            </button>
        @else
            <a class="aui-table-sort" href="{{ request()->fullUrlWithQuery([$sortParam => $sort, $directionParam => $nextDirection, $pageName => null]) }}">
                {{ $slot }}
                <i class="aui-table-sort-icon {{ $icon }}" aria-hidden="true"></i>
            </a>
        @endif
    @else
        {{ $slot }}
    @endif
</th>
