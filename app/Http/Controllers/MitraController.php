<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateMitraRequest;
use Illuminate\Support\Facades\Log;

class MitraController extends Controller
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
        try {
            $customers = Mitra::orderBy('nama', 'asc')->get();
            return view('partners.customer', compact('customers'));
        } catch (\Exception $e) {
            Log::error('Error fetching customers: ' . $e->getMessage());
            return view('partners.customer')->with('error', 'Error loading customers');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'nama' => 'required|string|max:255|unique:mitras,nama',
                'nomor_telepon' => 'required|string|max:20',
                'role' => 'required|string|max:50',
                'saldo_piutang' => 'required|numeric|min:0',
            ], [
                'nama.required' => 'Nama customer wajib diisi',
                'nama.unique' => 'Nama customer sudah ada',
                'nomor_telepon.required' => 'Nomor telepon wajib diisi',
                'role.required' => 'Role wajib diisi',
                'saldo_piutang.required' => 'Saldo piutang wajib diisi',
            ]);

            $mitra = Mitra::create($validated);
            log::info('Customer created successfully: ' . $validated['nama']);

            // Return JSON response for AJAX requests
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Customer created successfully',
                    'data' => $mitra
                ]);
            }
            return redirect()->route('customer.index')->with('success', 'Customer created successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation failes for category creation: ', $e->errors());
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating customer: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating customer: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Error creating customer: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Mitra $mitra)
    {
        try {
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => $mitra
                ]);
            }
            return view('partners.customer_show', compact('mitra'));
        } catch (\Exception $e) {
            Log::error('Error fetching customer: ' . $e->getMessage());
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error fetching customer: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Error fetching customer');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mitra $customer)
    {
        try {   
            $validated=$request->validate([
                'nama' => 'required|string|max:255' . $customer->id,
                'nomor_telepon' => 'required|string|max:20',
                'role' => 'required|string|max:50',
                'saldo_piutang' => 'required|numeric|min:0',
            ], [
                'nama.required' => 'Nama customer wajib diisi',
                'nomor_telepon.required' => 'Nomor telepon wajib diisi',
                'role.required' => 'Role wajib diisi',
                'saldo_piutang.required' => 'Saldo piutang wajib diisi',
            ]);

            $customer->update($validated);
            Log::info('Customer updated successfully: ' . $customer->nama);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Customer updated successfully',
                    'data' => $customer
                ]);
            }

            return redirect()->route('customer.index')->with('success', 'Customer updated successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation failed for customer update: ', $e->errors());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
    }   catch (\Exception $e) {
            Log::error('Error updating customer: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating customer: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Error updating customer: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mitra $customer)
    {
        try {
            $id = $customer->id;
            $customer->delete();
            Log::info("Customer '{$id}' deleted successfully");

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Customer '{$id}' deleted successfully"
                ]);
            }
            return redirect()->route('customer.index')->with('success', "Customer '{$id}' deleted successfully");
        } catch (\Exception $e) {
            Log::error('Error deleting customer: ' . $e->getMessage());
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting customer: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Error deleting customer: ' . $e->getMessage());
        }
    }
}