<?php

namespace App\Http\Controllers;

use App\Http\Resources\EmployeeResource;
use App\Http\Resources\ServiceResource;
use App\Models\Employee;
use App\Models\Service;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __invoke(Service $service, Employee $employee)
    {
        return inertia()->render('Checkout', [
            'employee' => EmployeeResource::make($employee),
            'service' => ServiceResource::make($service),
        ]);
    }
}
