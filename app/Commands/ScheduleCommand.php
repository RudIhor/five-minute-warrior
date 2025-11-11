<?php

namespace App\Commands;

use App\Providers\AppServiceProvider;
use Carbon\CarbonImmutable;
use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableCellStyle;
use Symfony\Component\Console\Output\OutputInterface;

class ScheduleCommand extends Command
{
    protected $signature = 'schedule';

    protected $description = 'Display the current schedule.';

    private const int MINUTE_STEP = 5;

    /** @var string[] */
    private const array TABLE_HEADERS = ['Time', 'Task'];

    /**
     * Execute the console command.
     */
    public function handle(OutputInterface $output): void
    {
        $table = new Table($output);

        $table->setStyle('box');
        $table->setHeaders(self::TABLE_HEADERS);
        $table->setRows([...$this->getRows()]);

        $table->render();
    }

    /**
     * @return array<array<CarbonImmutable, string>>
     */
    private function getRows(): array
    {
        $rows = [];
        $startOfHour = CarbonImmutable::now(AppServiceProvider::TIMEZONE)->startOfHour();
        while ($startOfHour->isCurrentHour()) {
            $startOfHour = $startOfHour->addMinutes(self::MINUTE_STEP);
            $rows[] = $this->getRow($startOfHour);
        }

        return $rows;
    }

    /**
     * @param CarbonImmutable $time
     * @return array<TableCell, TableCell>
     */
    private function getRow(CarbonImmutable $time): array
    {
        $now = CarbonImmutable::now(AppServiceProvider::TIMEZONE);
        $timeframe = sprintf(
            '%s - %s',
            $time->subMinutes(self::MINUTE_STEP)->format('H:i:s'),
            $time->format('H:i:s'),
        );

        $timeframeCellStyle = new TableCellStyle(['fg' => 'green']);
        if ($now->gte($time->subMinutes(self::MINUTE_STEP)) && $now->lte($time)) {
            $timeframeCellStyle = new TableCellStyle(['fg' => 'red']);
        }

        return [
            new TableCell($timeframe, ['style' => $timeframeCellStyle]),
            new TableCell('реалізувати додавання логу на тепершню п яти хвилинку', ['style' => new TableCellStyle(['fg' => 'cyan'])]),
        ];
    }
}
