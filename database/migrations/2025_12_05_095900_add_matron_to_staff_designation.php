<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            DB::statement("ALTER TABLE staff MODIFY staff_designation ENUM(
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
                'LIBRARY OFFICER',
                'MATRON',
                'STAFF SECRETARY'
            ) DEFAULT NULL");
        });
    }

    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            DB::statement("ALTER TABLE staff MODIFY staff_designation ENUM(
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
                'LIBRARY OFFICER',
                'MATRON'
            ) DEFAULT NULL");
        });
    }
};
