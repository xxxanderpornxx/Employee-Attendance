<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\AttendanceController;

class MarkAbsentEmployees extends Command
{
    // Remove the `string` type declarations
    public $signature = 'attendance:mark-absent';
    public $description = 'Mark employees as absent if they have no check-in record for today';

    public function handle(): int
    {
        try {
            $controller = new AttendanceController();
            $controller->markAbsentEmployees();

            $this->info('Absent employees marked successfully.');
            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Failed to mark absent employees: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
