<?php

declare(strict_types=1);

namespace AvianUi\AvianUi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \AvianUi\AvianUi\AvianUi
 */
class AvianUi extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \AvianUi\AvianUi\AvianUi::class;
    }
}
