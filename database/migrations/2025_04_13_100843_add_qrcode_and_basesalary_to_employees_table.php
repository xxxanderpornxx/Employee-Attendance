<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('QRcode')->nullable()->after('HireDate');
            $table->decimal('BaseSalary', 10, 2)->default(0)->after('QRcode');
            $table->string('Email') ->nullable()->after('BaseSalary');
            $table->date('DateOfBirth')->nullable()->after('Sex');
            $table->string('Address')->nullable()->after('ContactNumber');
        });
    }

    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['QRcode', 'BaseSalary', 'Email', 'DateOfBirth', 'Address']);
        });
    }
};