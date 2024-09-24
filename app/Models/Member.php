<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Member extends Model
{
        use HasFactory;
    
        protected $fillable = [
            'membership_id',
            'members_name',
            'members_email',
            'gender',
            'date_of_birth',
            'status',
            'user_id'
        ];
    
        // Cast attributes to specific types
        protected $casts = [
            'email_verified_at' => 'datetime',
            'date_of_birth' => 'date',
        ];

        public function qrCodes()
        {
            return $this->hasMany(QrCode::class);
        }

            // Relationship to User model
        public function user()
        {
            return $this->belongsTo(User::class, 'user_id');
        }
    
    
}