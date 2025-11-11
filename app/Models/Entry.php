<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $task
 * @property CarbonImmutable $starts_at
 * @property CarbonImmutable $ends_at
 */
final class Entry extends Model
{
    protected $casts = [
        'starts_at' => 'immutable_datetime',
        'ends_at' => 'immutable_datetime',
    ];
}
