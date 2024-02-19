<?php

namespace App\Bookings;

use App\Models\Appointment;
use App\Models\Employee;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Spatie\Period\Boundaries;
use Spatie\Period\Period;
use Spatie\Period\PeriodCollection;
use Spatie\Period\Precision;

class ServiceSlotAvailability
{
    public function __construct(protected Collection $employees, protected Service $service)
    {
        //
    }

    public function forPeriod(Carbon $startsAt, Carbon $endsAt)
    {
        $range = (new SlotRangeGenerator($startsAt, $endsAt))->generate($this->service->duration);

        $this->employees->each(function (Employee $employee) use ($startsAt, $endsAt, &$range) {
            $periods = (new ScheduleAvailability($employee, $this->service))
                ->forPeriod($startsAt, $endsAt);

            $periods = $this->removeAppointments($periods, $employee);

            foreach ($periods as $period) {
                $this->addAvailableEmployeeForPeriod($range, $period, $employee);
            }

            // remove appointments from the period collection
        });

        $range = $this->removeEmptySlots($range);

        return $range;
    }

    protected function removeAppointments(PeriodCollection $period, Employee $employee)
    {
        $employee->appointments->whereNull('cancelled_at')->each(function (Appointment $appointment) use (&$period) {
            $period = $period->subtract(
                Period::make(
                    $appointment->starts_at->copy()->subMinutes($this->service->duration)->addMinute(),
                    $appointment->ends_at,
                    Precision::MINUTE(),
                    Boundaries::EXCLUDE_ALL()
                )
            );
        });

        return $period;
    }

    protected function removeEmptySlots(Collection $range)
    {
        return $range->filter(function (Date $date) {
            $date->slots = $date->slots->filter(function (Slot $slot) {
                return $slot->hasEmployees();
            });

            return true;
        });
    }

    protected function addAvailableEmployeeForPeriod(Collection $range, Period $period, Employee $employee)
    {
        $range->each(function (Date $date) use ($period, $employee) {
            $date->slots->each(function (Slot $slot) use ($period, $employee) {
                if ($period->contains($slot->time)) {
                    $slot->addEmployee($employee);
                }
            });
        });
    }
}
