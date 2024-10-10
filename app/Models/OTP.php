<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OTP extends Model
{
    use HasFactory;

    protected $table = 'otps'; // Specify the table name if it doesn't follow Laravel's naming conventions

    protected $fillable = [
        'membership_id', // Adjust this if you're using a different field
        'otp',
        'expires_at',
    ];

    // Optionally, define any relationships here
}
