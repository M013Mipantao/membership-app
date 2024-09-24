<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'guests_name',
        'guests_email',
        'contact',
        'status',
        'fk_member_guest_id'
    ];

    // Cast attributes to specific types
    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'date',
    ];

    public function qrCode()
    {
        return $this->hasMany(QrCode::class,'fk_member_guest_qr_id');
    }
}
