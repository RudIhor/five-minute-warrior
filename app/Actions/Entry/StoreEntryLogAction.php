<?php

namespace App\Actions\Entry;

use App\DTOs\Entry\StoreEntryDTO;
use App\Models\Entry;
use Illuminate\Support\Facades\DB;
use Throwable;

final class StoreEntryLogAction
{
    /**
     * @throws Throwable
     */
    public function run(StoreEntryDTO $dto): void
    {
        DB::transaction(function () use ($dto) {
            $lastEntry = Entry::query()->orderByDesc('ends_at')->first();

            $entry = new Entry();
            $entry->task = $dto->task;
            if (empty($lastEntry) || ($lastEntry->starts_at->isPast() && $lastEntry->ends_at->isPast())) {
                $entry->starts_at = $dto->starts_at;
                $entry->ends_at = $dto->ends_at;
            } else {
                $entry->starts_at = $lastEntry->starts_at->addMinutes(5);
                $entry->ends_at = $lastEntry->ends_at->addMinutes(5);
            }

            $entry->save();
        });
    }
}
