<div align="center">
    <h1>Avian Ui</h1>
</div>

<p align="center">
    <a href="https://packagist.org/packages/aldo-octavio-cahyadi/avian-ui"><img src="https://img.shields.io/packagist/v/aldo-octavio-cahyadi/avian-ui.svg?style=flat-square" alt="Packagist"></a>
    <a href="https://packagist.org/packages/aldo-octavio-cahyadi/avian-ui"><img src="https://img.shields.io/packagist/php-v/aldo-octavio-cahyadi/avian-ui.svg?style=flat-square" alt="PHP from Packagist"></a>
    <a href="https://packagist.org/packages/aldo-octavio-cahyadi/avian-ui"><img src="https://badge.laravel.cloud/badge/aldo-octavio-cahyadi/avian-ui?style=flat" alt="Laravel versions"></a>
    <a href="https://github.com/aldo-octavio-cahyadi/avian-ui/actions"><img alt="GitHub Workflow Status (main)" src="https://img.shields.io/github/actions/workflow/status/aldo-octavio-cahyadi/avian-ui/tests.yml?branch=main&label=Tests&style=flat-square"></a>
    <a href="https://packagist.org/packages/aldo-octavio-cahyadi/avian-ui"><img src="https://img.shields.io/packagist/dt/aldo-octavio-cahyadi/avian-ui.svg?style=flat-square" alt="Total Downloads"></a>
</p>



## Installation

You can install the package via Composer:

```bash
composer require aldo-octavio-cahyadi/avian-ui
```

You may publish all of the package's resources at once:

```bash
php artisan vendor:publish --tag="avian-ui"
```

Or, you may publish each resource individually:

### Publishing the Configuration File

```bash
php artisan vendor:publish --tag="avian-ui-config"
```

### Publishing and Running the Migrations

```bash
php artisan vendor:publish --tag="avian-ui-migrations"
php artisan migrate
```

### Publishing the Views

```bash
php artisan vendor:publish --tag="avian-ui-views"
```

### Publishing the Translations

```bash
php artisan vendor:publish --tag="avian-ui-lang"
```

### Publishing the Public Assets

```bash
php artisan vendor:publish --tag="avian-ui-assets"
```

## Usage

Avian UI is a Blade component library: a small design system (`aui-*` CSS
classes driven by CSS custom properties) plus anonymous Blade components. The
interactive components are built on Alpine, so they work in a plain Blade app
and in a Livewire app without any change.

### 1. Include the assets

```blade
<head>
    <x-avian::styles />
    <x-avian::scripts />
</head>
```

Alpine is **not** bundled. `<x-avian::scripts />` only registers components on
Alpine, so it has to run before Alpine starts:

- **With Livewire** nothing else is needed. Livewire loads Alpine at the end of
  the page, so the tag in `<head>` is already early enough.
- **Without Livewire** load your own Alpine tag *below* `<x-avian::scripts />`:

```blade
<x-avian::scripts />
<script defer src="/js/alpine.min.js"></script>
```

By default the CSS and JS are served straight from the package at
`/avian-ui/...`, so there is nothing to publish or build. To serve them from
`public/` instead, publish them and turn the route off:

```bash
php artisan vendor:publish --tag="avian-ui-assets"
```

```php
// config/avian-ui.php
'assets' => ['route' => false],
```

Or point `assets.url` at a bundler output (or any base URL) and take over
completely. Every URL is suffixed with a content version, so the browser
refetches the files whenever the package is updated.

To bundle the source through Vite instead:

```js
import 'aldo-octavio-cahyadi/avian-ui/public/css/avian-ui.css';
import 'aldo-octavio-cahyadi/avian-ui/public/js/avian-ui.js';
```

### 2. Theming

Every color is a CSS custom property, and each one falls back to the host
application's `--color-*` variable:

```css
--aui-primary: var(--color-primary, #008d4c);
```

So an application that already defines `--color-primary` is themed
automatically. If it does not, the package ships seven ready-made palettes
(`emerald-green`, `sky-blue`, `steel-blue`, `brown-gold`, `teal-maroon`,
`golden-yellow`, `navy-blue`) selected with a `data-theme` attribute:

```blade
<html data-theme="sky-blue">
```

Set `assets.themes` to `false` to skip that stylesheet, or override any token
in your own CSS:

```css
:root {
    --aui-primary: #7c3aed;
    --aui-radius-lg: 16px;
    --aui-font-sans: 'Plus Jakarta Sans', sans-serif;
}
```

Icons are passed through as class strings (`icon="fas fa-plus"`), so the
package does not depend on any particular icon set.

### 3. Form components

Form controls render a label, the control, a hint and the validation message
for their `name`, resolved from the standard error bag. They also repopulate
themselves from old input after a failed validation round trip.

```blade
<x-avian::form action="{{ route('users.store') }}" method="POST">
    <div class="aui-form-grid">
        <x-avian::input name="name" label="Full name" required />
        <x-avian::input name="email" type="email" label="Email" icon="fas fa-envelope" />

        <x-avian::select
            name="role"
            label="Role"
            placeholder="Choose a role"
            :options="['admin' => 'Administrator', 'viewer' => 'Viewer']"
        />

        <x-avian::searchable-select
            name="country"
            label="Country"
            placeholder="Choose a country"
            :options="['us' => 'United States', 'id' => 'Indonesia', 'jp' => 'Japan']"
        />

        <x-avian::input name="budget" label="Budget" prefix="Rp" hint="Rounded to thousands." />
        <x-avian::textarea class="aui-form-full" name="notes" label="Notes" rows="4" />
    </div>

    <x-avian::checkbox name="terms" label="I accept the terms" />
    <x-avian::switch name="active" label="Active" checked />
    <x-avian::radio name="plan" value="pro" label="Pro" inline />
    <x-avian::file name="attachment" label="Attachment" />

    <div class="aui-form-actions">
        <x-avian::button variant="light" type="reset">Cancel</x-avian::button>
        <x-avian::button type="submit" icon="fas fa-check">Save</x-avian::button>
    </div>
</x-avian::form>
```

| Prop | Applies to | Purpose |
| --- | --- | --- |
| `name` | all controls | Drives the id, the error lookup and old input |
| `label`, `hint` | all controls | Field chrome around the control |
| `error` | all controls | Override the resolved validation message |
| `error-bag` | all controls | Read from a named error bag |
| `field` | all controls | `false` renders the bare control with no wrapper |
| `size` | input, select | `sm` or `lg` |
| `prefix`, `suffix`, `icon` | input | Input group affixes |
| `numeric` | input | Money-masked text field (see below) |
| `options`, `placeholder` | select, searchable-select | Options as `value => label` |
| `search-placeholder`, `empty-text` | searchable-select | Copy for the search box and empty state |
| `inline` | checkbox, radio | Lay several out on one line |

`numeric` renders the input as a plain text field wired to Alpine's dynamic
money mask (`x-mask:dynamic="$money($input)"`), formatting thousands
separators and a decimal point as the user types:

```blade
<x-avian::input name="budget" label="Budget" prefix="Rp" numeric />
```

This needs the [`@alpinejs/mask`](https://alpinejs.dev/plugins/mask) plugin
loaded alongside Alpine — it is not bundled by the package:

```blade
<x-avian::scripts />
<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/mask@3.x.x/dist/cdn.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
```

`searchable-select` behaves like `select` but replaces the native dropdown
with a searchable one — handy once an option list gets too long to scan.
Filtering happens client-side, in the browser, against the rendered option
labels, so it works without a Livewire component:

```blade
<x-avian::searchable-select
    name="country"
    label="Country"
    placeholder="Choose a country"
    :options="['us' => 'United States', 'id' => 'Indonesia', 'jp' => 'Japan']"
    wire:model="country"
/>
```

Drop `options` and pass `<x-avian::searchable-select.option>` children instead
for custom row markup:

```blade
<x-avian::searchable-select wire:model="itemNo" :value="$itemNo">
    @foreach ($items as $item)
        <x-avian::searchable-select.option :value="$item->id" :label="$item->name" :selected="$itemNo">
            <strong>{{ $item->id }}</strong> <small>{{ $item->name }}</small>
        </x-avian::searchable-select.option>
    @endforeach
</x-avian::searchable-select>
```

By default filtering runs client-side, in the browser, against the rendered
option labels, so it works without a Livewire component. Pass `search-model`
to hand filtering to the server instead (Livewire only) — the parent owns the
search property and returns an already-filtered `options` list, exactly like
it already owns the selected value through `wire:model` + `:value`:

```blade
<x-avian::searchable-select
    wire:model.live="filter.status"
    :value="$filter['status']"
    :options="$this->statusOptions"
    search-model="filter.statusSearch"
/>
```

All extra attributes land on the control itself, so `wire:model`, `x-on:*`,
`placeholder`, `min`, `step` and the rest behave exactly as expected:

```blade
<x-avian::input name="search" :field="false" wire:model.live.debounce.300ms="search" />
```

When a control is bound with `wire:model`, the old-input fallback is skipped so
Livewire stays the single source of truth.

### 4. General components

```blade
<x-avian::page-header title="Users" subtitle="Everyone with access">
    <x-slot:actions>
        <x-avian::button icon="fas fa-plus" x-on:click="$dispatch('aui-modal-open', { name: 'create-user' })">
            New user
        </x-avian::button>
    </x-slot:actions>
</x-avian::page-header>

<x-avian::alert variant="warning" dismissible>Two records need review.</x-avian::alert>

<x-avian::card title="Members" subtitle="Active this month" :padded="false">
    <x-avian::table :headers="['Name', 'Role', '']">
        @forelse ($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td><x-avian::badge variant="success" dot>{{ $user->role }}</x-avian::badge></td>
                <td class="aui-table-align-right">
                    <x-avian::dropdown align="right" size="sm" label="Actions">
                        <x-avian::dropdown.item icon="fas fa-pen" :href="route('users.edit', $user)">Edit</x-avian::dropdown.item>
                        <div class="aui-dropdown-divider"></div>
                        <x-avian::dropdown.item icon="fas fa-trash" danger wire:click="delete({{ $user->id }})">Delete</x-avian::dropdown.item>
                    </x-avian::dropdown>
                </td>
            </tr>
        @empty
            <tr><td colspan="3"><x-avian::empty title="No users yet" /></td></tr>
        @endforelse
    </x-avian::table>
</x-avian::card>
```

Available components: `alert`, `avatar`, `badge`, `button`, `card`,
`dropdown` (+ `dropdown.item`), `empty`, `page-header`, `pagination`,
`progress`, `scripts`, `spinner`, `styles`, `table`, `tabs` (+ `tabs.panel`),
`modal`, plus the form set `form`, `field`, `label`, `error`, `hint`, `input`,
`textarea`, `select`, `searchable-select` (+ `searchable-select.option`),
`checkbox`, `radio`, `switch`, `file`.

Pass `icon-only` for a square, icon-only button (a table row action, a
toolbar) — it has no visible text, so pass `label` for an accessible name:

```blade
<x-avian::button icon="fas fa-pen" icon-only label="Edit" size="sm" variant="light" />
<x-avian::button icon="fas fa-trash" icon-only label="Delete" size="sm" variant="light" wire:click="delete" />
```

Pass a paginator straight to the table to get Previous/Next and numbered page
links rendered underneath it:

```blade
<x-avian::table :headers="['Name', 'Role']" :paginator="$users">
    @foreach ($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->role }}</td>
        </tr>
    @endforeach
</x-avian::table>
```

`$users` can come from `paginate()` (numbered links plus a "Showing X to Y of
Z results" summary) or `simplePaginate()` (Previous/Next only). The same
markup is available on its own as `<x-avian::pagination :paginator="$users" />`
for a paginator you render outside a table.

### 5. Modals, dropdowns and tabs

```blade
<x-avian::modal name="create-user" title="New user" size="lg">
    <x-avian::input name="name" label="Name" />

    <x-slot:footer>
        <x-avian::button variant="light" x-on:click="hide()">Cancel</x-avian::button>
        <x-avian::button wire:click="save">Save</x-avian::button>
    </x-slot:footer>
</x-avian::modal>
```

A named modal listens for browser events, so anything on the page can open it.
The button component takes a `modal` prop for exactly this:

```blade
<x-avian::button modal="create-user">New user</x-avian::button>
```

From your own markup, dispatch the event yourself. Alpine only initialises
elements inside an `x-data` tree, so a standalone trigger needs its own scope:

```blade
<button x-data="{}" x-on:click="$dispatch('aui-modal-open', { name: 'create-user' })">Open</button>
```

```php
// Livewire
$this->dispatch('aui-modal-open', name: 'create-user');
$this->dispatch('aui-modal-close', name: 'create-user');
```

```js
// Plain JavaScript
window.AvianUI.openModal('create-user');
```

Tabs keep their state in Alpine. `variant` styles the tab list itself: omit it
(or pass `line`) for the default underlined tabs, `pill` for standalone
rounded buttons, or `segmented` for a grouped segmented-control look:

```blade
<x-avian::tabs :tabs="['overview' => 'Overview', 'activity' => 'Activity']" variant="segmented">
    <x-avian::tabs.panel name="overview">...</x-avian::tabs.panel>
    <x-avian::tabs.panel name="activity">...</x-avian::tabs.panel>
</x-avian::tabs>
```

The Alpine components registered by the package are `auiModal`, `auiDropdown`,
`auiTabs`, `auiDismiss`, `auiFile` and `auiSearchableSelect`. The modal
releases the body scroll lock on `livewire:navigating`, so `wire:navigate`
never strands a locked page.

### 6. Configuration

```php
return [
    'prefix' => 'avian',   // <x-avian::button>; <x-avian-ui::button> always works too

    'assets' => [
        'route' => true,       // serve CSS/JS from the package, no publish needed
        'path' => 'avian-ui',  // URL prefix for that route
        'url' => null,         // or a base URL/path you serve the assets from
        'themes' => true,      // include the theme palette stylesheet
    ],
];
```

### 7. Previewing the library

```bash
composer serve
```

The workbench serves a showcase page with every component on it.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Thank you for considering contributing to Avian Ui! Please review our [contributing guide](.github/CONTRIBUTING.md) to get started.

## Security Vulnerabilities

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## Credits

- [Aldo Octavio Cahyadi](https://github.com/aldo-octavio-cahyadi)
- [All Contributors](../../contributors)

## License

Avian Ui is open-sourced software licensed under the [MIT license](LICENSE.md).
