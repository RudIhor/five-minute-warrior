<?php

declare(strict_types=1);

namespace App\ViewModels\Entry;

use App\Models\Entry;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use stdClass;

final class EntryViewModel extends Data
{
    public function __construct(
        public string $task,
        public string $time,
        public Optional|int $is_future,
        public Optional|int $is_current,
    ) {
    }

    public static function fromStdClass(stdClass $stdClass): self
    {
        return new self(
            task: $stdClass->task,
            time: $stdClass->time,
            is_future: $stdClass->is_future,
            is_current: $stdClass->is_current,
        );
    }

    public static function fromModel(Entry $entry): self
    {
        return new self(
            task: $entry->task,
            time: $entry->starts_at->format('H:i:s') . ' - ' . $entry->ends_at->format('H:i:s'),
            is_future: Optional::create(),
            is_current: Optional::create(),
        );
    }
}
