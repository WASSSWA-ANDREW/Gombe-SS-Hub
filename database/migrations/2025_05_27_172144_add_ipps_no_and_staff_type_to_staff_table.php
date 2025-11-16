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
        Schema::table('staff', function (Blueprint $table) {
            $table->string('ipps_no')->nullable()->after('registration_no'); // IPPS Number
            $table->string('staff_type')->default('private')->after('highest_level_of_education'); // To differentiate staff, e.g., 'private', 'government'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn('ipps_no');
            $table->dropColumn('staff_type');
        });
    }
};
