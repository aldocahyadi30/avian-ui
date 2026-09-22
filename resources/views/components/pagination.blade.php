{{--
    Renders a Laravel paginator (simple or length-aware) with `aui-*` classes.

    Usage:
        <x-avian::pagination :paginator="$users" />

    Pass any `Illuminate\Contracts\Pagination\Paginator`. A length-aware
    paginator (the default from `paginate()`) also gets numbered page links
    and a "Showing X to Y of Z results" summary; a simple paginator
    (`simplePaginate()`) only gets Previous/Next.
--}}
@props([
    'paginator' => null,
    'onEachSide' => 1,
])

@php
    $hasPages = $paginator !== null && $paginator->hasPages();
    $isLengthAware = $paginator instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;
@endphp

@if ($hasPages)
    <nav {{ $attributes->class(['aui-pagination']) }} role="navigation" aria-label="Pagination">
        @if ($isLengthAware)
            <p class="aui-pagination-summary">
                Showing
                <span class="aui-pagination-summary-strong">{{ $paginator->firstItem() }}</span>
                to
                <span class="aui-pagination-summary-strong">{{ $paginator->lastItem() }}</span>
                of
                <span class="aui-pagination-summary-strong">{{ $paginator->total() }}</span>
                results
            </p>
        @endif

        <div class="aui-pagination-links">
            @if ($paginator->onFirstPage())
                <span class="aui-pagination-link aui-pagination-link-disabled" aria-disabled="true">
                    <i class="fas fa-chevron-left" aria-hidden="true"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="aui-pagination-link" rel="prev">
                    <i class="fas fa-chevron-left" aria-hidden="true"></i>
                </a>
            @endif

            @if ($isLengthAware)
                @php
                    $current = $paginator->currentPage();
                    $last = $paginator->lastPage();
                    $start = max($current - $onEachSide, 1);
                    $end = min($current + $onEachSide, $last);
                @endphp

                @if ($start > 1)
                    <a href="{{ $paginator->url(1) }}" class="aui-pagination-link">1</a>

                    @if ($start > 2)
                        <span class="aui-pagination-ellipsis">&hellip;</span>
                    @endif
                @endif

                @for ($page = $start; $page <= $end; $page++)
                    @if ($page === $current)
                        <span class="aui-pagination-link aui-pagination-link-active" aria-current="page">{{ $page }}</span>
                    @else
                        <a href="{{ $paginator->url($page) }}" class="aui-pagination-link">{{ $page }}</a>
                    @endif
                @endfor

                @if ($end < $last)
                    @if ($end < $last - 1)
                        <span class="aui-pagination-ellipsis">&hellip;</span>
                    @endif

                    <a href="{{ $paginator->url($last) }}" class="aui-pagination-link">{{ $last }}</a>
                @endif
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="aui-pagination-link" rel="next">
                    <i class="fas fa-chevron-right" aria-hidden="true"></i>
                </a>
            @else
                <span class="aui-pagination-link aui-pagination-link-disabled" aria-disabled="true">
                    <i class="fas fa-chevron-right" aria-hidden="true"></i>
                </span>
            @endif
        </div>
    </nav>
@endif
