<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'donation_cause_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function donationCause()
    {
        return $this->belongsTo(DonationCause::class);
    }
}