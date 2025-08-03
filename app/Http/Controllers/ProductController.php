<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        // Load categories to display in table
        $kategoris = Kategori::all();
        return view('product.kategori', compact('kategoris'));
    }

    public function kategory()
    {
        $kategoris = Kategori::all();
        return view('product.kategori', compact('kategoris'));
    }

    public function getData()
    {
        $kategoris = Kategori::all();
        return response()->json([
            'success' => true,
            'data' => $kategoris
        ]);
    }

    public function create()
    {
        return view('kategori.create');
    }

    // FIXED STORE METHOD
    public function store(Request $request) 
    {
        try {
            // Validate input
            $request->validate([
                'nama_kategori' => 'required|string|max:255|unique:kategoris,nama_kategori',
            ]);

            // Create new category
            $kategori = Kategori::create([
                'nama_kategori' => $request->nama_kategori
            ]);

            // Return JSON response for AJAX
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kategori berhasil ditambahkan!',
                    'data' => $kategori
                ]);
            }

            return redirect()->back()->with('success', 'Kategori berhasil ditambahkan!');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan!');
        }
    }

    public function show(Kategori $kategori)
    {
        return response()->json([
            'success' => true,
            'data' => $kategori
        ]);
    }

    public function edit(Kategori $kategori)
    {
        return view('kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        try {
            $request->validate([
                'nama_kategori' => 'required|string|max:255|unique:kategoris,nama_kategori,' . $kategori->id,
            ]);

            $kategori->update(['nama_kategori' => $request->nama_kategori]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kategori berhasil diupdate!',
                    'data' => $kategori
                ]);
            }

            return redirect()->back()->with('success', 'Kategori berhasil diupdate!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan!');
        }
    }

    public function destroy(Kategori $kategori)
    {
        try {
            $nama_kategori = $kategori->nama_kategori;
            $kategori->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Kategori '$nama_kategori' berhasil dihapus!"
                ]);
            }

            return redirect()->back()->with('success', "Kategori '$nama_kategori' berhasil dihapus!");
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan!');
        }
    }

    public function tambahDariProduk(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|unique:kategoris,nama_kategori',
        ]);

        $kategori = Kategori::create([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return response()->json([
            'success' => true,
            'kategori' => $kategori
        ]);
    }
}