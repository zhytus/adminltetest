<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\DetailTransaksi;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $transactions = Transaction::all();
        return view('transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
{
    try {
        
        $validated = $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'supplier_id' => 'required|exists:mitras,id',
            'jumlah_produk' => 'required|integer|min:1',
            'harga_beli_produk' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:255'
        ], [
            'produk_id.required' => 'Produk wajib dipilih',
            'produk_id.exists' => 'Produk tidak ditemukan',
            'supplier_id.required' => 'Supplier wajib dipilih',
            'supplier_id.exists' => 'Supplier tidak ditemukan',
            'jumlah_produk.required' => 'Jumlah produk wajib diisi',
            'jumlah_produk.min' => 'Jumlah produk minimal 1',
            'harga_beli_produk.required' => 'Harga beli wajib diisi',
            'harga_beli_produk.min' => 'Harga beli tidak boleh negatif',
        ]);

        DB::beginTransaction();
        
        $produk = Produk::findOrFail($validated['produk_id']);
        $supplier = \App\Models\Mitra::findOrFail($validated['supplier_id']);

        $totalAmount = $validated['harga_beli_produk'] * $validated['jumlah_produk'];
        
        $transaction =Transaction::create([
            'produk_id' => $produk->id,
            'produk_nama' => $produk->nama_produk,
            'total' => $totalAmount,
            'tipe_transaksi' => 'pembelian',
            'tipe_pembayaran' => $request->input('tipe_pembayaran', 'tunai'),
        ]);

        
        $transactionDetail = DetailTransaksi::create([
            'transaction_id' => $transaction->id,
            'produk_id' => $produk->id,
            'produk_nama' => $produk->nama_produk,
            'mitra_id' => $validated['supplier_id'],
            'mitra_nama' => $supplier->nama ?? 'Tidak Diketahui',
            'jumlah_barang' => $validated['jumlah_produk'],
            'harga_beli' => $validated['harga_beli_produk'],
            'total' => $totalAmount,
            'tipe' => 'pembelian'
            ]);
        
        // Update product stock (add purchased quantity)
        $produk->increment('stok', $validated['jumlah_produk']);
        
        // Update product purchase price if different
        if ($produk->harga_beli != $validated['harga_beli_produk']) {
            $produk->update(['harga_beli' => $validated['harga_beli_produk']]);
        }
        
        // Set restock status to false since we just restocked
        $produk->update(['restock_status' => false]);
        
        DB::commit();
        
        Log::info('Transaction updated successfully: ' . $transaction->id);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Pembelian produk berhasil dicatat!',
                'data' => [
                    'produk' => $produk->fresh(),
                    'transaction' => $transaction->fresh(),
                    'transaction_detail' => $transactionDetail
                ]
            ]);
        }

        return redirect()->route('transaction.index')->with('success', 'Pembelian produk berhasil dicatat!');

    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        Log::warning('Validation failed for transaction update: ', $e->errors());

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
        return redirect()->back()->withErrors($e->errors())->withInput();

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error updating transaction: ' . $e->getMessage());
        
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating transaction: ' . $e->getMessage()
            ], 500);
        }
        return redirect()->back()->with('error', 'Error updating transaction: ' . $e->getMessage());
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }

    public function getData()
    {
        $kategoris = \App\Models\Kategori::all();
        $produks = Produk::all();
        $mitras = \App\Models\Mitra::where('role', 'pemasok')->get();
        return view('transaction.purchasing', compact('produks', 'kategoris', 'mitras'));
    }
}
