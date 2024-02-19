<?php

namespace App\Bookings;

use App\Models\Employee;
use Carbon\Carbon;

class Slot
{
    public $employees = [];

    public function __construct(public Carbon $time)
    {
        //
    }

    public function addEmployee(Employee $employee)
    {
        $this->employees[] = $employee;
    }

    public function hasEmployees()
    {
        return !empty($this->employees);
    }
}
