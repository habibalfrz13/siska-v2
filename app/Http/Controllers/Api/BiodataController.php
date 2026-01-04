<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Biodata;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

/**
 * API Biodata Controller
 * 
 * Handles user profile/biodata operations for mobile app
 */
class BiodataController extends Controller
{
    /**
     * Create or update user biodata
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $validator = Validator::make($request->all(), [
            'alamat' => 'required|string',
            'nomor_telepon' => 'required|string',
            'Bio' => 'required|string', // Matches DB column case sensitivity if any, usually lowercase in Laravel accessors but input case matches
            'ttl' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if exists
        $biodata = Biodata::where('id_user', $user->id)->first();

        if ($biodata) {
            $biodata->update([
                'alamat' => $request->alamat,
                'nomor_telepon' => $request->nomor_telepon,
                'Bio' => $request->Bio,
                'ttl' => $request->ttl,
                'jenis_kelamin' => $request->jenis_kelamin,
            ]);
            $message = 'Biodata berhasil diperbarui.';
        } else {
            $biodata = Biodata::create([
                'id_user' => $user->id,
                'alamat' => $request->alamat,
                'nomor_telepon' => $request->nomor_telepon,
                'Bio' => $request->Bio,
                'ttl' => $request->ttl,
                'jenis_kelamin' => $request->jenis_kelamin,
            ]);
            $message = 'Biodata berhasil dibuat.';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $biodata
        ]);
    }

    /**
     * Update specific fields including photo
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $user = $request->user();
        $biodata = Biodata::where('id_user', $user->id)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'alamat' => 'nullable|string',
            'nomor_telepon' => 'nullable|string',
            'Bio' => 'nullable|string',
            'ttl' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        $dataToUpdate = $request->only(['alamat', 'nomor_telepon', 'Bio', 'ttl', 'jenis_kelamin']);
        
        // Remove nulls so we don't overwrite if not sent
        $dataToUpdate = array_filter($dataToUpdate, function($value) { 
            return !is_null($value); 
        });

        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            if ($biodata->foto && Storage::disk('public_custom')->exists('images/profile/' . $biodata->foto)) {
                Storage::disk('public_custom')->delete('images/profile/' . $biodata->foto);
            }

            $image = $request->file('foto');
            $imageName = $image->hashName();
            $image->storeAs('images/profile', $imageName, 'public_custom'); 
            $dataToUpdate['foto'] = $imageName;
        }

        $biodata->update($dataToUpdate);

        // Also update name in User table if provided
        if ($request->has('name')) {
            $user->update(['name' => $request->name]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
            'data' => [
                'user' => $user->fresh(),
                'biodata' => $biodata->fresh(),
                'foto_url' => $biodata->foto ? url('images/profile/' . $biodata->foto) : null // Using the route we saw in web.php or direct URL
            ]
        ]);
    }
}
