<?php

namespace App\Bookings;

use Illuminate\Support\Collection;

class DateCollection extends Collection
{
    public function firstAvailableDate()
    {
        return $this->first(function (Date $date) {
            return $date->slots->count() >= 1;
        });
    }

    public function hasSlots()
    {
        return $this->filter(function (Date $date) {
            return !$date->slots->isEmpty();
        });
    }
}
