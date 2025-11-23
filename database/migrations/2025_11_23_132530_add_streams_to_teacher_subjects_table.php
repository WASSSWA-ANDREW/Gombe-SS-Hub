<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teacher_subjects', function (Blueprint $table) {
            if (!Schema::hasColumn('teacher_subjects', 'streams')) {
                $table->json('streams')->nullable()->after('classes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('teacher_subjects', function (Blueprint $table) {
            if (Schema::hasColumn('teacher_subjects', 'streams')) {
                $table->dropColumn('streams');
            }
        });
    }
};
