<?php

declare(strict_types=1);

use AvianUi\AvianUi\AvianUi;
use Illuminate\Support\Facades\Blade;

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
    expect(view()->exists('avian-ui::placeholder'))->toBeTrue()
        ->and(view()->exists('avian-ui::components.button'))->toBeTrue();
});

it('registers the artisan command', function () {
    $this->artisan('avian-ui:placeholder')
        ->expectsOutputToContain('AvianUi placeholder command executed.')
        ->assertSuccessful();
});

it('registers both the canonical and configured component prefixes', function () {
    expect(Blade::render('<x-avian::badge>Draft</x-avian::badge>'))->toContain('aui-badge')
        ->and(Blade::render('<x-avian-ui::badge>Draft</x-avian-ui::badge>'))->toContain('aui-badge');
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
