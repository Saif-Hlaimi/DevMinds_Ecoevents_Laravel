<?php

namespace App\Notifications;

use App\Models\Group;
use App\Models\GroupJoinRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JoinRequestCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Group $group, public GroupJoinRequest $requestModel) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'kind' => 'join_request_created',
            'group_slug' => $this->group->slug,
            'group_name' => $this->group->name,
            'requested_by' => $this->requestModel->user_id,
            'requested_by_name' => optional($this->requestModel->user)->name,
            'request_id' => $this->requestModel->id,
            'message' => $this->requestModel->user?->name.' requested to join '.$this->group->name,
        ];
    }
}
