<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('notification_type');
            $table->text('message');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->json('data')->nullable();
            $table->string('recipient_role')->default('all');
            $table->boolean('read')->default(false);
            $table->timestamps();
            
            $table->index('notification_type');
            $table->index('priority');
            $table->index('recipient_role');
            $table->index('read');
            $table->index('created_at');
        });

        Schema::create('ai_predictions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->string('prediction_type');
            $table->string('level')->nullable();
            $table->string('class')->nullable();
            $table->float('confidence_score')->default(0);
            $table->json('prediction_data');
            $table->enum('risk_level', ['low', 'medium', 'high'])->nullable();
            $table->year('academic_year');
            $table->timestamps();
            
            $table->foreign('student_id')->references('id')->on('students')->onDelete('set null');
            $table->index('student_id');
            $table->index('prediction_type');
            $table->index('risk_level');
            $table->index('academic_year');
        });

        Schema::create('anomaly_detections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->unsignedBigInteger('staff_id')->nullable();
            $table->string('anomaly_type');
            $table->text('description');
            $table->enum('severity', ['low', 'medium', 'high'])->default('medium');
            $table->json('anomaly_data');
            $table->boolean('resolved')->default(false);
            $table->text('resolution_notes')->nullable();
            $table->year('academic_year');
            $table->timestamps();
            
            $table->foreign('student_id')->references('id')->on('students')->onDelete('set null');
            $table->foreign('staff_id')->references('id')->on('staff')->onDelete('set null');
            $table->index('student_id');
            $table->index('staff_id');
            $table->index('anomaly_type');
            $table->index('severity');
            $table->index('resolved');
        });

        Schema::create('recommendations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->unsignedBigInteger('staff_id')->nullable();
            $table->string('class')->nullable();
            $table->string('level')->nullable();
            $table->string('recommendation_type');
            $table->text('description');
            $table->text('recommended_action');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->boolean('implemented')->default(false);
            $table->text('implementation_notes')->nullable();
            $table->year('academic_year');
            $table->timestamps();
            
            $table->foreign('student_id')->references('id')->on('students')->onDelete('set null');
            $table->foreign('staff_id')->references('id')->on('staff')->onDelete('set null');
            $table->index('student_id');
            $table->index('staff_id');
            $table->index('recommendation_type');
            $table->index('priority');
            $table->index('implemented');
        });

        Schema::create('intelligence_logs', function (Blueprint $table) {
            $table->id();
            $table->string('service_name');
            $table->string('action');
            $table->string('entity_type')->nullable();
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->json('metadata')->nullable();
            $table->text('result')->nullable();
            $table->timestamps();
            
            $table->index('service_name');
            $table->index('action');
            $table->index('entity_type');
            $table->index('created_at');
        });

        Schema::create('performance_analytics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->string('level');
            $table->string('class');
            $table->string('stream')->nullable();
            $table->float('average_performance')->default(0);
            $table->float('trend_percentage')->default(0);
            $table->integer('subjects_passed')->default(0);
            $table->integer('subjects_failed')->default(0);
            $table->enum('performance_grade', ['A', 'B', 'C', 'D', 'E', 'F'])->nullable();
            $table->year('academic_year');
            $table->timestamps();
            
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->index('student_id');
            $table->index('level');
            $table->index('class');
            $table->index('academic_year');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('performance_analytics');
        Schema::dropIfExists('intelligence_logs');
        Schema::dropIfExists('recommendations');
        Schema::dropIfExists('anomaly_detections');
        Schema::dropIfExists('ai_predictions');
        Schema::dropIfExists('notifications');
    }
};
