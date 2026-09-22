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
}
