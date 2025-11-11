<?php

namespace App\Commands;

use App\Repositories\EntryRepository;
use App\ViewModels\Entry\EntryViewModel;
use Carbon\CarbonImmutable;
use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableCellStyle;
use Symfony\Component\Console\Output\OutputInterface;
use Termwind\Enums\Color;

class ShowScheduleCommand extends Command
{
    protected $signature = 'show';

    protected $description = 'Display the current schedule.';

    /** @var string[] */
    private const array TABLE_HEADERS = ['Time', 'Task'];

    /**
     * Execute the console command.
     */
    public function handle(OutputInterface $output, EntryRepository $repository): void
    {
        $table = new Table($output);

        $table->setStyle('box');
        $table->setHeaders(self::TABLE_HEADERS);
        $table->setRows([...$this->getRows($repository->getTimeSlotsWithTasks())]);

        $table->render();
    }

    /**
     * @param EntryViewModel[] $entries
     * @return array<array<CarbonImmutable, string>>
     */
    private function getRows(array $entries): array
    {
        $rows = [];
        foreach ($entries as $entry) {
            $rows[] = $this->getRow($entry);
        }

        return $rows;
    }

    /**
     * @param EntryViewModel $entry
     * @return array<TableCell, TableCell>
     */
    private function getRow(EntryViewModel $entry): array
    {
        $tableCellStyle = new TableCellStyle(['fg' => Color::GRAY]);
        if ($entry->is_current) {
            $tableCellStyle = new TableCellStyle(['fg' => Color::RED]);
        } elseif ($entry->is_future) {
            $tableCellStyle = new TableCellStyle(['fg' => Color::GREEN]);
        }

        return [
            new TableCell($entry->time, ['style' => $tableCellStyle]),
            new TableCell($entry->task, ['style' => $tableCellStyle]),
        ];
    }
}
