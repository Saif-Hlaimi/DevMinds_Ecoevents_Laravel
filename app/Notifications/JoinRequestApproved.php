<?php

namespace App\Notifications;

use App\Models\Group;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class JoinRequestApproved extends Notification
{
    use Queueable;

    public function __construct(public Group $group) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'kind' => 'join_request_approved',
            'group_slug' => $this->group->slug,
            'group_name' => $this->group->name,
            'message' => 'Your request to join '.$this->group->name.' was approved',
        ];
    }
}
