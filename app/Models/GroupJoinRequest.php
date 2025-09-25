<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupJoinRequest extends Model
{
    use HasFactory;

    protected $fillable = ['group_id','user_id','message','status','handled_by','handled_at'];

    protected $casts = [
        'handled_at' => 'datetime',
    ];

    public function group() { return $this->belongsTo(Group::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function handler() { return $this->belongsTo(User::class, 'handled_by'); }
}
