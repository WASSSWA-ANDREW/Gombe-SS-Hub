<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marks_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('teacher_subject_id');
            $table->unsignedBigInteger('academics_id')->nullable();
            $table->unsignedBigInteger('olevel_subject_id')->nullable();
            $table->unsignedBigInteger('alevel_subject_id')->nullable();
            $table->enum('level', ['olevel', 'alevel']);
            $table->string('class');
            $table->string('stream')->nullable();
            $table->string('term')->nullable();
            $table->integer('academic_year')->nullable();
            $table->enum('entry_type', ['beginning_of_term', 'activities_of_integration', 'test', 'end_of_term']);
            $table->integer('activity_number')->nullable();
            $table->integer('test_number')->nullable();
            $table->decimal('theory_marks', 8, 2)->nullable();
            $table->decimal('practical_marks', 8, 2)->nullable();
            $table->decimal('total_marks', 8, 2)->nullable();
            $table->string('grade')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamp('entered_at')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('teacher_subject_id')->references('id')->on('teacher_subjects')->onDelete('cascade');
            $table->foreign('academics_id')->references('id')->on('academics')->onDelete('set null');
            $table->foreign('olevel_subject_id')->references('id')->on('olevel_subjects')->onDelete('set null');
            $table->foreign('alevel_subject_id')->references('id')->on('alevel_subjects')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('staff')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marks_entries');
    }
};
