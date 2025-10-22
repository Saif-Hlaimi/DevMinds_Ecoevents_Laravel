<?php

namespace App\Notifications;

use App\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class ComplaintUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $complaint;

    public function __construct(Complaint $complaint)
    {
        $this->complaint = $complaint;
    }

    public function via($notifiable)
    {
        return ['database']; // stockage en base
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Your complaint "' . $this->complaint->subject . '" has been updated.',
            'complaint_id' => $this->complaint->id,
            'status' => $this->complaint->status,
        ];
    }
}
