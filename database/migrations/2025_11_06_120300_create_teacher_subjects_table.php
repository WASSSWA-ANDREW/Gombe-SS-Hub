<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_subjects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('staff_id');
            $table->unsignedBigInteger('academics_id')->nullable();
            $table->unsignedBigInteger('olevel_subject_id')->nullable();
            $table->unsignedBigInteger('alevel_subject_id')->nullable();
            $table->enum('level', ['olevel', 'alevel'])->default('olevel');
            $table->enum('specialty', ['arts', 'science'])->nullable();
            $table->json('classes')->nullable(); // Classes they teach
            $table->timestamps();

            $table->foreign('staff_id')->references('id')->on('staff')->onDelete('cascade');
            $table->foreign('academics_id')->references('id')->on('academics')->onDelete('set null');
            $table->foreign('olevel_subject_id')->references('id')->on('olevel_subjects')->onDelete('set null');
            $table->foreign('alevel_subject_id')->references('id')->on('alevel_subjects')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_subjects');
    }
};
