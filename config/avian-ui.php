<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Blade Component Prefix
    |--------------------------------------------------------------------------
    |
    | Anonymous Blade components ship under this prefix, so a button is written
    | as <x-avian::button>. The canonical <x-avian-ui::button> prefix is always
    | registered as well, which is what the package views use internally.
    |
    */

    'prefix' => 'avian',

    /*
    |--------------------------------------------------------------------------
    | Assets
    |--------------------------------------------------------------------------
    |
    | "route" serves the CSS and JS straight from the package so the library
    | works without a publish or a build step. Set it to false and publish the
    | assets with the "avian-ui-assets" tag to serve them from public/vendor,
    | or point "url" at a bundler output (or any base URL) to take over.
    |
    */

    'assets' => [

        'route' => true,

        'path' => 'avian-ui',

        'url' => null,

        'themes' => true,

    ],

];
