<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','slug','description','cover_image','cover_image_path','privacy','created_by'
    ];

    protected static function booted()
    {
        static::creating(function (Group $group) {
            if (empty($group->slug)) {
                $group->slug = Str::slug($group->name).'-'.Str::random(5);
            }
        });
    }

    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
    public function members() { return $this->hasMany(GroupMember::class); }
    public function approvedMembers() { return $this->members()->where('status','approved'); }
    public function joinRequests() { return $this->hasMany(GroupJoinRequest::class); }
    public function posts() { return $this->hasMany(GroupPost::class)->latest(); }

    public function getCoverImageSrcAttribute(): ?string
    {
        if ($this->cover_image_path) {
            return asset('storage/'.$this->cover_image_path);
        }
        return $this->cover_image ?: null;
    }
}
