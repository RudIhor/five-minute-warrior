<?php

declare(strict_types=1);

namespace App\Commands;

use App\Actions\Entry\StoreEntryLogAction;
use App\DTOs\Entry\StoreEntryDTO;
use LaravelZero\Framework\Commands\Command;
use Throwable;

final class AddEntryCommand extends Command
{
    protected $signature = 'add {task}';

    protected $description = 'Add a new entry for the next 5 minutes.';

    /**
     * @throws Throwable
     */
    public function handle(StoreEntryLogAction $action): void
    {
        $action->run(new StoreEntryDTO($this->argument('task')));

        $this->call('show');
    }
}
