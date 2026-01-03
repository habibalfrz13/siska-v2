<?php

namespace App\Http\Controllers;

use App\Models\Biodata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class BiodataController extends Controller
{
    public function index()
    {
        // Ambil semua data biodata
        $biodatas = Biodata::all();
    
        return view('dashboard.biodata.index', [
            'biodatas' => $biodatas,
            'title' => 'Biodatas',
        ]);
    }

    public function create()
    {
        return view('dashboard.biodata.create', [
            'title' => 'Create Biodata',
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            // Validasi data yang masuk
            'alamat' => 'required|string',
            'nomor_telepon' => 'required|string',
            'Bio' => 'required|string',
            'ttl' => 'required|date',
        ]);
        
        // Simpan data baru ke dalam database
        Biodata::create([
            'id_user' => Auth::id(),
            'alamat' => $request->alamat,
            'nomor_telepon' => $request->nomor_telepon,
            'Bio' => $request->Bio,
            'ttl' => $request->ttl,
        ]);

        return redirect()->route('biodata.index')
            ->with('success', 'Biodata created successfully');
    }

    public function edit($id)
    {
        $biodata = Biodata::findOrFail($id);
        return view('dashboard.biodata.edit', [
            'biodata' => $biodata,
            'title' => 'Edit Biodata',
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $biodata = Biodata::findOrFail($id);

        $dataToUpdate = [
            'username'      => $request->username,
            'nomor_telepon' => $request->nomor_telepon,
            'bio'           => $request->bio,
            'alamat'        => $request->alamat,
            'ttl'           => $request->ttl,
            'jenis_kelamin' => $request->jenis_kelamin,
        ];

        if ($request->hasFile('foto')) {
            $image = $request->file('foto');
            $imageName = $image->hashName();
            $image->storeAs('images/profile', $imageName, 'public_custom'); 
            $dataToUpdate['foto'] = $imageName;
        }

        $biodata->update($dataToUpdate);

        return redirect()->route('user.profile')
            ->with('success', 'Biodata updated successfully');
    }

    public function destroy($id)
    {
        Biodata::findOrFail($id)->delete();
        return redirect()->route('biodatas.index')
            ->with('success', 'Biodata deleted successfully');
    }
}
