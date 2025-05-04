<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payrollexports', function (Blueprint $table) {
            $table->id(); // PayrollID
            $table->unsignedBigInteger('EmployeeID');
            $table->date('PayPeriodStart');
            $table->date('PayPeriodEnd');
            $table->decimal('TotalHoursWorked', 8, 2);
            $table->decimal('OvertimeHours', 8, 2);
            $table->integer('LeaveDays');
            $table->date('ExportDate');
            $table->timestamps();

            // Foreign Key Constraint
            $table->foreign('EmployeeID')->references('id')->on('employees')->onDelete('cascade'); // Assumes you have an 'employees' table
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrollexports');
    }
};