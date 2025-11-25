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
        Schema::table('user_preferences', function (Blueprint $table) {

            if (!Schema::hasColumn('user_preferences', 'user_id')) {
                $table->unsignedBigInteger('user_id')->after('id');
            }

            if (!Schema::hasColumn('user_preferences', 'theme')) {
                $table->string('theme')->nullable()->after('user_id');
            }

            if (!Schema::hasColumn('user_preferences', 'notifications_enabled')) {
                $table->boolean('notifications_enabled')->default(true)->after('theme');
            }

            if (!Schema::hasColumn('user_preferences', 'language')) {
                $table->string('language')->default('en')->after('notifications_enabled');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_preferences', function (Blueprint $table) {
            if (Schema::hasColumn('user_preferences', 'user_id')) {
                try {
                    $table->dropForeign(['user_id']);
                } catch (\Exception $e) {
                    //
                }
                $table->dropColumn('user_id');
            }

            if (Schema::hasColumn('user_preferences', 'theme')) {
                $table->dropColumn('theme');
            }

            if (Schema::hasColumn('user_preferences', 'notifications_enabled')) {
                $table->dropColumn('notifications_enabled');
            }

            if (Schema::hasColumn('user_preferences', 'language')) {
                $table->dropColumn('language');
            }
        });
    }
};
