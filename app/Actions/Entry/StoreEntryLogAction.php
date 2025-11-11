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
            $entry = new Entry();

            $entry->task = $dto->task;
            $entry->starts_at = $dto->starts_at;
            $entry->ends_at = $dto->ends_at;

            $entry->save();
        });
    }
}
