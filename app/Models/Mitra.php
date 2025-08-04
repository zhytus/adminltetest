<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mitra extends Model
{
    /** @use HasFactory<\Database\Factories\MitraFactory> */
    use HasFactory;
    protected $table = 'mitras';

     protected $fillable = [
        'nama',
        'nomor_telepon',
        'role',
        'saldo_piutang',
    ];

}
