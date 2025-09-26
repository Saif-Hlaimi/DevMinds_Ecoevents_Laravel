<?php

namespace App\Notifications;

use App\Models\GroupPost;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PostCommented extends Notification
{
    use Queueable;

    public function __construct(public GroupPost $post, public User $actor) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'kind' => 'post_commented',
            'post_id' => $this->post->id,
            'group_slug' => $this->post->group->slug,
            'actor_id' => $this->actor->id,
            'actor_name' => $this->actor->name,
            'message' => $this->actor->name.' commented on your post',
        ];
    }
}
