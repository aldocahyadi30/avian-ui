<?php

declare(strict_types=1);

namespace AvianUi\AvianUi;

use Illuminate\Session\Store;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Illuminate\Support\ViewErrorBag;

class AvianUi
{
    /**
     * The cached asset version identifier.
     */
    protected ?string $version = null;

    /**
     * Get the absolute URL for the component stylesheet.
     */
    public function styleUrl(): string
    {
        return $this->assetUrl('css/avian-ui.css');
    }

    /**
     * Get the absolute URL for the optional theme stylesheet.
     */
    public function themeStyleUrl(): string
    {
        return $this->assetUrl('css/avian-ui-themes.css');
    }

    /**
     * Get the absolute URL for the Alpine behaviour script.
     */
    public function scriptUrl(): string
    {
        return $this->assetUrl('js/avian-ui.js');
    }

    /**
     * Build the absolute URL for a packaged asset.
     */
    public function assetUrl(string $file): string
    {
        $base = $this->assetBase();

        $url = Str::startsWith($base, ['http://', 'https://', '//'])
            ? rtrim($base, '/').'/'.$file
            : asset(trim($base, '/').'/'.$file);

        return $url.'?id='.$this->version();
    }

    /**
     * Get the base path or URL the assets are served from.
     */
    public function assetBase(): string
    {
        $url = config('avian-ui.assets.url');

        if (is_string($url) && $url !== '') {
            return $url;
        }

        if (config('avian-ui.assets.route', true) === false) {
            return 'vendor/avian-ui';
        }

        $path = config('avian-ui.assets.path', 'avian-ui');

        return is_string($path) && $path !== '' ? $path : 'avian-ui';
    }

    /**
     * Get the directory the packaged assets live in.
     */
    public function assetDirectory(): string
    {
        return dirname(__DIR__).'/public';
    }

    /**
     * Resolve a packaged asset to an absolute file path.
     */
    public function assetPath(string $file): ?string
    {
        if (! (bool) preg_match('#^(css/[A-Za-z0-9._-]+\.css|js/[A-Za-z0-9._-]+\.js)$#', $file)) {
            return null;
        }

        $path = $this->assetDirectory().'/'.$file;

        return is_file($path) ? $path : null;
    }

    /**
     * Get a version identifier that changes whenever the assets change.
     */
    public function version(): string
    {
        if ($this->version !== null) {
            return $this->version;
        }

        $stamp = '';

        foreach (['css/avian-ui.css', 'css/avian-ui-themes.css', 'js/avian-ui.js'] as $file) {
            $path = $this->assetPath($file);

            $stamp .= $path === null ? '0' : (string) filemtime($path);
        }

        return $this->version = substr(hash('xxh128', $stamp), 0, 12);
    }

    /**
     * Get the flashed old input for the given field name.
     */
    public function oldValue(?string $name): mixed
    {
        if ($name === null || $name === '') {
            return null;
        }

        $session = app()->bound('session.store') ? app('session.store') : null;

        if (! $session instanceof Store) {
            return null;
        }

        $key = $this->fieldKey($name);

        return $session->hasOldInput($key) ? $session->getOldInput($key) : null;
    }

    /**
     * Get the first validation message for the given field name.
     */
    public function errorFor(?string $name, ?string $bag = null): ?string
    {
        if ($name === null || $name === '') {
            return null;
        }

        $errors = View::shared('errors');

        if (! $errors instanceof ViewErrorBag) {
            return null;
        }

        $bag = $bag === null || $bag === '' ? 'default' : $bag;

        if (! $errors->hasBag($bag)) {
            return null;
        }

        $message = $errors->getBag($bag)->first($this->fieldKey($name));

        return $message === '' ? null : $message;
    }

    /**
     * Normalize an HTML field name into a validation error key.
     */
    public function fieldKey(string $name): string
    {
        return trim(str_replace(['[]', '][', '[', ']'], ['', '.', '.', ''], $name), '.');
    }
}
