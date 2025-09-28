<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_email',
        'to_email',
        'subject',
        'body',
        'is_read',
    ];
}
