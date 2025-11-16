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
        Schema::table('students', function (Blueprint $table) {
            $table->string('father_passport_photo_path')->nullable()->after('father_dead_alive');
            $table->string('mother_passport_photo_path')->nullable()->after('mother_dead_alive');
            $table->string('guardian_passport_photo_path')->nullable()->after('guardian_relationship');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('father_passport_photo_path');
            $table->dropColumn('mother_passport_photo_path');
            $table->dropColumn('guardian_passport_photo_path');
        });
    }
};
