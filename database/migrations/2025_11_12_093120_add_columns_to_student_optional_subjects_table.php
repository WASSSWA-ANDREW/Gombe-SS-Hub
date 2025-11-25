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
        Schema::table('student_optional_subjects', function (Blueprint $table) {
            $table->unsignedBigInteger('student_id')->after('id');
            $table->unsignedBigInteger('olevel_subject_id')->nullable()->after('student_id');
            $table->unsignedBigInteger('alevel_subject_id')->nullable()->after('olevel_subject_id');
            $table->enum('level', ['olevel', 'alevel'])->after('alevel_subject_id');
            $table->string('stream')->nullable()->after('level');

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('olevel_subject_id')->references('id')->on('olevel_subjects')->onDelete('cascade');
            $table->foreign('alevel_subject_id')->references('id')->on('alevel_subjects')->onDelete('cascade');

            $table->unique(['student_id', 'olevel_subject_id', 'alevel_subject_id'], 'sos_student_subjects_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_optional_subjects', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropForeign(['olevel_subject_id']);
            $table->dropForeign(['alevel_subject_id']);
            $table->dropUnique('sos_student_subjects_unique');
            $table->dropColumn(['student_id', 'olevel_subject_id', 'alevel_subject_id', 'level', 'stream']);
        });
    }
};
