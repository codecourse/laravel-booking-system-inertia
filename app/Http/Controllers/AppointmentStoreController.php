<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentStoreRequest;
use App\Models\Appointment;
use App\Models\Employee;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppointmentStoreController extends Controller
{
    public function __invoke(AppointmentStoreRequest $request)
    {
        $employee = Employee::find($request->employee_id);
        $service = Service::find($request->service_id);

        Appointment::create($request->only(['employee_id', 'service_id', 'name', 'email']) + [
            'starts_at' => $date = Carbon::parse($request->datetime),
            'ends_at' => $date->copy()->addMinutes($service->duration),
        ]);

        return back();
    }
}
