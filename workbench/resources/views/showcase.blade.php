@php
    $users = new \Illuminate\Pagination\LengthAwarePaginator(
        items: [
            ['Ada Lovelace', 'Administrator', 'success', 'Active'],
            ['Grace Hopper', 'Editor', 'warning', 'Pending'],
        ],
        total: 42,
        perPage: 2,
        currentPage: 2,
        options: ['path' => '/', 'pageName' => 'page'],
    );
@endphp
<!DOCTYPE html>
<html lang="en" data-theme="emerald-green">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Avian UI</title>

    {{--
        Fonts, icons and Alpine are pulled from a CDN for this local workbench
        preview only. The package itself never loads anything remote: it ships
        its own CSS and JS, and leaves fonts, icons and Alpine to the host app.
    --}}
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800|outfit:600,700,800">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <x-avian::styles />

    {{-- The package script registers its Alpine components, so it loads first. --}}
    <x-avian::scripts />
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/mask@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { margin: 0; padding: 30px; background: #f5f7fa; font-family: var(--aui-font-sans); }
        .showcase { max-width: 1080px; margin: 0 auto; }
        .showcase > * + * { margin-top: 24px; }
    </style>
</head>
<body>
    <div class="showcase">
        <x-avian::page-header title="Avian UI" subtitle="Component showcase served by the workbench.">
            <x-slot:actions>
                <x-avian::button variant="light" icon="fas fa-rotate">Refresh</x-avian::button>
                <x-avian::button icon="fas fa-plus" modal="demo">
                    New record
                </x-avian::button>
            </x-slot:actions>
        </x-avian::page-header>

        <x-avian::alert variant="info" dismissible>
            Every class in this page comes from the packaged stylesheet. Switch the
            <code>data-theme</code> attribute on <code>&lt;html&gt;</code> to repaint it.
        </x-avian::alert>

        <x-avian::card title="Buttons" subtitle="Variants, sizes and states">
            <div class="aui-row" style="flex-wrap: wrap">
                @foreach (['primary', 'secondary', 'success', 'warning', 'danger', 'info', 'light', 'outline', 'ghost'] as $variant)
                    <x-avian::button :variant="$variant">{{ ucfirst($variant) }}</x-avian::button>
                @endforeach
            </div>

            <div class="aui-row" style="margin-top: 14px">
                <x-avian::button size="sm">Small</x-avian::button>
                <x-avian::button>Default</x-avian::button>
                <x-avian::button size="lg">Large</x-avian::button>
                <x-avian::button loading>Saving</x-avian::button>
                <x-avian::button disabled>Disabled</x-avian::button>
                <x-avian::button href="#" variant="link">Link button</x-avian::button>
            </div>
        </x-avian::card>

        <x-avian::card title="Badges & status" subtitle="Pills, avatars and progress">
            <div class="aui-row" style="flex-wrap: wrap">
                <x-avian::badge variant="success" dot>Complete</x-avian::badge>
                <x-avian::badge variant="warning" dot>Ongoing</x-avian::badge>
                <x-avian::badge variant="danger" dot>Not started</x-avian::badge>
                <x-avian::badge variant="primary">Primary</x-avian::badge>
                <x-avian::badge variant="info" uppercase>Information</x-avian::badge>
                <x-avian::avatar name="Ada Lovelace" />
                <x-avian::avatar name="Grace Hopper" size="sm" />
            </div>

            <x-avian::progress :value="68" label="Completion" show-value style="margin-top: 18px" />
        </x-avian::card>

        <x-avian::card title="Form" subtitle="Labels, hints, validation and Alpine-backed controls">
            <x-avian::form action="#" method="POST" files>
                <div class="aui-form-grid">
                    <x-avian::input name="name" label="Full name" placeholder="Ada Lovelace" required />
                    <x-avian::input name="email" type="email" label="Email" icon="fas fa-envelope" />
                    <x-avian::input name="website" label="Website" prefix="https://" suffix=".com" />
                    <x-avian::select
                        name="role"
                        label="Role"
                        placeholder="Choose a role"
                        :options="['admin' => 'Administrator', 'editor' => 'Editor', 'viewer' => 'Viewer']"
                    />
                    <x-avian::searchable-select
                        name="country"
                        label="Country"
                        placeholder="Choose a country"
                        :options="['us' => 'United States', 'id' => 'Indonesia', 'jp' => 'Japan', 'de' => 'Germany']"
                    />
                    <x-avian::input name="budget" label="Budget" prefix="Rp" numeric hint="Rounded to the nearest thousand." />
                    <x-avian::file name="attachment" label="Attachment" />
                    <x-avian::textarea class="aui-form-full" name="notes" label="Notes" rows="3" />
                </div>

                <div class="aui-stack" style="margin-top: 6px">
                    <x-avian::checkbox name="terms" label="I accept the terms" hint="You can revoke this at any time." />
                    <div>
                        <x-avian::label>Plan</x-avian::label>
                        <x-avian::radio name="plan" value="basic" label="Basic" inline checked />
                        <x-avian::radio name="plan" value="pro" label="Pro" inline />
                    </div>
                    <x-avian::switch name="active" label="Active" checked />
                </div>

                <div class="aui-form-actions">
                    <x-avian::button variant="light" type="reset">Cancel</x-avian::button>
                    <x-avian::button type="submit" icon="fas fa-check">Save</x-avian::button>
                </div>
            </x-avian::form>
        </x-avian::card>

        <x-avian::card title="Table" :padded="false">
            <x-avian::table :headers="['Name', 'Role', 'Status', '']" :paginator="$users">
                @foreach ($users as [$name, $role, $variant, $status])
                    <tr>
                        <td>{{ $name }}</td>
                        <td>{{ $role }}</td>
                        <td><x-avian::badge :variant="$variant" dot>{{ $status }}</x-avian::badge></td>
                        <td class="aui-table-align-right">
                            <x-avian::dropdown align="right" size="sm" label="Actions">
                                <x-avian::dropdown-item icon="fas fa-pen">Edit</x-avian::dropdown-item>
                                <div class="aui-dropdown-divider"></div>
                                <x-avian::dropdown-item icon="fas fa-trash" danger>Delete</x-avian::dropdown-item>
                            </x-avian::dropdown>
                        </td>
                    </tr>
                @endforeach
            </x-avian::table>
        </x-avian::card>

        <x-avian::card title="Tabs">
            <x-avian::tabs :tabs="['overview' => 'Overview', 'activity' => 'Activity']">
                <x-avian::tab-panel name="overview">The first panel is shown by default.</x-avian::tab-panel>
                <x-avian::tab-panel name="activity">
                    <x-avian::empty title="No activity yet" text="Actions will show up here." />
                </x-avian::tab-panel>
            </x-avian::tabs>
        </x-avian::card>
    </div>

    <x-avian::modal name="demo" title="New record" size="lg">
        <x-avian::input name="title" label="Title" />
        <x-avian::textarea name="description" label="Description" rows="3" />

        <x-slot:footer>
            <x-avian::button variant="light" x-on:click="hide()">Cancel</x-avian::button>
            <x-avian::button icon="fas fa-check">Create</x-avian::button>
        </x-slot:footer>
    </x-avian::modal>
</body>
</html>
