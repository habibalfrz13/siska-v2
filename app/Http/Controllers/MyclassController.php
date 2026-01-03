<?php

namespace App\Http\Controllers;

use App\Http\Traits\HandlesErrors;
use App\Models\Kelas;
use App\Models\Myclass;
use App\Models\Peserta;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MyclassController extends Controller
{
    use HandlesErrors;

    /**
     * Display all enrolled classes (Admin)
     */
    public function index()
    {
        try {
            $myclass = Myclass::with(['kelas', 'user.biodata'])
                ->orderBy('created_at', 'desc')
                ->get();
            $kelas = Kelas::where('status', 'Aktif')->get();

            return view('dashboard.myclass.index', compact('myclass', 'kelas'));
        } catch (\Exception $e) {
            $this->logError($e, 'fetching enrolled classes');
            return redirect()->route('home')
                ->with('error', 'Gagal memuat data kelas.');
        }
    }

    /**
     * Display user's enrolled classes
     */
    public function userIndex()
    {
        try {
            $userId = Auth::id();
            $myclass = Myclass::with(['kelas.kategori', 'kelas.vendor'])
                ->where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->get();

            return view('dashboard.myclass.userIndex', compact('myclass'));
        } catch (\Exception $e) {
            $this->logError($e, 'fetching user enrolled classes');
            return redirect()->route('home')
                ->with('error', 'Gagal memuat kelas Anda.');
        }
    }

    /**
     * Display user's enrolled class details
     */
    public function userIndexDetail()
    {
        try {
            $userId = Auth::id();
            $myclass = Myclass::with(['kelas'])
                ->where('user_id', $userId)
                ->where('status', 'Aktif')
                ->get();

            return view('dashboard.myclass.userIndexDetail', compact('myclass'));
        } catch (\Exception $e) {
            $this->logError($e, 'fetching user class details');
            return redirect()->route('myclass.userIndex')
                ->with('error', 'Gagal memuat detail kelas.');
        }
    }

    /**
     * Show the form for creating a new enrollment
     */
    public function create()
    {
        try {
            $kelas = Kelas::where('status', 'Aktif')->get();
            return view('dashboard.myclass.create', compact('kelas'));
        } catch (\Exception $e) {
            $this->logError($e, 'loading enrollment form');
            return redirect()->route('myclass.index')
                ->with('error', 'Gagal memuat form.');
        }
    }

    /**
     * Store a new class enrollment
     */
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'kelas_id' => 'required|exists:kelas,id',
            'nama_peserta' => 'required|string|max:255',
            'judul' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
        ], [
            'user_id.required' => 'User ID wajib diisi.',
            'kelas_id.required' => 'Kelas wajib dipilih.',
            'nama_peserta.required' => 'Nama peserta wajib diisi.',
        ]);

        try {
            DB::beginTransaction();

            // Security: If not admin, force user_id to be current user
            // This prevents users from enrolling others by manipulating the request
            if (Auth::user()->id_role != 1) {
                $validated['user_id'] = Auth::id();
                // Also ensure nama_peserta matches current user name if desired, 
                // but let's trust the validated user_id for the core relationship
            }

            // Check if user already enrolled
            $existingEnrollment = Myclass::where('user_id', $validated['user_id'])
                ->where('kelas_id', $validated['kelas_id'])
                ->first();

            if ($existingEnrollment) {
                return redirect()->back()
                    ->with('error', 'Anda sudah terdaftar di kelas ini.')
                    ->withInput();
            }

            // Check quota
            $kelas = Kelas::findOrFail($validated['kelas_id']);
            $enrolledCount = Myclass::where('kelas_id', $kelas->id)
                ->where('status', 'Aktif')
                ->count();

            if ($enrolledCount >= $kelas->kuota) {
                return redirect()->back()
                    ->with('error', 'Kuota kelas sudah penuh.')
                    ->withInput();
            }

            // Create enrollment
            $myclass = Myclass::create([
                'user_id' => $validated['user_id'],
                'kelas_id' => $validated['kelas_id'],
                'status' => 'Tidak Aktif',
            ]);

            // Create participant record
            $peserta = Peserta::create([
                'user_id' => $validated['user_id'],
                'kelas_id' => $validated['kelas_id'],
                'myclass_id' => $myclass->id,
                'nama_peserta' => $validated['nama_peserta'],
                'judul' => $validated['judul'],
            ]);

            // Create transaction
            $transaksi = Transaksi::create([
                'user_id' => $validated['user_id'],
                'kelas_id' => $validated['kelas_id'],
                'myclass_id' => $myclass->id,
                'peserta_id' => $peserta->id,
                'jumlah_pembayaran' => $validated['harga'],
                'status_pembayaran' => 'Pending',
                'tanggal_pembayaran' => $request->tanggal_pembayaran,
            ]);

            // Generate Midtrans snap token
            try {
                \Midtrans\Config::$serverKey = config('midtrans.serverKey');
                \Midtrans\Config::$isProduction = config('midtrans.isProduction', false);
                \Midtrans\Config::$isSanitized = true;
                \Midtrans\Config::$is3ds = true;

                $params = [
                    'transaction_details' => [
                        'order_id' => 'ORDER-' . $transaksi->id . '-' . time(),
                        'gross_amount' => (int) $transaksi->jumlah_pembayaran,
                    ],
                    'customer_details' => [
                        'first_name' => Auth::user()->name,
                        'email' => Auth::user()->email,
                    ],
                ];

                $snapToken = \Midtrans\Snap::getSnapToken($params);
                $transaksi->snap_token = $snapToken;
                $transaksi->save();
            } catch (\Exception $e) {
                Log::error('Midtrans error', [
                    'message' => $e->getMessage(),
                    'transaksi_id' => $transaksi->id,
                ]);
                // Continue without snap token - can retry later
            }

            DB::commit();

            Log::info('Class enrollment created', [
                'user_id' => $validated['user_id'],
                'kelas_id' => $validated['kelas_id'],
                'myclass_id' => $myclass->id,
            ]);

            return redirect()->route('myclass.userIndex')
                ->with('success', 'Berhasil mendaftar kelas. Silakan lakukan pembayaran.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError($e, 'creating enrollment', [
                'user_id' => $request->user_id,
                'kelas_id' => $request->kelas_id,
            ]);

            return redirect()->back()
                ->with('error', 'Gagal mendaftar kelas. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Display enrollment details
     */
    public function show($id)
    {
        try {
            $myclass = Myclass::with(['kelas', 'user.biodata'])
                ->join('pesertas', 'myclasses.user_id', '=', 'pesertas.user_id')
                ->where('myclasses.kelas_id', $id)
                ->select('myclasses.id', 'myclasses.status', 'myclasses.foto', 'pesertas.nama_peserta')
                ->groupBy('myclasses.id', 'myclasses.status', 'myclasses.foto', 'pesertas.nama_peserta')
                ->get();

            return view('dashboard.myclass.show', compact('myclass'));
        } catch (\Exception $e) {
            $this->logError($e, 'showing enrollment', ['id' => $id]);
            return redirect()->route('myclass.index')
                ->with('error', 'Gagal memuat detail pendaftaran.');
        }
    }

    /**
     * Show the form for editing enrollment
     */
    public function edit($id)
    {
        try {
            $class = Myclass::with('kelas')->findOrFail($id);
            return view('dashboard.myclass.edit', compact('class'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('myclass.index')
                ->with('error', 'Data tidak ditemukan.');
        } catch (\Exception $e) {
            $this->logError($e, 'loading edit form', ['id' => $id]);
            return redirect()->route('myclass.index')
                ->with('error', 'Gagal memuat form edit.');
        }
    }

    /**
     * Update enrollment status
     */
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $class = Myclass::findOrFail($id);

            // Admin confirmation (no photo required)
            if ($class->foto) {
                $class->update(['status' => 'Aktif']);
                $message = 'Pembayaran telah dikonfirmasi.';
                $redirectRoute = 'myclass.index';
            } else {
                // User uploads payment proof
                $validated = $request->validate([
                    'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                ], [
                    'foto.required' => 'Bukti transfer wajib diunggah.',
                    'foto.image' => 'File harus berupa gambar.',
                    'foto.max' => 'Ukuran gambar maksimal 2MB.',
                ]);

                $image = $request->file('foto');
                $imageName = $image->hashName();
                $image->storeAs('images/buktiTF/', $imageName, 'public');

                $class->update([
                    'foto' => $imageName,
                    'status' => 'Pending',
                ]);

                $message = 'Bukti transfer berhasil diunggah. Menunggu konfirmasi.';
                $redirectRoute = 'myclass.userIndex';
            }

            DB::commit();

            Log::info('Enrollment updated', [
                'id' => $id,
                'status' => $class->status,
                'user_id' => Auth::id(),
            ]);

            return redirect()->route($redirectRoute)->with('success', $message);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('myclass.index')
                ->with('error', 'Data tidak ditemukan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError($e, 'updating enrollment', ['id' => $id]);
            return redirect()->back()
                ->with('error', 'Gagal memperbarui data.')
                ->withInput();
        }
    }

    /**
     * Remove enrollment
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $class = Myclass::findOrFail($id);

            // Delete associated data
            Peserta::where('myclass_id', $id)->delete();
            Transaksi::where('myclass_id', $id)->delete();

            // Delete payment proof if exists
            if ($class->foto) {
                Storage::disk('public')->delete('images/buktiTF/' . $class->foto);
            }

            $class->delete();

            DB::commit();

            Log::info('Enrollment deleted', [
                'id' => $id,
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('myclass.index')
                ->with('success', 'Pendaftaran berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('myclass.index')
                ->with('error', 'Data tidak ditemukan.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError($e, 'deleting enrollment', ['id' => $id]);
            return redirect()->route('myclass.index')
                ->with('error', 'Gagal menghapus data.');
        }
    }
}
