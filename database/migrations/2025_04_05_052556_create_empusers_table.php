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
        Schema::create('empusers', function (Blueprint $table) {
            $table->id();
            // $table->string('employee_id')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->enum('role', ['HR', 'Manager', 'Admin']);
            $table->string('password')->nullable(); // Store hashed password
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */

    public function down(): void
    {
        Schema::dropIfExists('empusers'); // Drop the entire table
    }
};
