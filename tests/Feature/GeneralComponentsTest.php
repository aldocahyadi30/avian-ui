<?php

declare(strict_types=1);

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Blade;

it('renders a button with its variant and size', function () {
    $html = Blade::render('<x-avian::button variant="danger" size="sm">Delete</x-avian::button>');

    expect($html)->toContain('aui-btn aui-btn-danger aui-btn-sm')
        ->toContain('type="button"')
        ->toContain('Delete');
});

it('renders a button as a link when given an href', function () {
    $html = Blade::render('<x-avian::button href="/reports" icon="fas fa-file">Reports</x-avian::button>');

    expect($html)->toContain('<a')
        ->toContain('href="/reports"')
        ->toContain('<i class="fas fa-file"')
        ->not->toContain('type="button"');
});

it('adds wire:navigate to a link button only when asked', function () {
    expect(Blade::render('<x-avian::button href="/reports" navigate>Reports</x-avian::button>'))
        ->toContain('wire:navigate');

    expect(Blade::render('<x-avian::button href="/reports">Reports</x-avian::button>'))
        ->not->toContain('wire:navigate');
});

it('never adds wire:navigate to a plain button', function () {
    expect(Blade::render('<x-avian::button navigate>Reports</x-avian::button>'))
        ->not->toContain('wire:navigate');
});

it('disables a loading button and swaps its icon for a spinner', function () {
    $html = Blade::render('<x-avian::button icon="fas fa-save" loading>Saving</x-avian::button>');

    expect($html)->toContain('aui-btn-loading')
        ->toContain('disabled="disabled"')
        ->toContain('aui-spinner')
        ->not->toContain('fas fa-save');
});

it('merges livewire and alpine attributes onto the button', function () {
    expect(Blade::render('<x-avian::button wire:click="save" x-on:click="ping()">Save</x-avian::button>'))
        ->toContain('wire:click="save"')
        ->toContain('x-on:click="ping()"');
});

it('gives a modal trigger button its own alpine scope', function () {
    $html = Blade::render('<x-avian::button modal="create-user">New user</x-avian::button>');

    expect($html)->toContain('x-data="{}"')
        ->toContain('x-on:click="$dispatch(&#039;aui-modal-open&#039;')
        ->toContain('create-user');
});

it('lets a caller override the modal trigger handler', function () {
    expect(Blade::render('<x-avian::button modal="x" x-on:click="custom()">Go</x-avian::button>'))
        ->toContain('x-on:click="custom()"')
        ->not->toContain('aui-modal-open');
});

it('renders an icon-only button with an accessible label and no visible text', function () {
    $html = Blade::render('<x-avian::button icon="fas fa-pen" icon-only label="Edit">Edit</x-avian::button>');

    expect($html)->toContain('aui-btn-icon')
        ->toContain('aria-label="Edit"')
        ->toContain('<i class="fas fa-pen"')
        ->not->toContain('>Edit<');
});

it('keeps a regular button free of icon-only markup', function () {
    $html = Blade::render('<x-avian::button icon="fas fa-pen">Edit</x-avian::button>');

    expect($html)->not->toContain('aui-btn-icon')
        ->not->toContain('aria-label')
        ->toContain('Edit');
});

it('renders a card with a title, actions and footer slots', function () {
    $html = Blade::render(<<<'BLADE'
        <x-avian::card title="Team" subtitle="Active members">
            <x-slot:actions><button>Add</button></x-slot:actions>
            Body content
            <x-slot:footer>Footer content</x-slot:footer>
        </x-avian::card>
    BLADE);

    expect($html)->toContain('class="aui-card"')
        ->toContain('<h2 class="aui-card-title">Team</h2>')
        ->toContain('<p class="aui-card-subtitle">Active members</p>')
        ->toContain('class="aui-card-actions"')
        ->toContain('class="aui-card-body"')
        ->toContain('Body content')
        ->toContain('class="aui-card-footer"');
});

it('renders a card without a header when nothing fills it', function () {
    expect(Blade::render('<x-avian::card>Plain</x-avian::card>'))
        ->toContain('class="aui-card-body"')
        ->not->toContain('aui-card-header');
});

it('renders a badge with a variant', function () {
    expect(Blade::render('<x-avian::badge variant="success" dot>Complete</x-avian::badge>'))
        ->toContain('aui-badge aui-badge-success aui-badge-dot')
        ->toContain('Complete');
});

it('renders an alert with its default icon', function () {
    $html = Blade::render('<x-avian::alert variant="danger" title="Failed">Something broke.</x-avian::alert>');

    expect($html)->toContain('aui-alert aui-alert-danger')
        ->toContain('role="alert"')
        ->toContain('fa-circle-exclamation')
        ->toContain('<p class="aui-alert-title">Failed</p>')
        ->toContain('Something broke.');
});

it('wires a dismissible alert to alpine', function () {
    $html = Blade::render('<x-avian::alert variant="info" dismissible>Heads up.</x-avian::alert>');

    expect($html)->toContain('x-data="auiDismiss()"')
        ->toContain('x-show="visible"')
        ->toContain('x-on:click="dismiss()"');
});

it('renders a table with headers and rows', function () {
    $html = Blade::render('<x-avian::table :headers="[\'Name\', \'Role\']"><tr><td>Ada</td></tr></x-avian::table>');

    expect($html)->toContain('class="aui-table-wrap"')
        ->toContain('aui-table aui-table-hover')
        ->toContain('<th>Name</th>')
        ->toContain('<th>Role</th>')
        ->toContain('<td>Ada</td>');
});

it('renders pagination links for a length-aware paginator', function () {
    $paginator = new LengthAwarePaginator(
        items: ['Ada', 'Grace'],
        total: 42,
        perPage: 2,
        currentPage: 3,
        options: ['path' => '/users', 'pageName' => 'page'],
    );

    $html = Blade::render('<x-avian::pagination :paginator="$paginator" />', ['paginator' => $paginator]);

    expect($html)->toContain('class="aui-pagination"')
        ->toContain('Showing')
        ->toContain('<span class="aui-pagination-summary-strong">5</span>')
        ->toContain('<span class="aui-pagination-summary-strong">6</span>')
        ->toContain('<span class="aui-pagination-summary-strong">42</span>')
        ->toContain('href="/users?page=2"')
        ->toContain('href="/users?page=4"')
        ->toContain('aui-pagination-link-active" aria-current="page">3<');
});

it('disables the previous link on the first page and the next link on the last page', function () {
    $firstPage = new LengthAwarePaginator(
        items: ['Ada'],
        total: 25,
        perPage: 10,
        currentPage: 1,
        options: ['path' => '/users', 'pageName' => 'page'],
    );

    expect(Blade::render('<x-avian::pagination :paginator="$paginator" />', ['paginator' => $firstPage]))
        ->toContain('aui-pagination-link-disabled')
        ->toContain('rel="next"')
        ->not->toContain('rel="prev"');

    $lastPage = new LengthAwarePaginator(
        items: ['Ada'],
        total: 25,
        perPage: 10,
        currentPage: 3,
        options: ['path' => '/users', 'pageName' => 'page'],
    );

    expect(Blade::render('<x-avian::pagination :paginator="$paginator" />', ['paginator' => $lastPage]))
        ->toContain('aui-pagination-link-disabled')
        ->toContain('rel="prev"')
        ->not->toContain('rel="next"');
});

it('renders nothing for a paginator without extra pages', function () {
    $paginator = new LengthAwarePaginator(
        items: ['Ada'],
        total: 1,
        perPage: 10,
        currentPage: 1,
    );

    expect(trim(Blade::render('<x-avian::pagination :paginator="$paginator" />', ['paginator' => $paginator])))->toBe('');
    expect(trim(Blade::render('<x-avian::pagination :paginator="null" />')))->toBe('');
});

it('renders a table with its paginator links underneath', function () {
    $paginator = new LengthAwarePaginator(
        items: ['Ada'],
        total: 20,
        perPage: 1,
        currentPage: 1,
        options: ['path' => '/users', 'pageName' => 'page'],
    );

    $html = Blade::render(
        '<x-avian::table :headers="[\'Name\']" :paginator="$paginator"><tr><td>Ada</td></tr></x-avian::table>',
        ['paginator' => $paginator],
    );

    expect($html)->toContain('class="aui-table-wrap"')
        ->toContain('class="aui-pagination"');
});

it('renders a modal wired to its alpine component', function () {
    $html = Blade::render('<x-avian::modal name="edit-user" title="Edit user" size="lg">Body</x-avian::modal>');

    expect($html)->toContain('x-data="auiModal(JSON.parse(')
        ->toContain('closeOnEscape')
        ->toContain('closeOnOverlay')
        ->toContain('x-on:keydown.escape.window="escape()"')
        ->toContain('x-on:click="overlay($event)"')
        ->toContain('aui-modal aui-modal-lg')
        ->toContain('data-modal="edit-user"')
        ->toContain('aria-modal="true"');
});

it('renders a dropdown with a default trigger and items', function () {
    $html = Blade::render(<<<'BLADE'
        <x-avian::dropdown label="Actions" align="right">
            <x-avian::dropdown.item href="/edit" icon="fas fa-pen">Edit</x-avian::dropdown.item>
            <x-avian::dropdown.item danger>Delete</x-avian::dropdown.item>
        </x-avian::dropdown>
    BLADE);

    expect($html)->toContain("x-data=\"auiDropdown({ align: 'right' })\"")
        ->toContain('x-ref="trigger"')
        ->toContain('x-ref="menu"')
        ->toContain('x-on:click.outside="hide()"')
        ->toContain('aui-dropdown-menu aui-dropdown-menu-right')
        ->toContain('Actions')
        ->toContain('aui-dropdown-item')
        ->toContain('aui-dropdown-item-danger')
        ->toContain('href="/edit"');
});

it('renders tabs with the first tab active by default', function () {
    $html = Blade::render(<<<'BLADE'
        <x-avian::tabs :tabs="['profile' => 'Profile', 'security' => 'Security']">
            <x-avian::tabs.panel name="profile">Profile panel</x-avian::tabs.panel>
        </x-avian::tabs>
    BLADE);

    expect($html)->toContain('auiTabs({ active: \'profile\' })')
        ->toContain('role="tablist"')
        ->toContain('x-on:click="select(\'security\')"')
        ->toContain('class="aui-tab-panel"')
        ->toContain('x-show="isActive(\'profile\')"');
});

it('defaults to the plain underlined tab list with no variant class', function () {
    expect(Blade::render('<x-avian::tabs :tabs="[\'a\' => \'A\']" />'))
        ->toContain('class="aui-tabs"')
        ->not->toContain('aui-tabs-line');
});

it('renders pill and segmented tab variants', function () {
    expect(Blade::render('<x-avian::tabs :tabs="[\'a\' => \'A\']" variant="pill" />'))
        ->toContain('class="aui-tabs aui-tabs-pill"');

    expect(Blade::render('<x-avian::tabs :tabs="[\'a\' => \'A\']" variant="segmented" />'))
        ->toContain('class="aui-tabs aui-tabs-segmented"');
});

it('renders a progress bar clamped to a percentage', function () {
    expect(Blade::render('<x-avian::progress :value="30" :max="60" label="Upload" show-value />'))
        ->toContain('width: 50%')
        ->toContain('aria-valuenow="50"')
        ->toContain('Upload')
        ->toContain('50%');
});

it('clamps progress values outside the range', function () {
    expect(Blade::render('<x-avian::progress :value="900" />'))->toContain('width: 100%');
    expect(Blade::render('<x-avian::progress :value="-5" />'))->toContain('width: 0%');
});

it('renders an avatar with derived initials', function () {
    expect(Blade::render('<x-avian::avatar name="Ada Lovelace" size="lg" />'))
        ->toContain('aui-avatar aui-avatar-lg')
        ->toContain('AL');
});

it('renders an avatar image when a source is given', function () {
    expect(Blade::render('<x-avian::avatar src="/me.png" name="Ada" />'))
        ->toContain('<img src="/me.png" alt="Ada">');
});

it('renders an empty state', function () {
    expect(Blade::render('<x-avian::empty title="No records" text="Try another filter." />'))
        ->toContain('class="aui-empty"')
        ->toContain('No records')
        ->toContain('Try another filter.');
});

it('renders a page header with action slot', function () {
    $html = Blade::render(<<<'BLADE'
        <x-avian::page-header title="Dashboard" subtitle="Today">
            <x-slot:actions><span>Export</span></x-slot:actions>
        </x-avian::page-header>
    BLADE);

    expect($html)->toContain('<h1 class="aui-page-title">Dashboard</h1>')
        ->toContain('<p class="aui-page-subtitle">Today</p>')
        ->toContain('class="aui-page-actions"')
        ->toContain('Export');
});

it('renders a spinner', function () {
    expect(Blade::render('<x-avian::spinner size="lg" />'))->toContain('aui-spinner aui-spinner-lg');
});

it('renders the stylesheet tags with a cache busted url', function () {
    $html = Blade::render('<x-avian::styles />');

    expect($html)->toContain('avian-ui/css/avian-ui.css?id=')
        ->toContain('avian-ui/css/avian-ui-themes.css?id=');
});

it('omits the theme stylesheet when themes are turned off', function () {
    config()->set('avian-ui.assets.themes', false);

    expect(Blade::render('<x-avian::styles />'))->not->toContain('avian-ui-themes.css');
});

it('renders the script tag deferred', function () {
    expect(Blade::render('<x-avian::scripts />'))
        ->toContain('avian-ui/js/avian-ui.js?id=')
        ->toContain('defer');
});
