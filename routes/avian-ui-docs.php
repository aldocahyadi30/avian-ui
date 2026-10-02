<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

$path = config('avian-ui.docs.path', 'avian-ui');

Route::view(is_string($path) && $path !== '' ? $path : 'avian-ui', 'avian-ui::docs.index')
    ->name('avian-ui.docs')
    ->middleware(config('avian-ui.docs.middleware', ['web']));
