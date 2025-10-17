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
        'user_id',       // Organizer
        'category',      // Fixed enum: conference, workshop, seminar, other
        'type',          // online / onsite
        'max_participants',
        'meet_link',
        'is_paid',      // ✅ Nouveau
        'price',       
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    // Organizer
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Participants (Many-to-Many)
    public function participants()
    {
        return $this->belongsToMany(User::class, 'event_user')
                    ->withTimestamps()
                    ->withPivot('status');
    }

    public function isFull()
    {
        if ($this->type === 'online') {
            return false;
        }
        return $this->participants()->count() >= $this->max_participants;
    }

    // Enum list for validation or select
    public static function categories(): array
    {
        return [
            'conference' => 'Conference',
            'workshop'   => 'Workshop',
            'seminar'    => 'Seminar',
            'other'      => 'Other',
        ];
    }
    public function comments()
{
    return $this->hasMany(Comment::class)->latest();
}

}
