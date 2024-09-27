<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $table = 'transaction';
    protected $fillable = [
        'fk_qr_id', 
        'fk_user_id', 
        'type',
        'created_at',
        'status'
    ];

}
