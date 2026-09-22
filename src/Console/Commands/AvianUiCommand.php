<?php

declare(strict_types=1);

namespace AvianUi\AvianUi\Console\Commands;

use Illuminate\Console\Command;

class AvianUiCommand extends Command
{
    /**
     * The command signature.
     */
    protected $signature = 'avian-ui:placeholder';

    /**
     * The command description.
     */
    protected $description = 'Placeholder Artisan command shipped by the package avian-ui.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->line('AvianUi placeholder command executed.');

        return self::SUCCESS;
    }
}
