<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationCause extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'raised_amount',
        'goal_amount',
        'sdg',
    ];

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }
}