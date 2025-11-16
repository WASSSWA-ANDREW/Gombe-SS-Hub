<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHealthFieldsToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add health fields to students table if they don't exist
        if (Schema::hasTable('students') && !Schema::hasColumn('students', 'medical_status')) {
            Schema::table('students', function (Blueprint $table) {
                $table->string('medical_status')->default('Healthy')->after('ple_aggregates');
                $table->string('physical_health')->default('Fit')->after('medical_status');
            });
        }

        // Add health fields to staff table if they don't exist
        if (Schema::hasTable('staff') && !Schema::hasColumn('staff', 'medical_status')) {
            Schema::table('staff', function (Blueprint $table) {
                $table->string('medical_status')->default('Healthy')->after('highest_level_of_education');
                $table->string('physical_health')->default('Fit')->after('medical_status');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remove health fields from students table if they exist
        if (Schema::hasTable('students') && Schema::hasColumn('students', 'medical_status')) {
            Schema::table('students', function (Blueprint $table) {
                $table->dropColumn('medical_status');
                $table->dropColumn('physical_health');
            });
        }

        // Remove health fields from staff table if they exist
        if (Schema::hasTable('staff') && Schema::hasColumn('staff', 'medical_status')) {
            Schema::table('staff', function (Blueprint $table) {
                $table->dropColumn('medical_status');
                $table->dropColumn('physical_health');
            });
        }
    }
}