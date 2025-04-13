<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('overtimerequests', function (Blueprint $table) {
            $table->id(); // OvertimeID
            $table->unsignedBigInteger('EmployeeID'); // Foreign Key
            $table->date('Date');
            $table->time('StartTime');
            $table->time('EndTime');
            $table->enum('Status', ['Pending', 'Approved', 'Rejected'])->default('Pending');

            $table->timestamps();

            // Set up foreign key to employees table
            $table->foreign('EmployeeID')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('overtimerequests');
    }
};