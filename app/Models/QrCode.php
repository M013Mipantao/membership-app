<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'qr_code', 
        'fk_member_guest_qr_id', 
        'type',
        'startdate',
        'enddate',
        'status'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class,'id');
    }

    // public function guest()
    // {
    //     return $this->belongsTo(Guest::class, 'id');
    // }

    protected $table = 'qr_codes';

    public function guest()
    {
        return $this->belongsTo(Guest::class, 'fk_member_guest_qr_id');
    }

}
