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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id(); //attendanceID
            $table->unsignedBigInteger('EmployeeID');
            $table->enum('Type', ['Check-in', 'Check-out']);
            $table->dateTime('DateTime');
            $table->timestamps();

            $table->foreign('EmployeeID')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};