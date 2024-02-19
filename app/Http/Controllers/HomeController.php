<?php

namespace App\Http\Controllers;

use App\Http\Resources\EmployeeResource;
use App\Http\Resources\ServiceResource;
use App\Models\Employee;
use App\Models\Service;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke()
    {
        return inertia()->render('Home', [
            'employees' => EmployeeResource::collection(
                Employee::orderBy('name', 'asc')->get()
            ),
            'services' => ServiceResource::collection(
                Service::get()
            ),
        ]);
    }
}
