<?php

declare(strict_types=1);

use AvianUi\AvianUi\AvianUi;

it('normalizes bracket field names into validation keys', function (string $name, string $expected) {
    expect(app(AvianUi::class)->fieldKey($name))->toBe($expected);
})->with([
    ['email', 'email'],
    ['user[name]', 'user.name'],
    ['user[address][city]', 'user.address.city'],
    ['tags[]', 'tags'],
    ['items.0.qty', 'items.0.qty'],
]);

it('resolves packaged asset paths', function () {
    expect(app(AvianUi::class)->assetPath('css/avian-ui.css'))->toBeString()
        ->and(app(AvianUi::class)->assetPath('js/avian-ui.js'))->toBeString();
});

it('refuses asset paths outside the packaged css and js directories', function (string $path) {
    expect(app(AvianUi::class)->assetPath($path))->toBeNull();
})->with([
    '../composer.json',
    'css/../../composer.json',
    'css/missing.css',
    'php/shell.php',
    'css/avian-ui.js',
]);

it('builds asset urls from the package route by default', function () {
    expect(app(AvianUi::class)->styleUrl())->toStartWith('http://localhost/avian-ui/css/avian-ui.css?id=');
});

it('builds asset urls from the published path when the route is disabled', function () {
    config()->set('avian-ui.assets.route', false);

    expect(app(AvianUi::class)->scriptUrl())->toStartWith('http://localhost/vendor/avian-ui/js/avian-ui.js?id=');
});

it('builds asset urls from an absolute base url when one is configured', function () {
    config()->set('avian-ui.assets.url', 'https://assets.example.com/ui/');

    expect(app(AvianUi::class)->styleUrl())->toStartWith('https://assets.example.com/ui/css/avian-ui.css?id=');
});

it('versions asset urls so browsers refetch changed files', function () {
    expect(app(AvianUi::class)->version())->toHaveLength(12);
});

it('returns no validation message when nothing is bound', function () {
    expect(app(AvianUi::class)->errorFor('email'))->toBeNull()
        ->and(app(AvianUi::class)->errorFor(null))->toBeNull();
});

it('reads flashed status messages as toasts', function () {
    session()->flash('success', 'Order saved.');
    session()->flash('error', 'Payment failed.');
    session()->flash('warning', 'Low stock.');
    session()->flash('info', 'New version.');

    expect(app(AvianUi::class)->flashedToasts())->toBe([
        ['variant' => 'success', 'message' => 'Order saved.', 'title' => null],
        ['variant' => 'danger', 'message' => 'Payment failed.', 'title' => null],
        ['variant' => 'warning', 'message' => 'Low stock.', 'title' => null],
        ['variant' => 'info', 'message' => 'New version.', 'title' => null],
    ]);
});

it('reads a flashed toast as a message, an options array or a list of them', function (mixed $flashed, array $expected) {
    session()->flash('toast', $flashed);

    expect(app(AvianUi::class)->flashedToasts())->toBe($expected);
})->with([
    'message' => ['Saved.', [['variant' => 'success', 'message' => 'Saved.', 'title' => null]]],
    'options' => [
        ['variant' => 'warning', 'title' => 'Heads up', 'message' => 'Stock is low.'],
        [['variant' => 'warning', 'message' => 'Stock is low.', 'title' => 'Heads up']],
    ],
    'list' => [
        ['First.', ['message' => 'Second.', 'variant' => 'info']],
        [
            ['variant' => 'success', 'message' => 'First.', 'title' => null],
            ['variant' => 'info', 'message' => 'Second.', 'title' => null],
        ],
    ],
]);

it('skips flashed toasts without a usable message', function () {
    session()->flash('success', '');
    session()->flash('error', ['not', 'a', 'string']);
    session()->flash('toast', ['title' => 'No message']);

    expect(app(AvianUi::class)->flashedToasts())->toBe([]);
});
