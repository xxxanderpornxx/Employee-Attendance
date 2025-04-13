<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('leaverequests', function (Blueprint $table) {
            $table->id(); // LeaveID
            $table->unsignedBigInteger('EmployeeID'); // Foreign key

            // ENUM type directly in table
            $table->enum('LeaveType', [
                'Sick Leave',
                'Vacation Leave',
                ]);

            $table->date('StartDate');
            $table->date('EndDate');
            $table->enum('Status', ['Pending', 'Approved', 'Denied'])->default('Pending');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('EmployeeID')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('leaverequests');
    }
};
