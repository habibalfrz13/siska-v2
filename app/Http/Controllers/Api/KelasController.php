<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\KelasResource;
use App\Models\Kelas;
use App\Models\Myclass;
use App\Models\Peserta;
use App\Models\Transaksi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * API Kelas Controller
 * 
 * Handles class/course operations for mobile app
 */
class KelasController extends Controller
{
    /**
     * Get all available classes
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = Kelas::with(['kategori', 'vendor']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by kategori
        if ($request->has('kategori_id')) {
            $query->where('id_kategori', $request->kategori_id);
        }

        // Search by judul
        if ($request->has('search')) {
            $query->where('judul', 'LIKE', '%' . $request->search . '%');
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 15);
        $kelas = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Daftar kelas berhasil diambil.',
            'data' => KelasResource::collection($kelas),
            'meta' => [
                'current_page' => $kelas->currentPage(),
                'last_page' => $kelas->lastPage(),
                'per_page' => $kelas->perPage(),
                'total' => $kelas->total(),
            ],
        ]);
    }

    /**
     * Get available classes for user (excluding enrolled ones)
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function available(Request $request): JsonResponse
    {
        $userId = Auth::id();

        $kelas = Kelas::with(['kategori', 'vendor'])
            ->where('status', 'aktif')
            ->whereNotExists(function ($query) use ($userId) {
                $query->select(DB::raw(1))
                    ->from('myclasses')
                    ->whereRaw('myclasses.kelas_id = kelas.id')
                    ->where('user_id', $userId);
            })
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Daftar kelas tersedia berhasil diambil.',
            'data' => KelasResource::collection($kelas),
            'meta' => [
                'current_page' => $kelas->currentPage(),
                'last_page' => $kelas->lastPage(),
                'per_page' => $kelas->perPage(),
                'total' => $kelas->total(),
            ],
        ]);
    }

    /**
     * Get single class detail
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $kelas = Kelas::with(['kategori', 'vendor'])->find($id);

        if (!$kelas) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new KelasResource($kelas),
        ]);
    }

    /**
     * Enroll in a class
     * 
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function enroll(Request $request, int $id): JsonResponse
    {
        $user = Auth::user();
        $kelas = Kelas::find($id);

        if (!$kelas) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas tidak ditemukan.',
            ], 404);
        }

        if ($kelas->status !== 'Aktif') {
            return response()->json([
                'success' => false,
                'message' => 'Kelas tidak tersedia untuk pendaftaran.',
            ], 400);
        }

        // Check if already enrolled
        $existingEnrollment = Myclass::where('kelas_id', $id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingEnrollment) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah terdaftar di kelas ini.',
            ], 400);
        }

        // Check quota
        $enrolledCount = Myclass::where('kelas_id', $id)->count();
        if ($enrolledCount >= $kelas->kuota) {
            return response()->json([
                'success' => false,
                'message' => 'Kuota kelas sudah penuh.',
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Create myclass entry (same as web)
            $myclass = Myclass::create([
                'kelas_id' => $id,
                'user_id' => $user->id,
                'status' => 'Tidak Aktif',
            ]);

            // Create participant record (same as web)
            $peserta = Peserta::create([
                'user_id' => $user->id,
                'kelas_id' => $id,
                'myclass_id' => $myclass->id,
                'nama_peserta' => $user->name,
                'judul' => $kelas->judul,
            ]);

            // Create transaction with myclass_id and peserta_id (same as web)
            $transaksi = Transaksi::create([
                'user_id' => $user->id,
                'kelas_id' => $id,
                'myclass_id' => $myclass->id,
                'peserta_id' => $peserta->id,
                'jumlah_pembayaran' => $kelas->harga,
                'status_pembayaran' => 'Pending',
                'tanggal_pembayaran' => null,
            ]);

            // Generate Midtrans snap token (same as web)
            $snapToken = null;
            try {
                \Midtrans\Config::$serverKey = config('midtrans.serverKey');
                \Midtrans\Config::$isProduction = config('midtrans.isProduction', false);
                \Midtrans\Config::$isSanitized = true;
                \Midtrans\Config::$is3ds = true;

                $params = [
                    'transaction_details' => [
                        'order_id' => 'SISKA-' . $transaksi->id . '-' . time(),
                        'gross_amount' => (int) $transaksi->jumlah_pembayaran,
                    ],
                    'customer_details' => [
                        'first_name' => $user->name,
                        'email' => $user->email,
                    ],
                ];

                $snapToken = \Midtrans\Snap::getSnapToken($params);
                $transaksi->snap_token = $snapToken;
                $transaksi->save();
            } catch (\Exception $e) {
                Log::error('Midtrans error (API)', [
                    'message' => $e->getMessage(),
                    'transaksi_id' => $transaksi->id,
                ]);
                // Continue without snap token - can retry later
            }

            DB::commit();

            Log::info('API Class enrollment created', [
                'user_id' => $user->id,
                'kelas_id' => $id,
                'myclass_id' => $myclass->id,
                'peserta_id' => $peserta->id,
                'transaksi_id' => $transaksi->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran kelas berhasil. Silakan lakukan pembayaran.',
                'data' => [
                    'enrollment_id' => $myclass->id,
                    'peserta_id' => $peserta->id,
                    'transaction_id' => $transaksi->id,
                    'snap_token' => $snapToken,
                    'amount' => $kelas->harga,
                    'status' => 'Pending',
                ],
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mendaftar kelas.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get user's enrolled classes
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function myClasses(Request $request): JsonResponse
    {
        $userId = Auth::id();

        $myClasses = Myclass::with(['kelas.kategori', 'kelas.vendor'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        $data = $myClasses->map(function ($item) {
            return [
                'id' => $item->id,
                'enrollment_status' => $item->status,
                'enrolled_at' => $item->created_at->format('Y-m-d H:i:s'),
                'kelas' => new KelasResource($item->kelas),
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Daftar kelas saya berhasil diambil.',
            'data' => $data,
            'meta' => [
                'current_page' => $myClasses->currentPage(),
                'last_page' => $myClasses->lastPage(),
                'per_page' => $myClasses->perPage(),
                'total' => $myClasses->total(),
            ],
        ]);
    }

    /**
     * Admin: Create a new class
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'kuota' => 'required|integer|min:1',
            'pelaksanaan' => 'required|date',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'id_kategori' => 'required|exists:kategoris,id',
            'id_vendor' => 'required|exists:vendors,id',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $request->except('foto');
        $data['status'] = 'Tidak Aktif';

        if ($request->hasFile('foto')) {
            $image = $request->file('foto');
            $image->storeAs('images/galerikelas/', $image->hashName());
            $data['foto'] = $image->hashName();
        }

        $kelas = Kelas::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Kelas berhasil dibuat.',
            'data' => new KelasResource($kelas),
        ], 201);
    }

    /**
     * Admin: Update a class
     * 
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $kelas = Kelas::find($id);

        if (!$kelas) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas tidak ditemukan.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'judul' => 'sometimes|string|max:255',
            'kuota' => 'sometimes|integer|min:1',
            'pelaksanaan' => 'sometimes|date',
            'harga' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:Aktif,Tidak Aktif',
            'deskripsi' => 'nullable|string',
            'id_kategori' => 'sometimes|exists:kategoris,id',
            'id_vendor' => 'sometimes|exists:vendors,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $kelas->update($request->only([
            'judul', 'kuota', 'pelaksanaan', 'harga', 
            'status', 'deskripsi', 'id_kategori', 'id_vendor'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Kelas berhasil diperbarui.',
            'data' => new KelasResource($kelas->fresh()),
        ]);
    }

    /**
     * Admin: Delete a class
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $kelas = Kelas::find($id);

        if (!$kelas) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas tidak ditemukan.',
            ], 404);
        }

        // Check if there are active enrollments
        $hasEnrollments = Myclass::where('kelas_id', $id)->exists();
        if ($hasEnrollments) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus kelas yang memiliki peserta.',
            ], 400);
        }

        $kelas->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kelas berhasil dihapus.',
        ]);
    }
}
