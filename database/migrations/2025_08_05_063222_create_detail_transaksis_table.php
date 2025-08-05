<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detail_transaksis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('produk_id');
            $table->string('produk_nama');
            $table->unsignedBigInteger('mitra_id');
            $table->string('mitra_nama');
            $table->integer('jumlah_barang');
            $table->decimal('harga_beli', 15, 2);
            $table->decimal('total', 15, 2);
            $table->enum('tipe', ['pembelian', 'penjualan']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_transaksis');
    }
};
