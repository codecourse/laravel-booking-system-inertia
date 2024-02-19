<?php

namespace App\Bookings;

use App\Models\Employee;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ServiceSlotAvailability
{
    public function __construct(protected Collection $employees, protected Service $service)
    {
        //
    }

    public function forPeriod(Carbon $startsAt, Carbon $endsAt)
    {
        $range = (new SlotRangeGenerator($startsAt, $endsAt))->generate($this->service->duration);

        $this->employees->each(function (Employee $employee) {
            // get the availability for the employee
            // remove appointments from the period collection
            // add the available employees to the $range
            // remove empty slots
        });
    }
}
