<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{ 
    protected $table = 'detail_transaksis';
    protected $fillable = [
        'transaction_id',
        'produk_id',
        'produk_nama',
        'mitra_id',
        'mitra_nama',
        'jumlah_barang',
        'harga_beli',
        'total',
        'tipe',
    ];
}
