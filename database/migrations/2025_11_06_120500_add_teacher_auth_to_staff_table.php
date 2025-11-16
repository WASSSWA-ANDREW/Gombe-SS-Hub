<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->string('password')->nullable()->after('telephone_contacts');
            $table->boolean('enable_teacher_login')->default(false)->after('password');
            $table->timestamp('last_teacher_login_at')->nullable()->after('enable_teacher_login');
        });
    }

    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn(['password', 'enable_teacher_login', 'last_teacher_login_at']);
        });
    }
};
