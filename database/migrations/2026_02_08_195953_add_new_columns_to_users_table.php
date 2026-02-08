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
        Schema::table('users', function (Blueprint $table) {
            $table->string('dui')->unique()->after('lastname');
            $table->date('birth_date')->after('dui');
            $table->string('phone_number')->unique()->nullable()->after('email');
            $table->date('hiring_date')->after('phone_number');  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('dui');
            $table->dropColumn('phone_number');
            $table->dropColumn('birth_date');
            $table->dropColumn('hiring_date');
        });
    }
};
