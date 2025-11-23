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
            $table->enum('staff_designation', [
                'HEADMASTER',
                'DEPUTY HEADMASTER-ADMINISTRATION',
                'DEPUTY HEADMASTER-ACADEMICS',
                'DEPUTY HEADMASTER-DISCIPLINE',
                'DIRECTOR OF STUDIES',
                'DEAN OF STUDENTS',
                'SCHOOL-COUNSELOR',
                'DISCIPLINARY OFFICER',
                'CLASS TEACHER',
                'PREFECT PATRON',
                'WARDEN',
                'HEAD OF DEPARTMENT',
                'LAB TECHNICIAN',
                'LIBRARY OFFICER'
            ])->nullable()->after('physical_health');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn('staff_designation');
        });
    }
};
