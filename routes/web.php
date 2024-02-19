<?php

use App\Bookings\ScheduleAvailability;
use App\Bookings\ServiceSlotAvailability;
use App\Bookings\SlotRangeGenerator;
use App\Models\Employee;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

// Carbon::setTestNow(now()->setTimeFromTimeString('12:00'));

Route::get('/', function () {
    $employees = Employee::get();
    $service = Service::find(1);

    $availability = (new ServiceSlotAvailability($employees, $service))
        ->forPeriod(now()->startOfDay(), now()->addDay()->endOfDay());

    // $generator = (new SlotRangeGenerator(now()->startOfDay(), now()->addDay()->endOfDay()));

    // dd($generator->generate(30));

    // $employee = Employee::find(1);
    // $service = Service::find(1);

    // $availability = (new ScheduleAvailability($employee, $service))
    //     ->forPeriod(
    //         now()->startOfDay(),
    //         now()->addMonth()->endOfDay(),
    //     );
});
