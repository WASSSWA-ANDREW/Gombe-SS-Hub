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
        Schema::create('discipline_tracks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->string('case_name');
            $table->enum('disciplinary_action', ['statement_letter', 'cautions', 'active_punishment']);
            $table->enum('resolution', ['suspension', 'expulsion'])->nullable();
            $table->enum('case_status', ['pending', 'sorted'])->default('pending');
            $table->date('date_of_incident')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('recorded_by')->nullable(); // staff_id
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('recorded_by')->references('id')->on('staff')->onDelete('set null');
            $table->index(['student_id', 'case_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discipline_tracks');
    }
};
