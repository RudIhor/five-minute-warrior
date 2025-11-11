<?php

namespace App\DTOs\Entry;

use App\Providers\AppServiceProvider;
use Carbon\CarbonImmutable;
use Spatie\LaravelData\Dto;

final class StoreEntryDTO extends Dto
{
    public CarbonImmutable $starts_at;
    public CarbonImmutable $ends_at;

    public function __construct(public string $task)
    {
        $this->starts_at = CarbonImmutable::now(AppServiceProvider::TIMEZONE)->floorMinutes(5);
        $this->ends_at = CarbonImmutable::now(AppServiceProvider::TIMEZONE)->ceilMinutes(5);
    }
}
