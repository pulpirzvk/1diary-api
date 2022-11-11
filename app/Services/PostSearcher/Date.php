<?php

namespace App\Services\PostSearcher;

use Carbon\Carbon;

class Date
{
    protected Carbon $date;

    public function __construct(Carbon $date)
    {
        $this->date = $date;
    }

    public static function make(Carbon|string|null $date): ?Date
    {
        if (is_string($date) && preg_match('/\d{4}-\d{2}-\d{2}.*/', $date)) {
            $date = Carbon::createFromFormat('Y-m-d', substr($date, 0, 10));
        }

        if ($date instanceof Carbon) {
            return new Date($date);
        }

        return null;
    }

    public function getStartOfDayTimestamp(): int
    {
        return $this->date->startOfDay()->getTimestamp();
    }

    public function getEndOfDayTimestamp(): int
    {
        return $this->date->endOfDay()->getTimestamp();
    }
}
