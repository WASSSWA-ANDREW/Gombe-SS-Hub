<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;
use App\Models\Staff;

class StaffDataEntered extends Notification implements ShouldQueue
{
    use Queueable;

    private Staff $staff;
    private string $action;

    public function __construct(Staff $staff, string $action = 'created')
    {
        $this->staff = $staff;
        $this->action = $action;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): DatabaseMessage
    {
        return new DatabaseMessage([
            'type' => 'Staff Data Entry',
            'action' => $this->action,
            'staff_name' => $this->staff->staff_name,
            'staff_type' => $this->staff->staff_type ?? 'Not Specified',
            'gender' => $this->staff->gender,
            'phone' => $this->staff->mobile_number ?? 'N/A',
            'triggered_by' => auth()->user()->name ?? 'System',
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'icon' => 'fa-chalkboard-teacher',
            'color' => 'green',
        ]);
    }
}
