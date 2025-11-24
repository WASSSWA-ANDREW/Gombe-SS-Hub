<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

class AcademicsDataEntered extends Notification implements ShouldQueue
{
    use Queueable;

    private string $dataType;
    private array $details;
    private string $action;

    public function __construct(string $dataType, array $details, string $action = 'created')
    {
        $this->dataType = $dataType;
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
            'type' => 'Academics Data Entry',
            'action' => $this->action,
            'data_type' => $this->dataType,
            'details' => json_encode($this->details),
            'triggered_by' => auth()->user()->name ?? 'System',
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'icon' => 'fa-book',
            'color' => 'purple',
        ]);
    }
}
