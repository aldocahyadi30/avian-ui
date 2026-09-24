@php
    $tableUsers = new \Illuminate\Pagination\LengthAwarePaginator(
        items: [
            ['Ada Lovelace', 'Administrator', 'success', 'Active'],
            ['Grace Hopper', 'Editor', 'warning', 'Pending'],
        ],
        total: 42,
        perPage: 2,
        currentPage: 2,
        options: ['path' => '/', 'pageName' => 'page'],
    );

    $props = [
        ['headers', 'array', '[]', 'Column headings, in order. Use an empty string for a column without a heading (e.g. actions).'],
        ['paginator', 'Paginator|null', 'null', 'A paginate() / simplePaginate() result. Renders <x-avian::pagination> under the table.'],
        ['hover', 'bool', 'true', 'Highlights the row under the mouse.'],
        ['striped', 'bool', 'false', 'Alternating row backgrounds.'],
        ['size', "'sm'|null", 'null', 'Compact rows for dense data.'],
        ['empty', 'string|false|slot|null', 'null', 'Title of the empty state shown when the body slot is empty. false disables it; a named slot replaces it.'],
        ['empty-text', 'string|null', 'null', 'Extra line under the empty-state title.'],
        ['empty-icon', 'string', "'fas fa-inbox'", 'Icon of the empty state.'],
        ['columns', 'int|null', 'null', 'colspan of the empty row. Defaults to the number of headers — set it when you use the head slot.'],
        ['head (slot)', 'slot', '—', 'Replaces the generated header row with your own <tr>(s).'],
        ['foot (slot)', 'slot', '—', 'Rows for a <tfoot>, e.g. totals.'],
    ];

    $examples = [
        [
            'title' => 'Basic table from a query',
            'text' => 'You write the <tr> rows yourself, so each cell can contain anything: badges, avatars, dropdowns.',
            'code' => <<<'BLADE'
                {{-- Controller: $users = User::latest()->paginate(15); --}}

                <x-avian::table :headers="['Name', 'Email', 'Status']" :paginator="$users">
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td><x-avian::badge variant="success" dot>{{ $user->status }}</x-avian::badge></td>
                        </tr>
                    @endforeach
                </x-avian::table>
                BLADE,
        ],
        [
            'title' => 'Row actions',
            'text' => 'Give the actions column an empty heading and align it right with .aui-table-align-right (.aui-table-align-center also exists).',
            'code' => <<<'BLADE'
                <x-avian::table :headers="['Name', '']">
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td class="aui-table-align-right">
                                <x-avian::dropdown align="right" size="sm" label="Actions">
                                    <x-avian::dropdown.item :href="route('users.edit', $user)" icon="fas fa-pen">Edit</x-avian::dropdown.item>
                                </x-avian::dropdown>
                            </td>
                        </tr>
                    @endforeach
                </x-avian::table>
                BLADE,
        ],
        [
            'title' => 'Empty state',
            'text' => 'When the loop renders no rows, the table shows an empty state across all columns automatically. Customise it with empty / empty-text / empty-icon, or pass a named slot.',
            'code' => <<<'BLADE'
                <x-avian::table
                    :headers="['Name', 'Email']"
                    empty="No users found"
                    empty-text="Try a different search."
                    empty-icon="fas fa-user-slash"
                >
                    @foreach ($users as $user) ... @endforeach
                </x-avian::table>

                {{-- Full control over the empty row --}}
                <x-avian::table :headers="['Name', 'Email']">
                    <x-slot:empty>
                        <x-avian::empty title="No users yet">
                            <x-avian::button :href="route('users.create')">Invite someone</x-avian::button>
                        </x-avian::empty>
                    </x-slot:empty>
                </x-avian::table>
                BLADE,
        ],
        [
            'title' => 'Custom header and totals footer',
            'code' => <<<'BLADE'
                <x-avian::table :columns="3" striped size="sm">
                    <x-slot:head>
                        <tr>
                            <th>Item</th>
                            <th class="aui-table-align-right">Qty</th>
                            <th class="aui-table-align-right">Total</th>
                        </tr>
                    </x-slot:head>

                    @foreach ($order->lines as $line) ... @endforeach

                    <x-slot:foot>
                        <tr>
                            <th colspan="2">Grand total</th>
                            <th class="aui-table-align-right">{{ number_format($order->total) }}</th>
                        </tr>
                    </x-slot:foot>
                </x-avian::table>
                BLADE,
        ],
        [
            'title' => 'Inside a card',
            'code' => <<<'BLADE'
                <x-avian::card title="Users" :padded="false">
                    <x-avian::table :headers="['Name', 'Email']" :paginator="$users">...</x-avian::table>
                </x-avian::card>
                BLADE,
        ],
    ];
@endphp

<x-avian::card title="Table" subtitle="Data rows with pagination and an empty state">
    <p class="aui-showcase-lead">
        A styled <code>&lt;table&gt;</code> that scrolls horizontally on small screens, draws its own header
        from a list of column names, renders pagination when you hand it a paginator and shows an empty
        state when there are no rows.
    </p>

    <div class="aui-showcase-demo">
        <div class="aui-stack" style="gap: 20px">
            <x-avian::card :padded="false">
                <x-avian::table :headers="['Name', 'Role', 'Status', '']" :paginator="$tableUsers">
                    @foreach ($tableUsers as [$name, $role, $variant, $status])
                        <tr>
                            <td>
                                <div class="aui-row">
                                    <x-avian::avatar :name="$name" size="sm" />
                                    {{ $name }}
                                </div>
                            </td>
                            <td>{{ $role }}</td>
                            <td><x-avian::badge :variant="$variant" dot>{{ $status }}</x-avian::badge></td>
                            <td class="aui-table-align-right">
                                <x-avian::dropdown align="right" size="sm" label="Actions">
                                    <x-avian::dropdown.item icon="fas fa-pen">Edit</x-avian::dropdown.item>
                                    <div class="aui-dropdown-divider"></div>
                                    <x-avian::dropdown.item icon="fas fa-trash" danger>Delete</x-avian::dropdown.item>
                                </x-avian::dropdown>
                            </td>
                        </tr>
                    @endforeach
                </x-avian::table>
            </x-avian::card>

            <x-avian::card :padded="false">
                <x-avian::table :headers="['Item', 'Qty', 'Price']" striped size="sm">
                    <tr><td>Wall paint 5L</td><td>2</td><td>Rp 450.000</td></tr>
                    <tr><td>Wood varnish 1L</td><td>1</td><td>Rp 120.000</td></tr>
                    <tr><td>Primer 2.5L</td><td>3</td><td>Rp 270.000</td></tr>
                </x-avian::table>
            </x-avian::card>

            <x-avian::card :padded="false">
                <x-avian::table :headers="['Name', 'Email']" empty="No users found" empty-text="Try a different search." empty-icon="fas fa-user-slash" />
            </x-avian::card>
        </div>
    </div>

    <div class="aui-showcase-block">
        <h4 class="aui-showcase-heading">How it works</h4>
        <ul class="aui-showcase-list">
            <li>The table sits in a <code>.aui-table-wrap</code> that scrolls sideways when the columns don't fit, so the page itself never overflows.</li>
            <li>The empty state appears whenever the body slot renders nothing — an empty <code>@@foreach</code> is enough, no <code>@@forelse</code> needed.</li>
            <li>With <code>:paginator</code>, pagination links and a "Showing X to Y of Z" summary appear under the table. They keep the current page URL, so add <code>-&gt;withQueryString()</code> to keep filters.</li>
            <li>Dropdowns inside rows are positioned so the scroll wrapper doesn't clip them.</li>
        </ul>
    </div>

    @include('showcase.partials.props')

    <div class="aui-showcase-block">
        <h4 class="aui-showcase-heading">Examples</h4>
        @foreach ($examples as $example)
            @include('showcase.partials.example', ['example' => $example])
        @endforeach
    </div>
</x-avian::card>
