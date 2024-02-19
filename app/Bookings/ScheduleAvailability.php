<?php

namespace App\Bookings;

use App\Models\Employee;
use App\Models\Schedule;
use App\Models\ScheduleExclusion;
use App\Models\Service;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Spatie\Period\Boundaries;
use Spatie\Period\Period;
use Spatie\Period\PeriodCollection;
use Spatie\Period\Precision;

class ScheduleAvailability
{
    protected PeriodCollection $periods;

    public function __construct(protected Employee $employee, protected Service $service)
    {
        $this->periods = new PeriodCollection();
    }

    public function forPeriod(Carbon $startsAt, Carbon $endsAt)
    {
        collect(CarbonPeriod::create($startsAt, $endsAt)->days())
            ->each(function ($date) {
                $this->addAvailabilityFromSchedule($date);

                $this->employee->scheduleExclusions->each(function (ScheduleExclusion $exclusion) {
                    $this->subtractScheduleExclusion($exclusion);
                });

                $this->excludeTimePassedToday();
            });

        return $this->periods;
    }

    protected function excludeTimePassedToday()
    {
        $this->periods = $this->periods->subtract(
            Period::make(
                now()->startOfDay(),
                now()->endOfHour(),
                Precision::MINUTE(),
                Boundaries::EXCLUDE_START()
            )
        );
    }

    protected function subtractScheduleExclusion(ScheduleExclusion $exclusion)
    {
        $this->periods = $this->periods->subtract(
            Period::make(
                $exclusion->starts_at,
                $exclusion->ends_at,
                Precision::MINUTE(),
                Boundaries::EXCLUDE_END()
            )
        );
    }

    protected function addAvailabilityFromSchedule(Carbon $date)
    {
        if ($date->lte(now()->subDay())) {
            return;
        }

        if (!$schedule = $this->employee->schedules->where('starts_at', '<=', $date)->where('ends_at', '>=', $date)->first()) {
            return;
        }

        if (![$startsAt, $endsAt] = $schedule->getWorkingHoursForDate($date)) {
            return;
        }

        $this->periods = $this->periods->add(
            Period::make(
                $date->copy()->setTimeFromTimeString($startsAt),
                $date->copy()->setTimeFromTimeString($endsAt)->subMinutes($this->service->duration),
                Precision::MINUTE()
            )
        );
    }
}
