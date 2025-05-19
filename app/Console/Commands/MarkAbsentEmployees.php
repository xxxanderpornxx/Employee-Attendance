<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Log;

class MarkAbsentEmployees extends Command
{
    protected $signature = 'attendance:mark-absent';
    protected $description = 'Mark employees as absent if they have no check-in record for today';

 public function handle(): int
{
    try {
        $this->info('Starting to mark absent employees...');

        $controller = new AttendanceController();
        $result = $controller->markAbsentEmployees();

        if ($result['success']) {
            Log::info($result['message']);
            $this->info($result['message']);
            return self::SUCCESS;
        } else {
            throw new \Exception($result['message']);
        }

    } catch (\Exception $e) {
        Log::error('Failed to mark absent employees: ' . $e->getMessage());
        $this->error('Failed to mark absent employees: ' . $e->getMessage());
        return self::FAILURE;
    }
    //php artisan attendance:mark-absent
}
}
