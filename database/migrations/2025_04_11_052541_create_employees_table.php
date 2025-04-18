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
            $table->string('FirstName');
            $table->string('MiddleName')->nullable();
            $table->string('LastName');
            $table->enum('Sex', ['Male', 'Female']);
            $table->unsignedBigInteger('PositionID');
            $table->unsignedBigInteger('DepartmentID');
            $table->string('ContactNumber');
            $table->date('HireDate');
            $table->timestamps();

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
