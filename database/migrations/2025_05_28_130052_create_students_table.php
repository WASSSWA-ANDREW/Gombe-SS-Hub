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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_name');
            $table->string('gender'); // Consider enum or specific values if applicable
            $table->string('learners_lin', 191)->nullable()->unique();
            $table->string('learners_nin', 191)->nullable()->unique();
            $table->date('date_of_birth');
            $table->string('religion')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('email', 191)->nullable()->unique();
            $table->string('district_of_birth')->nullable();
            $table->string('previous_school')->nullable();
            $table->string('ple_index_number', 191)->nullable()->unique();
            $table->text('special_issue')->nullable();
            $table->string('ple_english')->nullable();
            $table->string('ple_mathematics')->nullable();
            $table->string('ple_sst')->nullable();
            $table->string('ple_science')->nullable();
            $table->integer('ple_total')->nullable();
            $table->integer('ple_aggregates')->nullable();
            $table->string('pass_slip_path')->nullable(); // For storing the path to the uploaded file

            // Father's Details
            $table->string('father_full_name')->nullable();
            $table->string('father_mobile_number')->nullable();
            $table->string('father_email')->nullable();
            $table->string('father_nin')->nullable();
            $table->string('father_physical_address')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('father_dead_alive')->nullable(); // Consider enum: 'Alive', 'Dead'

            // Mother's Details
            $table->string('mother_full_name')->nullable();
            $table->string('mother_mobile_number')->nullable();
            $table->string('mother_email')->nullable();
            $table->string('mother_nin')->nullable();
            $table->string('mother_physical_address')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->string('mother_dead_alive')->nullable(); // Consider enum: 'Alive', 'Dead'

            // Guardian's Details (if applicable)
            $table->string('guardian_full_name')->nullable();
            $table->string('guardian_mobile_number')->nullable();
            $table->string('guardian_email')->nullable();
            $table->string('guardian_nin')->nullable();
            $table->string('guardian_physical_address')->nullable();
            $table->string('guardian_occupation')->nullable();
            $table->string('guardian_relationship')->nullable();

            $table->text('official_comment')->nullable();
            $table->string('level')->nullable(); // For O'Level, A'Level etc. Add index if frequently queried.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
