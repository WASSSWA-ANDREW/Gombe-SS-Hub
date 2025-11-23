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
        Schema::table('discipline_tracks', function (Blueprint $table) {
            $table->json('attachments')->nullable()->comment('JSON array of uploaded files with types');
            $table->string('statement_type')->nullable()->comment('Type of statement: written_statement, caution, counselling_agreement');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('discipline_tracks', function (Blueprint $table) {
            $table->dropColumn(['attachments', 'statement_type']);
        });
    }
};
