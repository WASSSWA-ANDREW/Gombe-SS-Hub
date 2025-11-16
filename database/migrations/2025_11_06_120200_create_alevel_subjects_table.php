<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alevel_subjects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('academics_id')->nullable();
            $table->string('subject_name');
            $table->string('subject_code')->nullable();
            $table->enum('stream', ['arts', 'science', 'general'])->default('general');
            $table->enum('category', ['principal', 'subsidiary', 'general'])->default('principal');
            $table->json('classes')->nullable(); // S5, S6
            $table->timestamps();

            $table->foreign('academics_id')->references('id')->on('academics')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alevel_subjects');
    }
};
