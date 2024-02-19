<?php

use App\Bookings\Date;
use App\Bookings\ServiceSlotAvailability;
use App\Bookings\Slot;
use App\Models\Appointment;
use App\Models\Employee;
use App\Models\Schedule;
use App\Models\Service;
use Carbon\Carbon;

it('shows available time slots for a service', function () {
    Carbon::setTestNow(Carbon::parse('1st January 2000'));

    $employee = Employee::factory()
        ->has(Schedule::factory()->state([
            'starts_at' => now()->startOfDay(),
            'ends_at' => now()->endOfDay(),
        ]))
        ->create();

    $service = Service::factory()->create([
        'duration' => 30
    ]);

    $availablity = (new ServiceSlotAvailability(collect([$employee]), $service))
        ->forPeriod(now()->startOfDay(), now()->endOfDay());

    expect($availablity->first()->date->toDateString())->toEqual(now()->toDateString());
    expect($availablity->first()->slots)->toHaveCount(16);
});

it('lists multiple slots over more than one day', function () {
    Carbon::setTestNow(Carbon::parse('1st January 2000'));

    $employee = Employee::factory()
        ->has(Schedule::factory()->state([
            'starts_at' => now()->startOfDay(),
            'ends_at' => now()->endOfYear(),
        ]))
        ->create();

    $service = Service::factory()->create([
        'duration' => 30
    ]);

    $availablity = (new ServiceSlotAvailability(collect([$employee]), $service))
        ->forPeriod(now()->startOfDay(), now()->addDay()->endOfDay());

    expect($availablity->map(fn ($date) => $date->date->toDateString()))
        ->toContain(
            now()->toDateString(),
            now()->addDay()->toDateString()
        );

    expect($availablity->first()->slots)->toHaveCount(16);
    expect($availablity->get(1)->slots)->toHaveCount(16);
});

it('excludes booked appointments for the employee', function () {
    Carbon::setTestNow(Carbon::parse('1st January 2000'));

    $service = Service::factory()->create([
        'duration' => 30
    ]);

    $employee = Employee::factory()
        ->has(Schedule::factory()->state([
            'starts_at' => now()->startOfDay(),
            'ends_at' => now()->endOfDay(),
        ]))
        ->has(Appointment::factory()->for($service)->state([
            'starts_at' => now()->setTimeFromTimeString('12:00'),
            'ends_at' => now()->setTimeFromTimeString('12:45'),
        ]))
        ->create();

    $availablity = (new ServiceSlotAvailability(collect([$employee]), $service))
        ->forPeriod(now()->startOfDay(), now()->endOfDay());

    $slots = $availablity->map(function (Date $date) {
        return $date->slots->map(fn (Slot $slot) => $slot->time->toTimeString());
    })
        ->flatten()
        ->toArray();

    expect($slots)
        ->toContain('11:30:00')
        ->not->toContain('12:00:00')
        ->not->toContain('12:30:00')
        ->toContain('13:00:00');
});

it('ignores cancelled appointments', function () {
    Carbon::setTestNow(Carbon::parse('1st January 2000'));

    $service = Service::factory()->create([
        'duration' => 30
    ]);

    $employee = Employee::factory()
        ->has(Schedule::factory()->state([
            'starts_at' => now()->startOfDay(),
            'ends_at' => now()->endOfDay(),
        ]))
        ->has(Appointment::factory()->for($service)->state([
            'starts_at' => now()->setTimeFromTimeString('12:00'),
            'ends_at' => now()->setTimeFromTimeString('12:45'),
            'cancelled_at' => now()
        ]))
        ->create();

    $availablity = (new ServiceSlotAvailability(collect([$employee]), $service))
        ->forPeriod(now()->startOfDay(), now()->endOfDay());

    $slots = $availablity->map(function (Date $date) {
        return $date->slots->map(fn (Slot $slot) => $slot->time->toTimeString());
    })
        ->flatten()
        ->toArray();

    expect($slots)
        ->toContain('11:30:00')
        ->toContain('12:00:00')
        ->toContain('12:30:00')
        ->toContain('13:00:00');
});

it('shows multiple employees available for a service', function () {
    Carbon::setTestNow(Carbon::parse('1st January 2000'));

    $service = Service::factory()->create([
        'duration' => 30
    ]);

    $employees = Employee::factory()
        ->count(2)
        ->has(Schedule::factory()->state([
            'starts_at' => now()->startOfDay(),
            'ends_at' => now()->endOfDay(),
        ]))
        ->create();

    $availablity = (new ServiceSlotAvailability($employees, $service))
        ->forPeriod(now()->startOfDay(), now()->endOfDay());

    expect($availablity->first()->slots->first()->employees)->toHaveCount(2);
});
