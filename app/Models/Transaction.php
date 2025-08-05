<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /** @use HasFactory<\Database\Factories\TransactionFactory> */
    use HasFactory;

    protected $table = 'transactions';
    
    protected $fillable = [
        'produk_id',
        'produk_nama',
        'total',
        'tipe_pembayaran',
        'tipe_transaksi'  ];
}
