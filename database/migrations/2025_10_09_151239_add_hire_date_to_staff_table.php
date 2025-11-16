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
        Schema::table('staff', function (Blueprint $table) {
            $table->date('hire_date')->nullable()->after('date_of_birth');
            $table->string('role')->default('teacher')->after('staff_type');
            $table->string('gender')->default('Male')->after('sex');
            $table->string('employment_type')->default('Regular')->after('role');
            $table->string('department')->default('General')->after('employment_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn('hire_date');
            $table->dropColumn('role');
            $table->dropColumn('gender');
            $table->dropColumn('employment_type');
            $table->dropColumn('department');
        });
    }
};
