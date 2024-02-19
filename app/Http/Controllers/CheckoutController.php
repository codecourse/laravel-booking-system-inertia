<?php

namespace App\Http\Controllers;

use App\Bookings\ServiceSlotAvailability;
use App\Http\Resources\AvailabilityResource;
use App\Http\Resources\EmployeeResource;
use App\Http\Resources\ServiceResource;
use App\Models\Employee;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __invoke(Service $service, Employee $employee, Request $request)
    {
        $availability = (new ServiceSlotAvailability($employee->exists ? collect([$employee]) : Employee::get(), $service))
            ->forPeriod(
                Carbon::createFromDate($request->calendar)->startOfDay(),
                Carbon::createFromDate($request->calendar)->endOfMonth(),
            );

        $availableDates = $availability->hasSlots();

        return inertia()->render('Checkout', [
            'employee' => $employee->exists ? EmployeeResource::make($employee) : null,
            'availability' => AvailabilityResource::collection($availableDates),
            'service' => ServiceResource::make($service),
            'date' => $availability->firstAvailableDate()?->date->toDateString(),
            'calendar' => $request->calendar
        ]);
    }
}
