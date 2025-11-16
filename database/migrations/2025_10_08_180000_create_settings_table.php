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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('category')->default('general');
            $table->string('type')->default('string'); // string, boolean, integer, json, etc.
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('settings')->insert([
            [
                'key' => 'app_name',
                'value' => 'Gombe SS Hub',
                'category' => 'general',
                'type' => 'string',
                'description' => 'Application name',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'font_family',
                'value' => 'Ubuntu',
                'category' => 'appearance',
                'type' => 'string',
                'description' => 'Default font family for the application',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'theme_mode',
                'value' => 'light',
                'category' => 'appearance',
                'type' => 'string',
                'description' => 'Theme mode (light/dark)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};