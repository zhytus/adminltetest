<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produk extends Model
{
    use HasFactory;
    protected $fillable = ['nama_produk', 'kategori_id', 'harga_beli', 'harga_jual', 'stok', 'sellable', 'restock_status'];


    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
}
