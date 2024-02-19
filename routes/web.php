<?php

use App\Bookings\ScheduleAvailability;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $availability = (new ScheduleAvailability())
        ->forPeriod(
            now()->startOfDay(),
            now()->addMonth()->endOfDay(),
        );
});
