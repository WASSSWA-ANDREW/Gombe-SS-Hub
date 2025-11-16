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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('surname');
            $table->string('first_name');
            $table->string('other_name')->nullable();
            $table->string('sex');
            $table->date('date_of_birth');
            $table->string('uts_file_no')->nullable();
            $table->string('district_file_no')->nullable();
            $table->string('computer_no')->nullable();
            $table->string('national_id_no')->nullable()->unique();
            $table->string('registration_no')->nullable();
            $table->string('salary_scale')->nullable();
            $table->decimal('gross_salary', 15, 2)->nullable();
            $table->decimal('net_salary', 15, 2)->nullable();
            $table->string('tin_no')->nullable();
            $table->date('date_of_1st_appt')->nullable();
            $table->string('designation_of_1st_appt')->nullable();
            $table->string('minute_no_1st_appt')->nullable();
            $table->date('date_of_current_appt')->nullable();
            $table->string('designation_of_current_appt')->nullable();
            $table->string('minute_no_current_appt')->nullable();
            $table->date('date_of_confirmation')->nullable();
            $table->string('minute_no_confirmation')->nullable();
            $table->date('date_of_current_posting')->nullable();
            $table->string('teaching_subjects')->nullable();
            $table->string('telephone_contacts')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('next_of_kin')->nullable();
            $table->string('next_of_kin_telephone')->nullable();
            $table->string('email')->nullable()->unique();
            $table->text('other_academic_qualifications')->nullable();
            $table->string('highest_level_of_education')->nullable();
            // 'date_of_issue' for the form itself, can be tracked by created_at
            $table->timestamps(); // Includes created_at (date of issue) and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
