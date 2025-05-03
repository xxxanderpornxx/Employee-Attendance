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
        Schema::create('empleavebalances', function (Blueprint $table) {
            $table->id(); // EmpLeaveBalanceID
            $table->unsignedBigInteger('EmployeeID');   // Foreign Key
            $table->decimal('VacationLeave', 5, 2)->default(0.00);
            $table->decimal('SickLeave', 5, 2)->default(0.00);
            $table->timestamps();
            $table->foreign('EmployeeID')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleavebalances');
    }
};
