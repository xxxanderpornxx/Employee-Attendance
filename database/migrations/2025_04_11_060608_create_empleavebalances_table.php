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
            $table->unsignedBigInteger('EmployeeID'); // Foreign Key

            $table->integer('VacationLeave')->default(0); // Number of VL days left
            $table->integer('SickLeave')->default(0);     // Number of SL days left
            $table->text('Reason')->nullable();           // Optional explanation
            $table->enum('Status', ['Excused', 'Unexcused'])->default('Excused');

            $table->timestamps();

            // Foreign Key Constraint
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