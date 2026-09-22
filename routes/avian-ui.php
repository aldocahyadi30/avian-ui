<?php

declare(strict_types=1);

use AvianUi\AvianUi\Http\Controllers\AssetController;
use Illuminate\Support\Facades\Route;

$path = config('avian-ui.assets.path', 'avian-ui');

Route::prefix(is_string($path) && $path !== '' ? $path : 'avian-ui')->group(function (): void {
    Route::get('{path}', AssetController::class)
        ->where('path', '(css|js)/[A-Za-z0-9._-]+')
        ->name('avian-ui.asset');
});
