<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('marks_entries', function (Blueprint $table) {
            $table->integer('uace_points')->nullable()->after('grade')->comment('UACE points for A-level subjects');
            $table->boolean('is_principal_subject')->nullable()->after('uace_points')->default(true)->comment('Indicates if subject is principal (true) or subsidiary (false)');
        });
    }

    public function down(): void
    {
        Schema::table('marks_entries', function (Blueprint $table) {
            $table->dropColumn(['uace_points', 'is_principal_subject']);
        });
    }
};
