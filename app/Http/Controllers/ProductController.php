<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of products (READ)
     */
    public function index()
    {
        $kategoris = Kategori::orderBy('nama_kategori', 'asc')->get();
        $produks = Produk::with('kategori')->orderBy('nama_produk', 'asc')->get();
        return view('product.product', compact('produks', 'kategoris'));
    }

    /**
     * Show the form for creating a new product (CREATE)
     */
    public function create()
    {
        return view('product.create');
    }

    public function store(Request $request)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'nama_produk' => 'required|string|max:255|unique:produks,nama_produk',
                'kategori_id' => 'required|exists:kategoris,id',
                'harga_beli' => 'required|numeric|min:0',
                'harga_jual' => 'required|numeric|min:0',
                'stok' => 'required|integer|min:0',
                'sellable' => 'required|boolean',
                'restock_status' => 'required|boolean',
            ], [
                'nama_produk.required' => 'Nama produk wajib diisi',
                'nama_produk.unique' => 'Nama produk sudah ada',
                'nama_produk.max' => 'Nama produk maksimal 255 karakter',
                'kategori_id.required' => 'Kategori wajib dipilih',
                'kategori_id.exists' => 'Kategori tidak valid',
                'harga_beli.required' => 'Harga beli wajib diisi',
                'harga_beli.numeric' => 'Harga beli harus berupa angka',
                'harga_jual.required' => 'Harga jual wajib diisi',
                'harga_jual.numeric' => 'Harga jual harus berupa angka',
                'stok.required' => 'Stok wajib diisi',
                'stok.integer' => 'Stok harus berupa angka bulat',
                'sellable.required' => 'Status jual wajib diisi',
                'restock_status.required' => 'Status restock wajib diisi',
            ]);

            // Create new product
            $produk = Produk::create([
                'nama_produk' => trim($validated['nama_produk']),
                'kategori_id' => $validated['kategori_id'],
                'harga_beli' => $validated['harga_beli'],
                'harga_jual' => $validated['harga_jual'],
                'stok' => $validated['stok'],
                'sellable' => $validated['sellable'],
                'restock_status' => $validated['restock_status'],
            ]);

            Log::info('Product created: ' . $produk->nama_produk);

            // Return JSON response for AJAX
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Produk berhasil ditambahkan!',
                    'data' => $produk->load('kategori')
                ]);
            }

            return redirect()->route('product.index')->with('success', 'Produk berhasil ditambahkan!');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation failed for product creation', $e->errors());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            Log::error('Error creating product: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data!');
        }
    }

    /**
     * Display the specified product (READ)
     */
    public function show(Produk $produk)
    {
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $produk->load('kategori')
            ]);
        }
        
        return view('product.show', compact('produk'));
    }

    /**
     * Show the form for editing the specified product
     */
    public function edit(Produk $produk)
    {
        $kategoris = \App\Models\Kategori::orderBy('nama_kategori', 'asc')->get();
        return view('product.edit', compact('produk', 'kategoris'));
    }

    /**
     * Update the specified product (UPDATE) - FIXED
     */
    public function update(Request $request, Produk $product)
{
    Log::info('UPDATE DIPANGGIL', $request->all());
    
    try {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255'. $product->id,
            'kategori_id' => 'required|exists:kategoris,id',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'sellable' => 'required|in:0,1,true,false', 
            'restock_status' => 'required|in:0,1,true,false', // Allow string values
        ], [
            'nama_produk.required' => 'Nama produk wajib diisi',
            'nama_produk.max' => 'Nama produk maksimal 255 karakter',
            'kategori_id.required' => 'Kategori wajib dipilih',
            'kategori_id.exists' => 'Kategori tidak valid',
            'harga_beli.required' => 'Harga beli wajib diisi',
            'harga_beli.numeric' => 'Harga beli harus berupa angka',
            'harga_beli.min' => 'Harga beli tidak boleh negatif',
            'harga_jual.required' => 'Harga jual wajib diisi',
            'harga_jual.numeric' => 'Harga jual harus berupa angka',
            'harga_jual.min' => 'Harga jual tidak boleh negatif',
            'stok.required' => 'Stok wajib diisi',
            'stok.integer' => 'Stok harus berupa angka bulat',
            'stok.min' => 'Stok tidak boleh negatif',
            'sellable.required' => 'Status jual wajib diisi',
            'restock_status.required' => 'Status restock wajib diisi',
        ]);

        // Convert string boolean values to actual booleans
        $validated['sellable'] = filter_var($validated['sellable'], FILTER_VALIDATE_BOOLEAN);
        $validated['restock_status'] = filter_var($validated['restock_status'], FILTER_VALIDATE_BOOLEAN);
        
        // Update the existing model (no need for findOrFail since we have route model binding)
        $product->update($validated);
        // dd($product);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil diupdate!',
                'data' => $product
            ]);
        }

        return redirect()->route('product.index')->with('success', 'Produk berhasil diupdate!');

    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::warning('Validation failed for product update', $e->errors());

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
        return redirect()->back()->withErrors($e->errors())->withInput();

    } catch (\Exception $e) {
        Log::error('Error updating product: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
        return redirect()->back()->with('error', 'Terjadi kesalahan saat mengupdate data!');
    }
}

    /**
     * Remove the specified product (DELETE)
     */
    public function destroy(Produk $produk)
    {
        try {
            $nama = $produk->nama_produk;
            $produk->delete();

            Log::info('Product deleted: ' . $nama);

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Produk berhasil dihapus!'
                ]);
            }

            return redirect()->route('product.index')->with('success', 'Produk berhasil dihapus!');

        } catch (\Exception $e) {
            Log::error('Error deleting product: ' . $e->getMessage());

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data!');
        }
    }
}