<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mitra extends Model
{
    /** @use HasFactory<\Database\Factories\MitraFactory> */
    use HasFactory;

     protected $fillable = [
        'kode_mitra',
        'nama',
        'nomor_telepon',
        'tipe',
        'saldo_piutang',
    ];

}
