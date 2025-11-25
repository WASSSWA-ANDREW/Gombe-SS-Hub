<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private function dropForeignKeyIfExists($table, $constraint)
    {
        $database = DB::getDatabaseName();
        $exists = DB::selectOne(
            "SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND CONSTRAINT_NAME = ?",
            [$database, $table, $constraint]
        );
        
        if ($exists) {
            DB::statement("ALTER TABLE $table DROP FOREIGN KEY $constraint");
        }
    }

    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            $this->dropForeignKeyIfExists('student_optional_subjects', 'student_optional_subjects_student_id_foreign');
            $this->dropForeignKeyIfExists('marks_entries', 'marks_entries_student_id_foreign');
            $this->dropForeignKeyIfExists('discipline_tracks', 'discipline_tracks_student_id_foreign');
            $this->dropForeignKeyIfExists('counselling_tracks', 'counselling_tracks_student_id_foreign');
            $this->dropForeignKeyIfExists('alumnis', 'alumnis_student_id_foreign');

            DB::statement('ALTER TABLE student_optional_subjects MODIFY student_id VARCHAR(255) NULL');
            DB::statement('ALTER TABLE marks_entries MODIFY student_id VARCHAR(255) NULL');
            DB::statement('ALTER TABLE discipline_tracks MODIFY student_id VARCHAR(255) NULL');
            DB::statement('ALTER TABLE counselling_tracks MODIFY student_id VARCHAR(255) NULL');
            DB::statement('ALTER TABLE alumnis MODIFY student_id VARCHAR(255) NULL');

            DB::statement('ALTER TABLE students MODIFY admission_number VARCHAR(255) NOT NULL');
            DB::statement('ALTER TABLE students DROP PRIMARY KEY');
            
            if (DB::getSchemaBuilder()->hasColumn('students', 'id')) {
                DB::statement('ALTER TABLE students DROP COLUMN id');
            }
            
            DB::statement('ALTER TABLE students ADD PRIMARY KEY (admission_number)');

            DB::statement('ALTER TABLE student_optional_subjects ADD CONSTRAINT student_optional_subjects_student_id_foreign FOREIGN KEY (student_id) REFERENCES students(admission_number) ON DELETE CASCADE');
            DB::statement('ALTER TABLE marks_entries ADD CONSTRAINT marks_entries_student_id_foreign FOREIGN KEY (student_id) REFERENCES students(admission_number) ON DELETE CASCADE');
            DB::statement('ALTER TABLE discipline_tracks ADD CONSTRAINT discipline_tracks_student_id_foreign FOREIGN KEY (student_id) REFERENCES students(admission_number) ON DELETE CASCADE');
            DB::statement('ALTER TABLE counselling_tracks ADD CONSTRAINT counselling_tracks_student_id_foreign FOREIGN KEY (student_id) REFERENCES students(admission_number) ON DELETE CASCADE');
            DB::statement('ALTER TABLE alumnis ADD CONSTRAINT alumnis_student_id_foreign FOREIGN KEY (student_id) REFERENCES students(admission_number) ON DELETE CASCADE');

            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            $this->dropForeignKeyIfExists('student_optional_subjects', 'student_optional_subjects_student_id_foreign');
            $this->dropForeignKeyIfExists('marks_entries', 'marks_entries_student_id_foreign');
            $this->dropForeignKeyIfExists('discipline_tracks', 'discipline_tracks_student_id_foreign');
            $this->dropForeignKeyIfExists('counselling_tracks', 'counselling_tracks_student_id_foreign');
            $this->dropForeignKeyIfExists('alumnis', 'alumnis_student_id_foreign');

            DB::statement('ALTER TABLE students DROP PRIMARY KEY');
            DB::statement('ALTER TABLE students ADD COLUMN id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE FIRST');
            DB::statement('ALTER TABLE students ADD PRIMARY KEY (id)');
            DB::statement('ALTER TABLE students MODIFY admission_number VARCHAR(255) NULL');

            DB::statement('ALTER TABLE student_optional_subjects ADD CONSTRAINT student_optional_subjects_student_id_foreign FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE');
            DB::statement('ALTER TABLE marks_entries ADD CONSTRAINT marks_entries_student_id_foreign FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE');
            DB::statement('ALTER TABLE discipline_tracks ADD CONSTRAINT discipline_tracks_student_id_foreign FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE');
            DB::statement('ALTER TABLE counselling_tracks ADD CONSTRAINT counselling_tracks_student_id_foreign FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE');
            DB::statement('ALTER TABLE alumnis ADD CONSTRAINT alumnis_student_id_foreign FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE');

            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }
};
