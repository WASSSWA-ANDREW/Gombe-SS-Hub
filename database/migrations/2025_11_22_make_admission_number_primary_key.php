<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('student_optional_subjects', function (Blueprint $table) {
                try {
                    $table->dropForeign(['student_id']);
                } catch (\Exception $e) {
                    //
                }
            });

            Schema::table('marks_entries', function (Blueprint $table) {
                try {
                    $table->dropForeign(['student_id']);
                } catch (\Exception $e) {
                    //
                }
            });

            Schema::table('discipline_tracks', function (Blueprint $table) {
                try {
                    $table->dropForeign(['student_id']);
                } catch (\Exception $e) {
                    //
                }
            });

            Schema::table('counselling_tracks', function (Blueprint $table) {
                try {
                    $table->dropForeign(['student_id']);
                } catch (\Exception $e) {
                    //
                }
            });

            Schema::table('students', function (Blueprint $table) {
                DB::statement('ALTER TABLE students DROP PRIMARY KEY, DROP INDEX id');
            });

            Schema::table('students', function (Blueprint $table) {
                $table->string('admission_number')->nullable(false)->unique()->change();
                $table->dropColumn('id');
                $table->primary('admission_number');
            });

            Schema::table('student_optional_subjects', function (Blueprint $table) {
                $table->foreign('student_id')->references('admission_number')->on('students')->onDelete('cascade');
            });

            Schema::table('marks_entries', function (Blueprint $table) {
                $table->foreign('student_id')->references('admission_number')->on('students')->onDelete('cascade');
            });

            Schema::table('discipline_tracks', function (Blueprint $table) {
                $table->foreign('student_id')->references('admission_number')->on('students')->onDelete('cascade');
            });

            Schema::table('counselling_tracks', function (Blueprint $table) {
                $table->foreign('student_id')->references('admission_number')->on('students')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('student_optional_subjects', function (Blueprint $table) {
                try {
                    $table->dropForeign(['student_id']);
                } catch (\Exception $e) {
                    //
                }
            });

            Schema::table('marks_entries', function (Blueprint $table) {
                try {
                    $table->dropForeign(['student_id']);
                } catch (\Exception $e) {
                    //
                }
            });

            Schema::table('discipline_tracks', function (Blueprint $table) {
                try {
                    $table->dropForeign(['student_id']);
                } catch (\Exception $e) {
                    //
                }
            });

            Schema::table('counselling_tracks', function (Blueprint $table) {
                try {
                    $table->dropForeign(['student_id']);
                } catch (\Exception $e) {
                    //
                }
            });

            Schema::table('students', function (Blueprint $table) {
                DB::statement('ALTER TABLE students DROP PRIMARY KEY');
            });

            Schema::table('students', function (Blueprint $table) {
                $table->bigIncrements('id')->first();
                $table->primary('id');
                $table->string('admission_number')->nullable()->change();
            });

            Schema::table('student_optional_subjects', function (Blueprint $table) {
                $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            });

            Schema::table('marks_entries', function (Blueprint $table) {
                $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            });

            Schema::table('discipline_tracks', function (Blueprint $table) {
                $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            });

            Schema::table('counselling_tracks', function (Blueprint $table) {
                $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            });
        }
    }
};
