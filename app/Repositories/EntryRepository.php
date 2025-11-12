<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Entry;
use App\ViewModels\Entry\EntryViewModel;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

final class EntryRepository
{
    public function getCurrentEntry(): ?EntryViewModel
    {
        $now = CarbonImmutable::now(config('app.timezone'));
        $startsAt = $now->floorMinutes(5);
        $endsAt = $now->ceilMinutes(5);
        $entry = Entry::query()
            ->where('starts_at', '=', $startsAt)
            ->where('ends_at', '=', $endsAt)
            ->first();

        if (empty($entry)) {
            return null;
        }

        return EntryViewModel::fromModel($entry);
    }

    /**
     * @return EntryViewModel[]
     */
    public function getTimeSlotsWithTasks(): array
    {
        $results = DB::select(
            <<<EOF
WITH RECURSIVE time_slots AS (
    -- Base case: start from the beginning of current hour
    SELECT
        datetime('now', 'localtime', 'start of day',
                 '+' || strftime('%H', 'now', 'localtime') || ' hours') as start_time,
        datetime('now', 'localtime', 'start of day',
                 '+' || strftime('%H', 'now', 'localtime') || ' hours', '+5 minutes') as end_time

    UNION ALL

    -- Recursive case: add 5 minutes to each interval
    SELECT
        datetime(start_time, '+5 minutes'),
        datetime(end_time, '+5 minutes')
    FROM time_slots
    WHERE start_time < datetime('now', 'localtime', 'start of day',
                                '+' || strftime('%H', 'now', 'localtime') || ' hours', '+55 minutes')
)
SELECT
    strftime('%H:%M:%S', ts.start_time) || ' - ' || strftime('%H:%M:%S', ts.end_time) as time,
    COALESCE(e.task, '') as task,
    CASE
        WHEN ts.start_time >= datetime('now', 'localtime') THEN 1
        ELSE 0
        END as is_future,
    CASE
        WHEN datetime('now', 'localtime') >= ts.start_time
            AND datetime('now', 'localtime') < ts.end_time THEN 1
        ELSE 0
        END as is_current
FROM time_slots ts
         LEFT JOIN entries e
                   ON time(e.starts_at) = time(ts.start_time)
                       AND time(e.ends_at) = time(ts.end_time)
                       AND e.starts_at >= datetime('now', 'localtime', 'start of day')
ORDER BY ts.start_time;
EOF
        );

        return array_map(fn ($item) => EntryViewModel::fromStdClass($item), $results);
    }
}
