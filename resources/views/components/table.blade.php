@props([
    'headers' => [],
    'hover' => true,
    'striped' => false,
    'size' => null,
    'paginator' => null,
    'empty' => null,
    'emptyText' => null,
    'emptyIcon' => 'fas fa-inbox',
    'columns' => null,
])

@php
    $showEmpty = $empty !== false && $slot->isEmpty();
    $emptyColumns = $columns ?? max(count($headers), 1);
@endphp

<div class="aui-table-wrap">
    <table {{ $attributes->class([
        'aui-table',
        'aui-table-hover' => $hover,
        'aui-table-striped' => $striped,
        'aui-table-'.$size => filled($size),
    ]) }}>
        @if (filled($headers) || isset($head))
            <thead>
                @isset($head)
                    {{ $head }}
                @else
                    <tr>
                        @foreach ($headers as $header)
                            <th>{{ $header }}</th>
                        @endforeach
                    </tr>
                @endisset
            </thead>
        @endif

        <tbody>
            @if ($showEmpty)
                <tr class="aui-table-empty">
                    <td colspan="{{ $emptyColumns }}">
                        @if ($empty instanceof \Illuminate\View\ComponentSlot)
                            {{ $empty }}
                        @else
                            <x-avian-ui::empty
                                :icon="$emptyIcon"
                                :title="$empty ?? __('avian-ui::messages.no_results')"
                                :text="$emptyText"
                            />
                        @endif
                    </td>
                </tr>
            @else
                {{ $slot }}
            @endif
        </tbody>

        @isset($foot)
            <tfoot>{{ $foot }}</tfoot>
        @endisset
    </table>
</div>

@if ($paginator !== null)
    <x-avian-ui::pagination :paginator="$paginator" />
@endif
