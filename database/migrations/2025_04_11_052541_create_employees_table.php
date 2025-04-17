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
        Schema::create('employees', function (Blueprint $table) {
            $table->id(); // Employee ID (Primary Key)
            $table->string('FirstName'); // First Name
            $table->string('MiddleName')->nullable(); // Middle Name (optional)
            $table->string('LastName'); // Last Name
            $table->enum('Sex', ['Male', 'Female']); // Sex (Male or Female)
            $table->unsignedBigInteger('PositionID'); // Foreign key for Position
            $table->unsignedBigInteger('DepartmentID'); // Foreign key for Department
            $table->string('ContactNumber'); // Contact Number
            $table->date('HireDate'); // Hire Date
            $table->timestamps(); // Created at and updated at timestamps

            // Foreign key constraints
            $table->foreign('PositionID')->references('id')->on('emppositions')->onDelete('cascade');
            $table->foreign('DepartmentID')->references('id')->on('departments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};