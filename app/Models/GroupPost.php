<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupPost extends Model
{
    use HasFactory;

    protected $fillable = ['group_id','user_id','content','image_url','image_path'];

    public function getImageSrcAttribute(): ?string
    {
        if ($this->image_path) {
            return asset('storage/'.$this->image_path);
        }
        return $this->image_url ?: null;
    }

    public function group() { return $this->belongsTo(Group::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function comments() { return $this->hasMany(GroupPostComment::class, 'post_id'); }
    public function reactions() { return $this->hasMany(GroupPostReaction::class, 'post_id'); }
}
