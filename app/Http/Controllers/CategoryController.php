<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of categories (READ)
     */
    public function index()
    {
        try {
            $kategoris = Kategori::orderBy('nama_kategori', 'asc')->get();
            return view('product.kategori', compact('kategoris'));
        } catch (\Exception $e) {
            Log::error('Error fetching categories: ' . $e->getMessage());
            return view('product.kategori')->with('error', 'Error loading categories');
        }
    }

    /**
     * Get all categories as JSON for AJAX requests
     */
    public function getData()
    {
        try {
            $kategoris = Kategori::orderBy('nama_kategori', 'asc')->get();
            return response()->json([
                'success' => true,
                'data' => $kategoris
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created category (CREATE)
     */
    public function store(Request $request) 
    {
        try {
            // Validate input
            $request->validate([
                'nama_kategori' => 'required|string|max:255|unique:kategoris,nama_kategori',
            ], [
                'nama_kategori.required' => 'Nama kategori wajib diisi',
                'nama_kategori.unique' => 'Nama kategori sudah ada',
                'nama_kategori.max' => 'Nama kategori maksimal 255 karakter'
            ]);

            // Create new category
            $kategori = Kategori::create([
                'nama_kategori' => trim($request->nama_kategori)
            ]);

            Log::info('Category created: ' . $kategori->nama_kategori);

            // Return JSON response for AJAX
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kategori berhasil ditambahkan!',
                    'data' => $kategori
                ]);
            }

            return redirect()->route('category.index')->with('success', 'Kategori berhasil ditambahkan!');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation failed for category creation', $e->errors());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            Log::error('Error creating category: ' . $e->getMessage());
            
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
     * Display the specified category (READ - Single)
     */
    public function show(Kategori $kategori)
    {
        try {
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => $kategori
                ]);
            }
            
            return view('product.kategori-detail', compact('kategori'));
        } catch (\Exception $e) {
            Log::error('Error showing category: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error fetching category data'
                ], 500);
            }
            
            return redirect()->route('category.index')->with('error', 'Category not found');
        }
    }

    /**
     * Update the specified category (UPDATE)
     */
    public function update(Request $request, Kategori $kategori)
    {
        try {
            // Validate input
            $request->validate([
                'nama_kategori' => 'required|string|max:255|unique:kategoris,nama_kategori,' . $kategori->id,
            ], [
                'nama_kategori.required' => 'Nama kategori wajib diisi',
                'nama_kategori.unique' => 'Nama kategori sudah ada',
                'nama_kategori.max' => 'Nama kategori maksimal 255 karakter'
            ]);

            $oldName = $kategori->nama_kategori;
            
            // Update category
            $kategori->update([
                'nama_kategori' => trim($request->nama_kategori)
            ]);

            Log::info("Category updated from '{$oldName}' to '{$kategori->nama_kategori}'");

            // Return JSON response for AJAX
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kategori berhasil diupdate!',
                    'data' => $kategori
                ]);
            }

            return redirect()->route('category.index')->with('success', 'Kategori berhasil diupdate!');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation failed for category update', $e->errors());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            Log::error('Error updating category: ' . $e->getMessage());
            
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
     * Remove the specified category (DELETE)
     */
    public function destroy(Kategori $kategori)
    {
        try {
            $nama_kategori = $kategori->nama_kategori;
            
            // Check if category has related products (optional)
            // if ($kategori->products()->count() > 0) {
            //     if (request()->ajax()) {
            //         return response()->json([
            //             'success' => false,
            //             'message' => 'Kategori tidak dapat dihapus karena masih memiliki produk!'
            //         ], 400);
            //     }
            //     return redirect()->back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki produk!');
            // }

            $kategori->delete();
            
            Log::info("Category deleted: {$nama_kategori}");

            // Return JSON response for AJAX
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Kategori '{$nama_kategori}' berhasil dihapus!"
                ]);
            }

            return redirect()->route('category.index')->with('success', "Kategori '{$nama_kategori}' berhasil dihapus!");
            
        } catch (\Exception $e) {
            Log::error('Error deleting category: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data!');
        }
    }

    /**
     * Additional method for product page
     */
    public function showProduct()
    {
        return view('product.index');
    }

    /**
     * Method for adding category from product form
     */
    public function tambahDariProduk(Request $request)
    {
        try {
            $request->validate([
                'nama_kategori' => 'required|string|max:255|unique:kategoris,nama_kategori',
            ]);

            $kategori = Kategori::create([
                'nama_kategori' => trim($request->nama_kategori),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil ditambahkan!',
                'data' => $kategori
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}