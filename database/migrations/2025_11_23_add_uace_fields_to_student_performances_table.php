<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_performances', function (Blueprint $table) {
            $table->integer('uace_points')->nullable()->after('grade')->comment('UACE points (A=6, B=5, C=4, D=3, E=2, O=1, F=0)');
            $table->enum('subject_category', ['principal', 'subsidiary', 'general'])->nullable()->after('uace_points')->comment('Subject category for UACE grading');
        });
    }

    public function down(): void
    {
        Schema::table('student_performances', function (Blueprint $table) {
            $table->dropColumn(['uace_points', 'subject_category']);
        });
    }
};
