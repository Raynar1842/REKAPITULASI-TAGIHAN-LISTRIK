<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warga extends Model
{
    use HasFactory;

    protected $fillable = [
        'no',
        'nama',
        'rek',
        'tagihan',
        'lunas',
    ];

    protected $casts = [
        'no' => 'integer',
        'tagihan' => 'integer',
        'lunas' => 'boolean',
    ];
}
