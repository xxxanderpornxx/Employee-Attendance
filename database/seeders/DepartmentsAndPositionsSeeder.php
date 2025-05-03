<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentsAndPositionsSeeder extends Seeder
{
    public function run()
    {


        // Sample positions
        $positions = [
            ['PositionName' => 'Manager', 'created_at' => now(), 'updated_at' => now()],
            ['PositionName' => 'Assistant Manager', 'created_at' => now(), 'updated_at' => now()],
            ['PositionName' => 'Team Lead', 'created_at' => now(), 'updated_at' => now()],
            ['PositionName' => 'Senior Staff', 'created_at' => now(), 'updated_at' => now()],
            ['PositionName' => 'Junior Staff', 'created_at' => now(), 'updated_at' => now()],
            ['PositionName' => 'Intern', 'created_at' => now(), 'updated_at' => now()],
            ['PositionName' => 'Consultant', 'created_at' => now(), 'updated_at' => now()],
            ['PositionName' => 'Analyst', 'created_at' => now(), 'updated_at' => now()],
            ['PositionName' => 'Specialist', 'created_at' => now(), 'updated_at' => now()],
            ['PositionName' => 'Coordinator', 'created_at' => now(), 'updated_at' => now()],
        ];



        // Insert data into the positions table
        DB::table('emppositions')->insert($positions);

        echo "Departments and positions seeded successfully.\n";
    }
}
