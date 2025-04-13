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
        Schema::create('employeeshifts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('EmployeeID'); // Foreign key for Employee
            $table->unsignedBigInteger('ShiftID'); // Foreign key for Shift
            $table->timestamps();

            // Adding foreign key constraints
            $table->foreign('EmployeeID')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('ShiftID')->references('id')->on('shifts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employeeshifts');
    }
};