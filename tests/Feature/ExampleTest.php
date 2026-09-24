<?php

declare(strict_types=1);

use AvianUi\AvianUi\AvianUi;
use AvianUi\AvianUi\AvianUiServiceProvider;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

it('resolves the singleton', function () {
    expect(app(AvianUi::class))->toBeInstanceOf(AvianUi::class);
});

it('returns the same instance from the container', function () {
    expect(app(AvianUi::class))->toBe(app(AvianUi::class));
});

it('merges the package config', function () {
    expect(config('avian-ui.prefix'))->toBe('avian')
        ->and(config('avian-ui.assets.route'))->toBeTrue();
});

it('loads the package translations', function () {
    expect(trans('avian-ui::messages.close'))->toBe('Close');
});

it('loads the package views', function () {
    expect(view()->exists('avian-ui::components.button'))->toBeTrue();
});

it('ships no migrations or placeholder resources', function () {
    expect(ServiceProvider::pathsToPublish(AvianUiServiceProvider::class, 'avian-ui-migrations'))->toBe([])
        ->and(Artisan::all())->not->toHaveKey('avian-ui:placeholder');
});

it('registers both the canonical and configured component prefixes', function () {
    expect(Blade::render('<x-avian::badge>Draft</x-avian::badge>'))->toContain('aui-badge')
        ->and(Blade::render('<x-avian-ui::badge>Draft</x-avian-ui::badge>'))->toContain('aui-badge');
});

it('renders nested components under a custom configured prefix', function () {
    // Start from a compiler that knows no prefixes, like an app booted with this config.
    $compiler = app('blade.compiler');
    (new ReflectionProperty($compiler, 'anonymousComponentNamespaces'))->setValue($compiler, []);

    config()->set('avian-ui.prefix', 'ui');
    app()->register(AvianUiServiceProvider::class, true);

    // A unique attribute per render keeps Blade from reusing an earlier compile.
    $fresh = 'data-test="'.uniqid().'"';

    expect(fn () => Blade::render("<x-avian::badge {$fresh}>Draft</x-avian::badge>"))->toThrow(InvalidArgumentException::class);

    $select = Blade::render("<x-ui::searchable-select {$fresh} name=\"role\" label=\"Role\" :options=\"\$options\" />", ['options' => ['a' => 'Admin']]);
    $trail = Blade::render("<x-ui::breadcrumbs {$fresh} :items=\"\$items\" />", ['items' => ['Home' => '/', 'Users' => null]]);

    expect($select)->toContain('<label class="aui-label" for="aui-role">')
        ->toContain('aui-combobox-item')
        ->and($trail)->toContain('href="/"')
        ->toContain('aria-current="page"');
});

it('serves the packaged stylesheet over the asset route', function () {
    $this->get('avian-ui/css/avian-ui.css')
        ->assertSuccessful()
        ->assertHeader('content-type', 'text/css; charset=utf-8');
});

it('serves the packaged script over the asset route', function () {
    $this->get('avian-ui/js/avian-ui.js')
        ->assertSuccessful()
        ->assertHeader('content-type', 'text/javascript; charset=utf-8');
});

it('does not serve files outside the packaged assets', function () {
    $this->get('avian-ui/css/missing.css')->assertNotFound();
});
