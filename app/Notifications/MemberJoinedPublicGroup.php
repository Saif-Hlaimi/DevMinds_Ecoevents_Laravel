<?php

namespace App\Notifications;

use App\Models\Group;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class MemberJoinedPublicGroup extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Group $group, public User $member) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'kind' => 'member_joined_public_group',
            'group_slug' => $this->group->slug,
            'group_name' => $this->group->name,
            'member_id' => $this->member->id,
            'member_name' => $this->member->name,
            'message' => $this->member->name.' joined '.$this->group->name,
        ];
    }
}
