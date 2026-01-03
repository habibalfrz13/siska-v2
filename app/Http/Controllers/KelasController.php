<?php

namespace App\Http\Controllers;

use App\Http\Traits\HandlesErrors;
use App\Models\Biodata;
use App\Models\Kategori;
use App\Models\Kelas;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class KelasController extends Controller
{
    use HandlesErrors;

    /**
     * Display a listing of all classes (Admin)
     */
    public function index()
    {
        try {
            $kelas = Kelas::with(['kategori', 'vendor'])
                ->orderBy('created_at', 'desc')
                ->get();

            return view('dashboard.kelas.index', compact('kelas'));
        } catch (\Exception $e) {
            $this->logError($e, 'fetching classes');
            return redirect()->route('home')
                ->with('error', 'Gagal memuat data kelas.');
        }
    }

    /**
     * Display available classes for users
     */
    public function userIndex()
    {
        try {
            $userId = Auth::id();
            $biodata = Biodata::where('user_id', $userId)->first();

            // Get active classes not enrolled by current user
            $kelas = Kelas::where('status', 'aktif')
                ->whereNotExists(function ($query) use ($userId) {
                    $query->select(DB::raw(1))
                        ->from('myclasses')
                        ->whereRaw('myclasses.kelas_id = kelas.id')
                        ->where('user_id', $userId);
                })
                ->with(['kategori', 'vendor'])
                ->orderBy('pelaksanaan', 'asc')
                ->get();

            return view('dashboard.kelas.userIndex', [
                'kelas' => $kelas,
                'biodata' => $biodata
            ]);
        } catch (\Exception $e) {
            $this->logError($e, 'fetching user classes');
            return redirect()->route('home')
                ->with('error', 'Gagal memuat data kelas.');
        }
    }

    /**
     * Show the form for creating a new class
     */
    public function create()
    {
        try {
            $kategoris = Kategori::orderBy('nama_kategori')->get();
            $vendors = Vendor::orderBy('vendor')->get();

            return view('dashboard.kelas.create', compact('kategoris', 'vendors'));
        } catch (\Exception $e) {
            $this->logError($e, 'loading create form');
            return redirect()->route('kelas.index')
                ->with('error', 'Gagal memuat form.');
        }
    }

    /**
     * Store a newly created class
     */
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048',
            'judul' => 'required|string|max:255',
            'kuota' => 'required|integer|min:1',
            'pelaksanaan' => 'required|date|after:today',
            'deskripsi' => 'nullable|string|max:5000',
            'harga' => 'required|numeric|min:0',
            'id_kategori' => 'required|exists:kategori,id',
            'id_vendor' => 'required|exists:vendors,id',
        ], [
            'foto.required' => 'Foto kelas wajib diunggah.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.max' => 'Ukuran gambar maksimal 2MB.',
            'judul.required' => 'Judul kelas wajib diisi.',
            'kuota.required' => 'Kuota peserta wajib diisi.',
            'kuota.min' => 'Kuota minimal 1 peserta.',
            'pelaksanaan.required' => 'Tanggal pelaksanaan wajib diisi.',
            'pelaksanaan.after' => 'Tanggal pelaksanaan harus setelah hari ini.',
            'harga.required' => 'Harga kelas wajib diisi.',
            'id_kategori.required' => 'Kategori wajib dipilih.',
            'id_vendor.required' => 'Vendor wajib dipilih.',
        ]);

        try {
            DB::beginTransaction();

            // Handle file upload
            $image = $request->file('foto');
            $imageName = $image->hashName();
            $image->storeAs('images/galerikelas/', $imageName, 'public');

            // Create class
            Kelas::create([
                'judul' => $validated['judul'],
                'kuota' => $validated['kuota'],
                'pelaksanaan' => $validated['pelaksanaan'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'harga' => $validated['harga'],
                'id_kategori' => $validated['id_kategori'],
                'id_vendor' => $validated['id_vendor'],
                'status' => 'Tidak Aktif',
                'foto' => $imageName,
            ]);

            DB::commit();

            Log::info('Class created', [
                'title' => $validated['judul'],
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('kelas.index')
                ->with('success', 'Kelas berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();

            // Delete uploaded file if exists
            if (isset($imageName)) {
                Storage::disk('public')->delete('images/galerikelas/' . $imageName);
            }

            $this->logError($e, 'creating class', ['title' => $request->judul]);

            return redirect()->back()
                ->with('error', 'Gagal membuat kelas. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Display the specified class
     */
    public function show($id)
    {
        try {
            $kelas = Kelas::with(['kategori', 'vendor'])->findOrFail($id);
            return view('dashboard.kelas.show', compact('kelas'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('kelas.index')
                ->with('error', 'Kelas tidak ditemukan.');
        } catch (\Exception $e) {
            $this->logError($e, 'showing class', ['id' => $id]);
            return redirect()->route('kelas.index')
                ->with('error', 'Gagal memuat detail kelas.');
        }
    }

    /**
     * Show the form for editing the specified class
     */
    public function edit($id)
    {
        try {
            $kelas = Kelas::findOrFail($id);
            $kategoris = Kategori::orderBy('nama_kategori')->get();
            $vendors = Vendor::orderBy('vendor')->get();

            return view('dashboard.kelas.edit', compact('kelas', 'kategoris', 'vendors'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('kelas.index')
                ->with('error', 'Kelas tidak ditemukan.');
        } catch (\Exception $e) {
            $this->logError($e, 'loading edit form', ['id' => $id]);
            return redirect()->route('kelas.index')
                ->with('error', 'Gagal memuat form edit.');
        }
    }

    /**
     * Update the specified class
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kuota' => 'required|integer|min:1',
            'pelaksanaan' => 'required|date',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'deskripsi' => 'nullable|string|max:5000',
            'harga' => 'nullable|numeric|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $kelas = Kelas::findOrFail($id);
            $updateData = [
                'judul' => $validated['judul'],
                'kuota' => $validated['kuota'],
                'pelaksanaan' => $validated['pelaksanaan'],
                'status' => $validated['status'],
                'deskripsi' => $validated['deskripsi'] ?? $kelas->deskripsi,
                'harga' => $validated['harga'] ?? $kelas->harga,
            ];

            // Handle new photo upload
            if ($request->hasFile('foto')) {
                $oldPhoto = $kelas->foto;
                $image = $request->file('foto');
                $imageName = $image->hashName();
                $image->storeAs('images/galerikelas/', $imageName, 'public');
                $updateData['foto'] = $imageName;

                // Delete old photo
                if ($oldPhoto) {
                    Storage::disk('public')->delete('images/galerikelas/' . $oldPhoto);
                }
            }

            $kelas->update($updateData);

            DB::commit();

            Log::info('Class updated', [
                'id' => $id,
                'title' => $validated['judul'],
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('kelas.index')
                ->with('success', 'Kelas berhasil diperbarui.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('kelas.index')
                ->with('error', 'Kelas tidak ditemukan.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError($e, 'updating class', ['id' => $id]);
            return redirect()->back()
                ->with('error', 'Gagal memperbarui kelas.')
                ->withInput();
        }
    }

    /**
     * Remove the specified class
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $kelas = Kelas::findOrFail($id);
            $title = $kelas->judul;
            $photo = $kelas->foto;

            // Check if class has enrollments
            if ($kelas->myClasses()->exists()) {
                return redirect()->route('kelas.index')
                    ->with('error', 'Kelas tidak dapat dihapus karena sudah ada peserta.');
            }

            $kelas->delete();

            // Delete photo
            if ($photo) {
                Storage::disk('public')->delete('images/galerikelas/' . $photo);
            }

            DB::commit();

            Log::info('Class deleted', [
                'id' => $id,
                'title' => $title,
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('kelas.index')
                ->with('success', 'Kelas berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('kelas.index')
                ->with('error', 'Kelas tidak ditemukan.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError($e, 'deleting class', ['id' => $id]);
            return redirect()->route('kelas.index')
                ->with('error', 'Gagal menghapus kelas.');
        }
    }
}
