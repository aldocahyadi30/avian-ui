<?php

declare(strict_types=1);

namespace AvianUi\AvianUi\Tests;

use AvianUi\AvianUi\AvianUiServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            AvianUiServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        // The docs route runs through the "web" middleware, which encrypts cookies.
        $app['config']->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
    }
}
