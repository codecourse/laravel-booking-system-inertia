<?php

namespace App\Bookings;

use Carbon\Carbon;

class Slot
{
    public $employees = [];

    public function __construct(public Carbon $time)
    {
        //
    }
}
