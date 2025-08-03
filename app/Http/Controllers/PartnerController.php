<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    /**
     * Display the partner management dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('partners.customer');
    }

    /**
     * Show the form for creating a new partner.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        return view('partners.supplier');
    }
}
