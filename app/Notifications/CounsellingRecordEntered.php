<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

class CounsellingRecordEntered extends Notification implements ShouldQueue
{
    use Queueable;

    private string $recordType;
    private array $details;
    private string $action;

    public function __construct(string $recordType, array $details, string $action = 'created')
    {
        $this->recordType = $recordType;
        $this->details = $details;
        $this->action = $action;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): DatabaseMessage
    {
        return new DatabaseMessage([
            'type' => 'Counselling Record Entry',
            'action' => $this->action,
            'record_type' => $this->recordType,
            'student_name' => $this->details['student_name'] ?? 'N/A',
            'session_topic' => $this->details['session_topic'] ?? 'Not specified',
            'triggered_by' => auth()->user()->name ?? 'System',
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'icon' => 'fa-comments',
            'color' => 'indigo',
        ]);
    }
}
