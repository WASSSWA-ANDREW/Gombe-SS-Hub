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
        Schema::create('student_performances', function (Blueprint $table) {
            $table->id();
            $table->string('student_id', 191);
            $table->unsignedBigInteger('olevel_subject_id')->nullable();
            $table->unsignedBigInteger('alevel_subject_id')->nullable();
            $table->enum('level', ['olevel', 'alevel']);
            $table->string('class');
            $table->string('stream')->nullable();
            $table->string('academic_year');
            $table->string('term')->nullable();
            $table->decimal('average_marks', 8, 2)->default(0);
            $table->decimal('highest_marks', 8, 2)->nullable();
            $table->decimal('lowest_marks', 8, 2)->nullable();
            $table->decimal('performance_trend', 5, 2)->default(0);
            $table->string('grade')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('admission_number')->on('students')->onDelete('cascade');
            $table->foreign('olevel_subject_id')->references('id')->on('olevel_subjects')->onDelete('set null');
            $table->foreign('alevel_subject_id')->references('id')->on('alevel_subjects')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_performances');
    }
};
