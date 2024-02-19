<?php

use App\Bookings\ScheduleAvailability;
use App\Models\Employee;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

// Carbon::setTestNow(now()->setTimeFromTimeString('12:00'));

Route::get('/', function () {
    $employee = Employee::find(1);
    $service = Service::find(1);

    $availability = (new ScheduleAvailability($employee, $service))
        ->forPeriod(
            now()->startOfDay(),
            now()->addMonth()->endOfDay(),
        );
});
