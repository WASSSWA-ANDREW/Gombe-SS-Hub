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
        Schema::create('counselling_tracks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->enum('counselling_type', ['life', 'academic', 'behavior', 'gender', 'character', 'sex']);
            $table->date('date_of_session');
            $table->text('notes')->nullable();
            $table->text('outcome')->nullable();
            $table->enum('status', ['ongoing', 'completed'])->default('ongoing');
            $table->unsignedBigInteger('counsellor_id')->nullable(); // staff_id
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('counsellor_id')->references('id')->on('staff')->onDelete('set null');
            $table->index(['student_id', 'status', 'counselling_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('counselling_tracks');
    }
};
