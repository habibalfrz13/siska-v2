<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor; // Import model Vendor

class VendorController extends Controller
{
    // Method untuk menampilkan semua data vendor
    public function index()
    {
        $vendors = Vendor::all(); // Ambil semua data vendor dari database
        return view('dashboard.vendor.index', compact('vendors')); // Tampilkan view index dengan data vendor
    }

    // Method untuk menampilkan form create vendor
    public function create()
    {
        return view('dashboard.vendor.create'); // Tampilkan view create vendor
    }

    // Method untuk menyimpan data vendor baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'vendor' => 'required',
        ]);

        // Buat data vendor baru berdasarkan input
        Vendor::create($request->all());

        // Redirect ke halaman index vendor dengan pesan sukses
        return redirect()->route('vendor.index')
            ->with('success', 'Vendor created successfully');
    }

    // Method untuk menampilkan detail vendor
    public function show($id)
    {
        $vendor = Vendor::find($id); // Ambil data vendor berdasarkan ID
        return view('dashboard.vendor.show', compact('vendor')); // Tampilkan view show dengan data vendor
    }

    // Method untuk menampilkan form edit vendor
    public function edit($id)
    {
        $vendor = Vendor::find($id); // Ambil data vendor berdasarkan ID
        return view('dashboard.vendor.edit', compact('vendor')); // Tampilkan view edit dengan data vendor
    }

    // Method untuk menyimpan perubahan pada data vendor
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'vendor' => 'required',
        ]);

        // Ambil data vendor berdasarkan ID
        $vendor = Vendor::find($id);

        // Update data vendor dengan input baru
        $vendor->update($request->all());

        // Redirect ke halaman index vendor dengan pesan sukses
        return redirect()->route('vendor.index')
            ->with('success', 'Vendor updated successfully');
    }

    // Method untuk menghapus data vendor
    public function destroy($id)
    {
        // Hapus data vendor berdasarkan ID
        Vendor::destroy($id);

        // Redirect ke halaman index vendor dengan pesan sukses
        return redirect()->route('vendor.index')
            ->with('success', 'Vendor deleted successfully');
    }
}

