<?php

declare(strict_types=1);

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Blade;

it('renders a datalist in list view with its toggle by default', function () {
    $html = Blade::render('<x-avian::datalist><x-avian::datalist.item title="Ada" /></x-avian::datalist>');

    expect($html)->toContain('x-data="auiDatalist({ view: \'list\', persist: null })"')
        ->toContain('x-modelable="view"')
        ->toContain('aui-datalist-items aui-datalist-cols-3 aui-datalist-list')
        ->toContain('class="aui-datalist-toggle"')
        ->toContain('aria-label="List view"')
        ->toContain('aria-label="Grid view"')
        ->toContain('x-on:click="set(\'grid\')"')
        ->toContain('class="aui-datalist-item"');
});

it('renders a datalist in grid view with the requested column count', function () {
    $html = Blade::render('<x-avian::datalist view="grid" :columns="4" persist="products"><x-avian::datalist.item title="Paint" /></x-avian::datalist>');

    expect($html)->toContain('view: \'grid\', persist: \'products\'')
        ->toContain('aui-datalist-items aui-datalist-cols-4 aui-datalist-grid')
        ->not->toContain('aui-datalist-cols-4 aui-datalist-list');
});

it('clamps the datalist column count and falls back to list view for unknown views', function () {
    $html = Blade::render('<x-avian::datalist view="table" :columns="9"><x-avian::datalist.item title="Ada" /></x-avian::datalist>');

    expect($html)->toContain('aui-datalist-items aui-datalist-cols-4 aui-datalist-list');
});

it('hides the datalist toggle when turned off and renders a toolbar slot', function () {
    $withoutToggle = Blade::render('<x-avian::datalist :toggle="false"><x-avian::datalist.item title="Ada" /></x-avian::datalist>');
    $withToolbar = Blade::render('<x-avian::datalist><x-slot:toolbar><span>42 products</span></x-slot:toolbar><x-avian::datalist.item title="Ada" /></x-avian::datalist>');

    expect($withoutToggle)->not->toContain('aui-datalist-toggle')
        ->not->toContain('aui-datalist-toolbar')
        ->and($withToolbar)->toContain('<span>42 products</span>')
        ->toContain('class="aui-datalist-toggle"');
});

it('binds wire:model on the datalist root for the view', function () {
    $html = Blade::render('<x-avian::datalist wire:model.live="view" class="mt-4"><x-avian::datalist.item title="Ada" /></x-avian::datalist>');

    expect($html)->toContain('wire:model.live="view"')
        ->toContain('class="aui-datalist mt-4"');
});

it('renders an empty state when a datalist has no items', function () {
    $default = Blade::render('<x-avian::datalist>@foreach ([] as $item)<x-avian::datalist.item :title="$item" />@endforeach</x-avian::datalist>');
    $custom = Blade::render('<x-avian::datalist empty="No products yet" empty-text="Add one to get started." empty-icon="fas fa-box"></x-avian::datalist>');
    $slot = Blade::render('<x-avian::datalist><x-slot:empty><p>Nothing here</p></x-slot:empty></x-avian::datalist>');
    $disabled = Blade::render('<x-avian::datalist :empty="false"></x-avian::datalist>');

    expect($default)->toContain('class="aui-datalist-empty"')
        ->toContain('No data found')
        ->not->toContain('aui-datalist-items')
        ->and($custom)->toContain('No products yet')
        ->toContain('Add one to get started.')
        ->toContain('fas fa-box')
        ->and($slot)->toContain('<p>Nothing here</p>')
        ->not->toContain('class="aui-empty"')
        ->and($disabled)->not->toContain('aui-datalist-empty');
});

it('renders a datalist with its paginator links underneath', function () {
    $paginator = new LengthAwarePaginator(
        items: ['Ada'],
        total: 20,
        perPage: 1,
        currentPage: 1,
        options: ['path' => '/users', 'pageName' => 'page'],
    );

    $html = Blade::render(
        '<x-avian::datalist :paginator="$paginator"><x-avian::datalist.item title="Ada" /></x-avian::datalist>',
        ['paginator' => $paginator],
    );

    expect($html)->toContain('class="aui-pagination"')
        ->toContain('href="/users?page=2"');
});

it('renders a datalist item with media, text, meta and actions', function () {
    $html = Blade::render(<<<'BLADE'
        <x-avian::datalist.item title="Wall paint" subtitle="SKU A-100" image="/img/paint.jpg">
            Weather-proof exterior paint.
            <x-slot:meta><span>Rp 450.000</span></x-slot:meta>
            <x-slot:actions><button>Edit</button></x-slot:actions>
        </x-avian::datalist.item>
        BLADE);

    expect($html)->toContain('<img src="/img/paint.jpg" alt="Wall paint" loading="lazy">')
        ->toContain('<p class="aui-datalist-title">')
        ->toContain('<p class="aui-datalist-subtitle">SKU A-100</p>')
        ->toContain('Weather-proof exterior paint.')
        ->toContain('<div class="aui-datalist-meta"><span>Rp 450.000</span></div>')
        ->toContain('<div class="aui-datalist-actions"><button>Edit</button></div>');
});

it('renders a datalist item icon, or a custom media slot', function () {
    $icon = Blade::render('<x-avian::datalist.item title="Contract" icon="fas fa-file-pdf" />');
    $media = Blade::render('<x-avian::datalist.item title="Ada"><x-slot:media><x-avian::avatar name="Ada Lovelace" /></x-slot:media></x-avian::datalist.item>');
    $bare = Blade::render('<x-avian::datalist.item title="Ada" />');

    expect($icon)->toContain('<i class="fas fa-file-pdf" aria-hidden="true"></i>')
        ->and($media)->toContain('class="aui-avatar"')
        ->and($bare)->not->toContain('aui-datalist-media')
        ->not->toContain('aui-datalist-aside');
});

it('turns a datalist item title into a stretched link when given an href', function () {
    $html = Blade::render('<x-avian::datalist.item title="Ada" href="/users/1" navigate />');

    expect($html)->toContain('class="aui-datalist-item aui-datalist-item-link"')
        ->toMatch('/<a href="\/users\/1"\s+wire:navigate\s*>Ada<\/a>/');
});
