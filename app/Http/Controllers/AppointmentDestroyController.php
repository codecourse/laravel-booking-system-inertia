<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentDestroyController extends Controller
{
    public function __invoke(Appointment $appointment)
    {
        $appointment->update([
            'cancelled_at' => now()
        ]);

        return back();
    }
}
