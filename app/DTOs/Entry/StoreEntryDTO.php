<?php

declare(strict_types=1);

namespace App\DTOs\Entry;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Dto;

final class StoreEntryDTO extends Dto
{
    public CarbonImmutable $starts_at;
    public CarbonImmutable $ends_at;

    public function __construct(public string $task)
    {
        $this->starts_at = CarbonImmutable::now(config('app.timezone'))->floorMinutes(5);
        $this->ends_at = CarbonImmutable::now(config('app.timezone'))->ceilMinutes(5);
    }
}
