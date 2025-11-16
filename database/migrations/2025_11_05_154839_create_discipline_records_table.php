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
        Schema::create('discipline_records', function (Blueprint $table) {
            $table->id();
            
            // Student and Staff Relations
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('staff_id')->nullable()->constrained('staff')->cascadeOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('staff')->cascadeOnDelete();
            
            // Record Type and Classification
            $table->enum('record_type', ['discipline', 'counselling'])->default('discipline');
            $table->string('title');
            $table->longText('description')->nullable();
            $table->string('category');
            $table->string('sub_category')->nullable();
            
            // Severity and Priority
            $table->enum('severity_level', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->tinyInteger('priority')->default(2)->comment('1=Low, 5=Critical');
            
            // Status and Timeline
            $table->enum('status', ['pending', 'resolved', 'ongoing', 'completed', 'dismissed'])->default('pending');
            $table->dateTime('date_recorded')->useCurrent();
            $table->date('date_of_incident')->nullable();
            $table->date('follow_up_date')->nullable();
            
            // Resolution and Outcomes
            $table->longText('resolution_notes')->nullable();
            $table->longText('outcome')->nullable();
            
            // Additional Information
            $table->json('attachments')->nullable()->comment('Array of file paths');
            $table->json('tags')->nullable()->comment('Array for categorization');
            $table->boolean('is_confidential')->default(false);
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for better query performance
            $table->index('student_id');
            $table->index('staff_id');
            $table->index('status');
            $table->index('severity_level');
            $table->index('priority');
            $table->index('record_type');
            $table->index('date_of_incident');
            $table->index('follow_up_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discipline_records');
    }
};
