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
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            DB::table('student_optional_subjects')
                ->whereNotNull('student_id')
                ->update(['student_id' => null]);

            DB::table('marks_entries')
                ->whereNotNull('student_id')
                ->update(['student_id' => null]);

            DB::table('discipline_tracks')
                ->whereNotNull('student_id')
                ->update(['student_id' => null]);

            DB::table('counselling_tracks')
                ->whereNotNull('student_id')
                ->update(['student_id' => null]);

            Schema::table('student_optional_subjects', function (Blueprint $table) {
                try {
                    $table->dropForeign(['student_id']);
                } catch (\Exception $e) {
                    DB::statement('ALTER TABLE student_optional_subjects DROP FOREIGN KEY student_optional_subjects_student_id_foreign');
                }
            });

            Schema::table('marks_entries', function (Blueprint $table) {
                try {
                    $table->dropForeign(['student_id']);
                } catch (\Exception $e) {
                    DB::statement('ALTER TABLE marks_entries DROP FOREIGN KEY marks_entries_student_id_foreign');
                }
            });

            Schema::table('discipline_tracks', function (Blueprint $table) {
                try {
                    $table->dropForeign(['student_id']);
                } catch (\Exception $e) {
                    DB::statement('ALTER TABLE discipline_tracks DROP FOREIGN KEY discipline_tracks_student_id_foreign');
                }
            });

            Schema::table('counselling_tracks', function (Blueprint $table) {
                try {
                    $table->dropForeign(['student_id']);
                } catch (\Exception $e) {
                    DB::statement('ALTER TABLE counselling_tracks DROP FOREIGN KEY counselling_tracks_student_id_foreign');
                }
            });

            DB::statement('ALTER TABLE students MODIFY admission_number VARCHAR(255) NOT NULL');
            DB::statement('ALTER TABLE students DROP PRIMARY KEY');
            DB::statement('ALTER TABLE students ADD PRIMARY KEY (admission_number)');
            DB::statement('ALTER TABLE students DROP COLUMN id');

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

            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            DB::statement('ALTER TABLE student_optional_subjects DROP FOREIGN KEY student_optional_subjects_student_id_foreign');
            DB::statement('ALTER TABLE marks_entries DROP FOREIGN KEY marks_entries_student_id_foreign');
            DB::statement('ALTER TABLE discipline_tracks DROP FOREIGN KEY discipline_tracks_student_id_foreign');
            DB::statement('ALTER TABLE counselling_tracks DROP FOREIGN KEY counselling_tracks_student_id_foreign');

            DB::statement('ALTER TABLE students DROP PRIMARY KEY');
            DB::statement('ALTER TABLE students ADD COLUMN id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE FIRST');
            DB::statement('ALTER TABLE students ADD PRIMARY KEY (id)');
            DB::statement('ALTER TABLE students MODIFY admission_number VARCHAR(255) NULL');

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

            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }
};
