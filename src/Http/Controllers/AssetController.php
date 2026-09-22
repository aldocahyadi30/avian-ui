<?php

declare(strict_types=1);

namespace AvianUi\AvianUi\Http\Controllers;

use AvianUi\AvianUi\AvianUi;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AssetController
{
    /**
     * Serve a packaged stylesheet or script straight from the package.
     */
    public function __invoke(AvianUi $avianUi, string $path): BinaryFileResponse
    {
        $file = $avianUi->assetPath($path);

        abort_if($file === null, 404);

        return response()->file($file, [
            'Content-Type' => str_ends_with($file, '.css') ? 'text/css' : 'text/javascript',
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    }
}
