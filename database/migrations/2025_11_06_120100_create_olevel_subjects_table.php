<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('olevel_subjects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('academics_id')->nullable();
            $table->string('subject_name');
            $table->string('subject_code')->nullable();
            $table->enum('category', ['general', 'optional'])->default('general');
            $table->json('classes')->nullable(); // S1, S2, S3, S4
            $table->boolean('requires_practical')->default(false);
            $table->timestamps();

            $table->foreign('academics_id')->references('id')->on('academics')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('olevel_subjects');
    }
};
