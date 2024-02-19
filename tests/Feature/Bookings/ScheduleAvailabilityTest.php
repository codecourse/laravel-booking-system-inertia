<?php

use App\Bookings\ScheduleAvailability;
use App\Models\Employee;
use App\Models\Schedule;
use App\Models\ScheduleExclusion;
use App\Models\Service;
use Carbon\Carbon;

it('lists correct employee availability', function () {
    Carbon::setTestNow(Carbon::parse('1st January 2000'));

    $employee = Employee::factory()
        ->has(Schedule::factory()->state([
            'starts_at' => now()->startOfDay(),
            'ends_at' => now()->addYear()->endOfDay(),
        ]))
        ->create();

    $service = Service::factory()->create([
        'duration' => 30
    ]);

    $availability = (new ScheduleAvailability($employee, $service))
        ->forPeriod(now()->startOfDay(), now()->endOfDay());

    expect($availability->current())
        ->startsAt(now()->setTimeFromTimeString('09:00:00'))
        ->toBeTrue()
        ->endsAt(now()->setTimeFromTimeString('16:30:00'))
        ->toBeTrue();
});

it('accounts for different daily schedule times', function () {
    Carbon::setTestNow(Carbon::parse('Monday January 2000'));

    $employee = Employee::factory()
        ->has(Schedule::factory()->state([
            'starts_at' => now()->startOfDay(),
            'ends_at' => now()->addYear()->endOfDay(),
            'monday_starts_at' => '11:00:00',
            'monday_ends_at' => '16:00:00',
            'tuesday_starts_at' => '09:00:00',
            'tuesday_ends_at' => '17:00:00',
        ]))
        ->create();

    $service = Service::factory()->create([
        'duration' => 30
    ]);

    $availability = (new ScheduleAvailability($employee, $service))
        ->forPeriod(now()->startOfDay(), now()->addDay()->endOfDay());

    expect($availability->current())
        ->startsAt(now()->setTimeFromTimeString('11:00:00'))
        ->toBeTrue()
        ->endsAt(now()->setTimeFromTimeString('15:30:00'))
        ->toBeTrue();

    $availability->next();

    expect($availability->current())
        ->startsAt(now()->addDay()->setTimeFromTimeString('09:00:00'))
        ->toBeTrue()
        ->endsAt(now()->addDay()->setTimeFromTimeString('16:30:00'))
        ->toBeTrue();
});

it('does not show availability for schedule exclusions', function () {
    Carbon::setTestNow(Carbon::parse('1st January 2000'));

    $employee = Employee::factory()
        ->has(Schedule::factory()->state([
            'starts_at' => now()->startOfDay(),
            'ends_at' => now()->addYear()->endOfDay(),
        ]))
        ->has(ScheduleExclusion::factory()->state([
            'starts_at' => now()->addDay()->startOfDay(),
            'ends_at' => now()->addDay()->endOfDay(),
        ]))
        ->has(ScheduleExclusion::factory()->state([
            'starts_at' => now()->setTimeFromTimeString('12:00:00'),
            'ends_at' => now()->setTimeFromTimeString('13:00:00'),
        ]))
        ->create();

    $service = Service::factory()->create([
        'duration' => 30
    ]);

    $availability = (new ScheduleAvailability($employee, $service))
        ->forPeriod(now()->startOfDay(), now()->addDay()->endOfDay());

    expect($availability->current())
        ->startsAt(now()->setTimeFromTimeString('09:00:00'))
        ->toBeTrue()
        ->endsAt(now()->setTimeFromTimeString('11:59:00'))
        ->toBeTrue();

    $availability->next();

    expect($availability->current())
        ->startsAt(now()->setTimeFromTimeString('13:00:00'))
        ->toBeTrue()
        ->endsAt(now()->setTimeFromTimeString('16:30:00'))
        ->toBeTrue();

    $availability->next();

    expect($availability->valid())->toBeFalse();
});

it('only shows availability from the current time with an hour in advanced', function () {
    Carbon::setTestNow(Carbon::parse('1st January 2000 12:15:00'));

    $employee = Employee::factory()
        ->has(Schedule::factory()->state([
            'starts_at' => now()->startOfDay(),
            'ends_at' => now()->addYear()->endOfDay(),
        ]))
        ->create();

    $service = Service::factory()->create([
        'duration' => 30
    ]);

    $availability = (new ScheduleAvailability($employee, $service))
        ->forPeriod(now()->startOfDay(), now()->endOfDay());

    expect($availability->current())
        ->startsAt(now()->setTimeFromTimeString('13:00:00'))
        ->toBeTrue();
});
