<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employees;
use App\Models\Empleavebalances;

class EmpleavebalancesSeeder extends Seeder
{
    public function run()
    {
        $employees = Employees::all();

        foreach ($employees as $employee) {
            Empleavebalances::create([
                'EmployeeID' => $employee->id,
                'VacationLeave' => 0.00,
                'SickLeave' => 0.00,
            ]);
        }
    }
}