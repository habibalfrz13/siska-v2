<?php

namespace App\Http\Controllers;

use App\Http\Traits\HandlesErrors;
use App\Models\Myclass;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransaksiController extends Controller
{
    use HandlesErrors;

    /**
     * Display all transactions (Admin)
     */
    public function index()
    {
        try {
            $transaksis = Transaksi::with(['user.biodata', 'kelas'])
                ->orderBy('created_at', 'desc')
                ->get();

            return view('dashboard.transaksi.index', compact('transaksis'));
        } catch (\Exception $e) {
            $this->logError($e, 'fetching transactions');
            return redirect()->route('home')
                ->with('error', 'Gagal memuat data transaksi.');
        }
    }

    /**
     * Display user's pending transaction
     */
    public function userIndex($id)
    {
        try {
            $userId = Auth::id();
            $transaksi = Transaksi::with(['kelas.kategori', 'kelas.vendor'])
                ->where('user_id', $userId)
                ->where('status_pembayaran', 'pending')
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$transaksi) {
                return redirect()->route('myclass.userIndex')
                    ->with('error', 'Tidak ada transaksi pending.');
            }

            return view('dashboard.transaksi.userIndex', compact('transaksi'));
        } catch (\Exception $e) {
            $this->logError($e, 'fetching user transaction', ['id' => $id]);
            return redirect()->route('myclass.userIndex')
                ->with('error', 'Gagal memuat transaksi.');
        }
    }

    /**
     * Mark transaction as successful
     */
    public function sukses($id)
    {
        try {
            DB::beginTransaction();

            $transaksi = Transaksi::findOrFail($id);

            // Verify ownership
            if ($transaksi->user_id !== Auth::id() && Auth::user()->id_role !== 1) {
                return redirect()->route('myclass.userIndex')
                    ->with('error', 'Anda tidak memiliki akses ke transaksi ini.');
            }

            $transaksi->status_pembayaran = 'Berhasil';
            $transaksi->tanggal_pembayaran = now();
            $transaksi->save();

            // Activate the class enrollment
            $myclass = Myclass::where('kelas_id', $transaksi->kelas_id)
                ->where('user_id', $transaksi->user_id)
                ->latest()
                ->first();

            if ($myclass) {
                $myclass->status = 'Aktif';
                $myclass->save();
            }

            DB::commit();

            Log::info('Transaction successful', [
                'transaksi_id' => $id,
                'user_id' => $transaksi->user_id,
                'kelas_id' => $transaksi->kelas_id,
            ]);

            return redirect()->route('myclass.userIndex')
                ->with('success', 'Pembayaran berhasil! Kelas sudah aktif.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('myclass.userIndex')
                ->with('error', 'Transaksi tidak ditemukan.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError($e, 'confirming payment', ['id' => $id]);
            return redirect()->route('myclass.userIndex')
                ->with('error', 'Gagal mengkonfirmasi pembayaran.');
        }
    }

    /**
     * Cancel transaction
     */
    public function batalkan($id)
    {
        try {
            DB::beginTransaction();

            $transaksi = Transaksi::findOrFail($id);

            // Verify ownership
            if ($transaksi->user_id !== Auth::id() && Auth::user()->id_role !== 1) {
                return redirect()->route('myclass.userIndex')
                    ->with('error', 'Anda tidak memiliki akses ke transaksi ini.');
            }

            // Update transaction status
            $transaksi->status_pembayaran = 'Gagal';
            $transaksi->save();

            // Delete associated Myclass records
            Myclass::where('kelas_id', $transaksi->kelas_id)
                ->where('user_id', $transaksi->user_id)
                ->delete();

            DB::commit();

            Log::info('Transaction cancelled', [
                'transaksi_id' => $id,
                'user_id' => $transaksi->user_id,
            ]);

            return redirect()->route('kelas.userIndex')
                ->with('success', 'Transaksi berhasil dibatalkan.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('myclass.userIndex')
                ->with('error', 'Transaksi tidak ditemukan.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError($e, 'cancelling transaction', ['id' => $id]);
            return redirect()->route('myclass.userIndex')
                ->with('error', 'Gagal membatalkan transaksi.');
        }
    }

    /**
     * Show the form for creating a new transaction
     */
    public function create()
    {
        return view('transaksi.create');
    }

    /**
     * Store a new transaction
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'kelas_id' => 'required|exists:kelas,id',
            'metode_pembayaran' => 'required|string',
            'jumlah_pembayaran' => 'required|integer|min:0',
            'total_harga' => 'required|numeric|min:0',
            'tanggal_transaksi' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            Transaksi::create($validated);

            DB::commit();

            return redirect()->route('transaksi.index')
                ->with('success', 'Transaksi berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError($e, 'creating transaction');
            return redirect()->back()
                ->with('error', 'Gagal membuat transaksi.')
                ->withInput();
        }
    }

    /**
     * Display transaction details
     */
    public function show($id)
    {
        try {
            $transaksi = Transaksi::with(['user.biodata', 'kelas'])->findOrFail($id);
            
            // Verify ownership or admin access
            if ($transaksi->user_id !== Auth::id() && Auth::user()->id_role !== 1) {
                return redirect()->route('myclass.userIndex')
                    ->with('error', 'Anda tidak memiliki akses ke transaksi ini.');
            }
            
            return view('transaksi.show', compact('transaksi'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('transaksi.index')
                ->with('error', 'Transaksi tidak ditemukan.');
        } catch (\Exception $e) {
            $this->logError($e, 'showing transaction', ['id' => $id]);
            return redirect()->route('transaksi.index')
                ->with('error', 'Gagal memuat detail transaksi.');
        }
    }

    /**
     * Show the form for editing transaction
     */
    public function edit($id)
    {
        try {
            $transaksi = Transaksi::findOrFail($id);
            
            // Only admin can edit transactions
            if (Auth::user()->id_role !== 1) {
                return redirect()->route('myclass.userIndex')
                    ->with('error', 'Anda tidak memiliki akses untuk mengedit transaksi.');
            }
            
            return view('transaksi.edit', compact('transaksi'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('transaksi.index')
                ->with('error', 'Transaksi tidak ditemukan.');
        } catch (\Exception $e) {
            $this->logError($e, 'loading edit form', ['id' => $id]);
            return redirect()->route('transaksi.index')
                ->with('error', 'Gagal memuat form edit.');
        }
    }

    /**
     * Update transaction
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status_pembayaran' => 'required|in:Pending,Berhasil,Gagal',
        ]);

        try {
            DB::beginTransaction();

            $transaksi = Transaksi::findOrFail($id);
            $transaksi->update($validated);

            // Update myclass status accordingly
            if ($validated['status_pembayaran'] === 'Berhasil') {
                Myclass::where('id', $transaksi->myclass_id)
                    ->update(['status' => 'Aktif']);
            }

            DB::commit();

            Log::info('Transaction updated', [
                'id' => $id,
                'status' => $validated['status_pembayaran'],
            ]);

            return redirect()->route('transaksi.index')
                ->with('success', 'Transaksi berhasil diperbarui.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('transaksi.index')
                ->with('error', 'Transaksi tidak ditemukan.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError($e, 'updating transaction', ['id' => $id]);
            return redirect()->route('transaksi.index')
                ->with('error', 'Gagal memperbarui transaksi.');
        }
    }

    /**
     * Remove transaction
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $transaksi = Transaksi::findOrFail($id);
            $transaksi->delete();

            DB::commit();

            Log::info('Transaction deleted', ['id' => $id]);

            return redirect()->route('transaksi.index')
                ->with('success', 'Transaksi berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('transaksi.index')
                ->with('error', 'Transaksi tidak ditemukan.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError($e, 'deleting transaction', ['id' => $id]);
            return redirect()->route('transaksi.index')
                ->with('error', 'Gagal menghapus transaksi.');
        }
    }

    /**
     * Print all transactions report
     */
    public function cetak()
    {
        try {
            $transaksis = Transaksi::with(['user.biodata', 'kelas'])->get();
            $totalPembayaran = $transaksis->sum('jumlah_pembayaran');

            return view('dashboard.transaksi.cetak', compact('transaksis', 'totalPembayaran'));
        } catch (\Exception $e) {
            $this->logError($e, 'generating print report');
            return redirect()->route('transaksi.index')
                ->with('error', 'Gagal membuat laporan.');
        }
    }

    /**
     * Print successful transactions report
     */
    public function cetaksuccess()
    {
        try {
            $transaksis = Transaksi::with(['user.biodata', 'kelas'])
                ->where('status_pembayaran', 'Berhasil')
                ->orderBy('tanggal_pembayaran', 'desc')
                ->get();
            $totalPembayaran = $transaksis->sum('jumlah_pembayaran');

            return view('dashboard.transaksi.cetaksuccess', compact('transaksis', 'totalPembayaran'));
        } catch (\Exception $e) {
            $this->logError($e, 'generating success report');
            return redirect()->route('transaksi.index')
                ->with('error', 'Gagal membuat laporan.');
        }
    }
}
