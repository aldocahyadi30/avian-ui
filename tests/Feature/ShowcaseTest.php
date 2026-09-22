<?php

declare(strict_types=1);

use function Orchestra\Testbench\workbench_path;

it('renders every component together on the workbench showcase', function () {
    $html = view()->file(workbench_path('resources/views/showcase.blade.php'))->render();

    expect($html)->toContain('aui-page-title')
        ->toContain('aui-btn-primary')
        ->toContain('aui-badge-success')
        ->toContain('aui-form-grid')
        ->toContain('aui-table')
        ->toContain('auiModal(')
        ->toContain('auiTabs(')
        ->toContain('auiDropdown()')
        ->toContain('avian-ui/css/avian-ui.css')
        ->toContain('avian-ui/js/avian-ui.js');
});
