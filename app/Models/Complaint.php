<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $fillable = [
        'subject','message','category','priority','status',
        'attachment_path','assigned_to','user_id','complaint_type_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class,'assigned_to');
    }

    public function type()
    {
        return $this->belongsTo(ComplaintType::class,'complaint_type_id');
    }

    // Filtres pour index organisé
    public function scopeFilter($q, array $f)
    {
        if ($s = $f['q'] ?? null) {
            $q->where(fn($w) =>
                $w->where('subject','like',"%$s%")
                  ->orWhere('message','like',"%$s%")
            );
        }

        if ($st = $f['status'] ?? null) {
            $q->where('status', $st);
        }

        if ($pr = $f['priority'] ?? null) {
            $q->where('priority', $pr);
        }

        if ($mine = $f['mine'] ?? null) {
            $q->where('user_id', $mine);
        }

        return $q;
    }
}