<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    
    public function index()
    {
        // Logic to retrieve products or categories
        return view('product/kategori'); // Assuming 'dashboard' is the view for displaying products
    }
    public function show()
    {
        // Logic to retrieve a specific product by ID
        return view('product/product'); // Assuming 'show' is the view for displaying a single product
    }
}
