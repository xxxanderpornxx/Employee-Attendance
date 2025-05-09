<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('payrollexports', function (Blueprint $table) {
            $table->id(); // PayrollID
            $table->unsignedBigInteger('EmployeeID'); // FK to employees
            $table->date('PayPeriodStart');
            $table->date('PayPeriodEnd');
            $table->integer('DaysWorked')->default(0);
            $table->decimal('OvertimeHours', 8, 2)->default(0.00);
            $table->integer('LeaveDays')->default(0);
            $table->integer('AbsentDays')->default(0);
            $table->integer('LateMinutes')->default(0);
            $table->decimal('GrossPay', 12, 2)->default(0.00);
            $table->decimal('NetPay', 12, 2)->default(0.00);
            $table->date('ExportDate');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('EmployeeID')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payrollexports');
    }
};