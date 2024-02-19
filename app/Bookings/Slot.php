<?php

namespace App\Bookings;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class Slot
{
    public Collection $employees;

    public function __construct(public Carbon $time)
    {
        $this->employees = collect();
    }

    public function addEmployee(Employee $employee)
    {
        $this->employees->push($employee);
    }

    public function hasEmployees()
    {
        return $this->employees->count();
    }
}
