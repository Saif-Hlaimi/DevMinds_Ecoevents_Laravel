<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'date',
        'location',
        'image',
        'user_id', // Organizer of the event
    ];
    protected $casts = [
    'date' => 'datetime',
];

    // Relationship with User (organizer)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}