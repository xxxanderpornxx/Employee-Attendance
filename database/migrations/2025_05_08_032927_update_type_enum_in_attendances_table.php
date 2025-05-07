<?php
// filepath: c:\IT9aL\Project\EmployeeAttendance\database\migrations\xxxx_xx_xx_xxxxxx_update_type_enum_in_attendances_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTypeEnumInAttendancesTable extends Migration
{
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            // Modify the ENUM column to include 'Auto-marked'
            $table->enum('Type', ['Check-in', 'Check-out', 'Auto-marked'])->change();
        });
    }

    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            // Revert to the original ENUM values
            $table->enum('Type', ['Check-in', 'Check-out'])->change();
        });
    }
}
