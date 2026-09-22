@props([
    'headers' => [],
    'hover' => true,
    'striped' => false,
    'size' => null,
    'paginator' => null,
])

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
            {{ $slot }}
        </tbody>

        @isset($foot)
            <tfoot>{{ $foot }}</tfoot>
        @endisset
    </table>
</div>

@if ($paginator !== null)
    <x-avian-ui::pagination :paginator="$paginator" />
@endif
