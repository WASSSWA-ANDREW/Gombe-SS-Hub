<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;
use App\Models\Student;

class StudentDataEntered extends Notification implements ShouldQueue
{
    use Queueable;

    private Student $student;
    private string $action;

    public function __construct(Student $student, string $action = 'created')
    {
        $this->student = $student;
        $this->action = $action;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): DatabaseMessage
    {
        return new DatabaseMessage([
            'type' => 'Student Data Entry',
            'action' => $this->action,
            'student_name' => $this->student->student_name,
            'admission_number' => $this->student->admission_number,
            'level' => strtoupper($this->student->level),
            'class' => $this->student->class,
            'stream' => $this->student->stream,
            'gender' => $this->student->gender,
            'triggered_by' => auth()->user()->name ?? 'System',
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'icon' => 'fa-user-graduate',
            'color' => 'blue',
        ]);
    }
}
