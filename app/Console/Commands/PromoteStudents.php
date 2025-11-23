<?php

namespace App\Console\Commands;

use App\Models\Alumni;
use App\Models\Student;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PromoteStudents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'students:promote {--year= : Academic year to promote for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Promote students to next class annually and archive graduates as alumni';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $year = $this->option('year') ?? now()->year;

        $this->info("Starting student promotion for academic year {$year}");

        DB::beginTransaction();
        try {
            // Archive S6 students (A-Level candidates) as alumni
            $s6Students = Student::where('class', 'S6')->get();
            $this->archiveStudentsAsAlumni($s6Students, 'S6', $year);

            // Archive S4 students (O-Level candidates) as alumni
            $s4Students = Student::where('class', 'S4')->get();
            $this->archiveStudentsAsAlumni($s4Students, 'S4', $year);

            // Promote S5 -> S6
            $this->promoteStudents('S5', 'S6');

            // Promote S3 -> S4
            $this->promoteStudents('S3', 'S4');

            // Promote S2 -> S3
            $this->promoteStudents('S2', 'S3');

            // Promote S1 -> S2
            $this->promoteStudents('S1', 'S2');

            // Handle alumni returning as S5 (remove from alumni)
            $this->handleReturningAlumni();

            DB::commit();

            $this->info('Student promotion completed successfully!');
            $this->info("Archived: " . ($s6Students->count() + $s4Students->count()) . " graduates as alumni");
            $this->info("Promoted remaining students to next classes");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Student promotion failed: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Promote students from one class to another
     */
    private function promoteStudents(string $fromClass, string $toClass): void
    {
        $count = Student::where('class', $fromClass)->update([
            'class' => $toClass,
            'promoted_at' => now(),
            'promotion_count' => DB::raw('promotion_count + 1')
        ]);
        $this->info("Promoted {$count} students from {$fromClass} to {$toClass}");
    }

    /**
     * Archive students as alumni
     */
    private function archiveStudentsAsAlumni($students, string $graduationClass, int $year): void
    {
        foreach ($students as $student) {
            // Check if student already exists in alumni (avoid duplicates)
            $existingAlumni = Alumni::where('student_id', $student->id)->first();

            if (!$existingAlumni) {
                Alumni::create([
                    'student_name' => $student->student_name,
                    'photo_path' => $student->photo_path,
                    'gender' => $student->gender,
                    'learners_lin' => $student->learners_lin,
                    'learners_nin' => $student->learners_nin,
                    'date_of_birth' => $student->date_of_birth,
                    'religion' => $student->religion,
                    'mobile_number' => $student->mobile_number,
                    'email' => $student->email,
                    'district_of_birth' => $student->district_of_birth,
                    'district' => $student->district,
                    'nationality' => $student->nationality,
                    'tribe' => $student->tribe,
                    'previous_school' => $student->previous_school,
                    'ple_index_number' => $student->ple_index_number,
                    'uce_index_number' => $student->uce_index_number,
                    'special_issue' => $student->special_issue,
                    'ple_english' => $student->ple_english,
                    'ple_mathematics' => $student->ple_mathematics,
                    'ple_sst' => $student->ple_sst,
                    'ple_science' => $student->ple_science,
                    'ple_total' => $student->ple_total,
                    'ple_aggregates' => $student->ple_aggregates,
                    'uce_english' => $student->uce_english,
                    'uce_mathematics' => $student->uce_mathematics,
                    'uce_physics' => $student->uce_physics,
                    'uce_chemistry' => $student->uce_chemistry,
                    'uce_biology' => $student->uce_biology,
                    'uce_history' => $student->uce_history,
                    'uce_geography' => $student->uce_geography,
                    'uce_economics' => $student->uce_economics,
                    'uce_literature' => $student->uce_literature,
                    'uce_other' => $student->uce_other,
                    'combination' => $student->combination,
                    'pass_slip_path' => $student->pass_slip_path,
                    'medical_status' => $student->medical_status,
                    'physical_health' => $student->physical_health,
                    'father_full_name' => $student->father_full_name,
                    'father_mobile_number' => $student->father_mobile_number,
                    'father_email' => $student->father_email,
                    'father_nin' => $student->father_nin,
                    'father_physical_address' => $student->father_physical_address,
                    'father_occupation' => $student->father_occupation,
                    'father_dead_alive' => $student->father_dead_alive,
                    'mother_full_name' => $student->mother_full_name,
                    'mother_mobile_number' => $student->mother_mobile_number,
                    'mother_email' => $student->mother_email,
                    'mother_nin' => $student->mother_nin,
                    'mother_physical_address' => $student->mother_physical_address,
                    'mother_occupation' => $student->mother_occupation,
                    'mother_dead_alive' => $student->mother_dead_alive,
                    'guardian_full_name' => $student->guardian_full_name,
                    'guardian_mobile_number' => $student->guardian_mobile_number,
                    'guardian_email' => $student->guardian_email,
                    'guardian_nin' => $student->guardian_nin,
                    'guardian_physical_address' => $student->guardian_physical_address,
                    'guardian_occupation' => $student->guardian_occupation,
                    'guardian_relationship' => $student->guardian_relationship,
                    'official_comment' => $student->official_comment,
                    'level' => $student->level,
                    'graduation_class' => $graduationClass,
                    'graduation_year' => $year,
                    'stream' => $student->stream,
                    'student_id' => $student->id,
                ]);
            }
        }

        // Remove archived students from students table
        Student::whereIn('id', $students->pluck('id'))->delete();
    }

    /**
     * Handle alumni returning to school (remove from alumni table)
     */
    private function handleReturningAlumni(): void
    {
        // Find students currently in S5 who might have been alumni before
        $s5Students = Student::where('class', 'S5')->get();

        foreach ($s5Students as $student) {
            // Remove from alumni if they exist there
            Alumni::where('student_id', $student->id)->delete();
        }
    }
}
