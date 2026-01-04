<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransaksiResource;
use App\Models\Myclass;
use App\Models\Peserta;
use App\Models\Transaksi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * API Transaksi Controller
 * 
 * Handles transaction operations for mobile app
 */
class TransaksiController extends Controller
{
    /**
     * Get user's transactions
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        $query = Transaksi::with(['kelas'])
            ->where('user_id', $user->id);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status_pembayaran', $request->status);
        }

        // Date range filter
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('tanggal_transaksi', [
                $request->start_date,
                $request->end_date,
            ]);
        }

        $transaksis = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Daftar transaksi berhasil diambil.',
            'data' => TransaksiResource::collection($transaksis),
            'meta' => [
                'current_page' => $transaksis->currentPage(),
                'last_page' => $transaksis->lastPage(),
                'per_page' => $transaksis->perPage(),
                'total' => $transaksis->total(),
            ],
        ]);
    }

    /**
     * Get all transactions (Admin only)
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function all(Request $request): JsonResponse
    {
        $query = Transaksi::with(['user.biodata', 'kelas']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status_pembayaran', $request->status);
        }

        // Search by user name
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%');
            });
        }

        // Date range filter
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('tanggal_transaksi', [
                $request->start_date,
                $request->end_date,
            ]);
        }

        $transaksis = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Daftar semua transaksi berhasil diambil.',
            'data' => TransaksiResource::collection($transaksis),
            'meta' => [
                'current_page' => $transaksis->currentPage(),
                'last_page' => $transaksis->lastPage(),
                'per_page' => $transaksis->perPage(),
                'total' => $transaksis->total(),
            ],
        ]);
    }

    /**
     * Get transaction detail
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $user = Auth::user();
        
        $transaksi = Transaksi::with(['kelas.kategori', 'kelas.vendor', 'user.biodata'])
            ->find($id);

        if (!$transaksi) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan.',
            ], 404);
        }

        // Check ownership (unless admin)
        if ($user->id_role != 1 && $transaksi->user_id != $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke transaksi ini.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => new TransaksiResource($transaksi),
        ]);
    }

    /**
     * Get pending transaction for a class
     * 
     * @param int $kelasId
     * @return JsonResponse
     */
    public function pending(int $kelasId): JsonResponse
    {
        $userId = Auth::id();

        $transaksi = Transaksi::with(['kelas'])
            ->where('user_id', $userId)
            ->where('kelas_id', $kelasId)
            ->where('status_pembayaran', 'pending')
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$transaksi) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada transaksi pending untuk kelas ini.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new TransaksiResource($transaksi),
        ]);
    }

    /**
     * Confirm payment (mark as successful)
     * 
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function confirmPayment(Request $request, int $id): JsonResponse
    {
        $transaksi = Transaksi::find($id);

        if (!$transaksi) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan.',
            ], 404);
        }

        if ($transaksi->status_pembayaran === 'Berhasil') {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi sudah berhasil sebelumnya.',
            ], 400);
        }

        try {
            DB::beginTransaction();

            $transaksi->update([
                'status_pembayaran' => 'Berhasil',
            ]);

            // Activate the class enrollment using myclass_id directly
            $myclass = $transaksi->myclass;

            if ($myclass) {
                $myclass->update(['status' => 'Aktif']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil dikonfirmasi.',
                'data' => new TransaksiResource($transaksi->fresh()),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengkonfirmasi pembayaran.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Cancel transaction
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function cancel(int $id): JsonResponse
    {
        $user = Auth::user();
        $transaksi = Transaksi::find($id);

        if (!$transaksi) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan.',
            ], 404);
        }

        // Check ownership (unless admin)
        if ($user->id_role != 1 && $transaksi->user_id != $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke transaksi ini.',
            ], 403);
        }

        if ($transaksi->status_pembayaran === 'Berhasil') {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi yang sudah berhasil tidak dapat dibatalkan.',
            ], 400);
        }

        try {
            DB::beginTransaction();

            $transaksi->update([
                'status_pembayaran' => 'Gagal',
            ]);

            // Remove class enrollment and participant using myclass_id
            if ($transaksi->myclass_id) {
                Peserta::where('myclass_id', $transaksi->myclass_id)->delete();
                Myclass::where('id', $transaksi->myclass_id)->delete();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dibatalkan.',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membatalkan transaksi.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get transaction statistics (Admin only)
     * 
     * @return JsonResponse
     */
    public function statistics(): JsonResponse
    {
        $stats = [
            'total_transactions' => Transaksi::count(),
            'successful_transactions' => Transaksi::where('status_pembayaran', 'Berhasil')->count(),
            'pending_transactions' => Transaksi::where('status_pembayaran', 'pending')->count(),
            'failed_transactions' => Transaksi::where('status_pembayaran', 'Gagal')->count(),
            'total_revenue' => Transaksi::where('status_pembayaran', 'Berhasil')->sum('jumlah_pembayaran'),
            'today_revenue' => Transaksi::where('status_pembayaran', 'Berhasil')
                ->whereDate('updated_at', today())
                ->sum('jumlah_pembayaran'),
            'this_month_revenue' => Transaksi::where('status_pembayaran', 'Berhasil')
                ->whereMonth('updated_at', now()->month)
                ->whereYear('updated_at', now()->year)
                ->sum('jumlah_pembayaran'),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }
}
