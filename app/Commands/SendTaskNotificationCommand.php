<?php

declare(strict_types=1);

namespace App\Commands;

use App\Builders\TerminalNotifierBuilder;
use App\Repositories\EntryRepository;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

final class SendTaskNotificationCommand extends Command
{
    protected $signature = 'send-notification';

    protected $description = 'Sends a desktop notification for task every 5 minute';

    /**
     * Execute the console command.
     */
    public function handle(EntryRepository $repository): void
    {
        $currentEntry = $repository->getCurrentEntry();

        if (!empty($currentEntry)) {
            TerminalNotifierBuilder::make()
                ->setTitle($currentEntry->task)
                ->setMessage($currentEntry->time)
                ->setSound()
                ->exec();
        }
    }

    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        $schedule->command(self::class)->everyMinute();
    }
}
