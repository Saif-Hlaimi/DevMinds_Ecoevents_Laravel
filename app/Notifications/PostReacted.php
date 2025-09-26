<?php

namespace App\Notifications;

use App\Models\GroupPost;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PostReacted extends Notification
{
    use Queueable;

    public function __construct(public GroupPost $post, public User $actor, public string $type) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'kind' => 'post_reacted',
            'post_id' => $this->post->id,
            'group_slug' => $this->post->group->slug,
            'actor_id' => $this->actor->id,
            'actor_name' => $this->actor->name,
            'reaction' => $this->type,
            'message' => $this->actor->name.' reacted ('.$this->type.') to your post',
        ];
    }
}
