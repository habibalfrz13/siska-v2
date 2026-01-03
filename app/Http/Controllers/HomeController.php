<?php

namespace App\Http\Controllers;

use App\Http\Traits\HandlesErrors;
use App\Models\Kelas;
use App\Models\Myclass;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    use HandlesErrors;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        // Redirect based on role
        if ($user->id_role == 1) {
            return $this->adminDashboard();
        }

        return $this->userDashboard();
    }

    /**
     * Admin Dashboard
     */
    protected function adminDashboard()
    {
        try {
            // Kelas statistics
            $total = Kelas::count();
            $aktif = Kelas::where('status', 'Aktif')->count();
            $noaktif = Kelas::where('status', 'Tidak Aktif')->count();
            $certifications = Kelas::with(['kategori', 'vendor'])
                ->orderBy('pelaksanaan', 'desc')
                ->get();

            // Transaction statistics
            $successfulSales = Transaksi::where('status_pembayaran', 'Berhasil')->count();
            $totalRevenue = Transaksi::where('status_pembayaran', 'Berhasil')
                ->sum('jumlah_pembayaran') ?? 0;

            return view('dashboard.home', [
                'total' => $total,
                'aktif' => $aktif,
                'noaktif' => $noaktif,
                'certifications' => $certifications,
                'successfulSales' => $successfulSales,
                'totalRevenue' => $totalRevenue,
            ]);
        } catch (\Exception $e) {
            $this->logError($e, 'loading admin dashboard');

            return view('dashboard.home', [
                'total' => 0,
                'aktif' => 0,
                'noaktif' => 0,
                'certifications' => collect(),
                'successfulSales' => 0,
                'totalRevenue' => 0,
            ])->with('error', 'Gagal memuat beberapa data.');
        }
    }

    /**
     * User Dashboard
     */
    protected function userDashboard()
    {
        try {
            $userId = Auth::id();

            // Get greeting based on time
            $hour = Carbon::now()->hour;
            if ($hour < 12) {
                $greeting = 'Pagi';
            } elseif ($hour < 15) {
                $greeting = 'Siang';
            } elseif ($hour < 18) {
                $greeting = 'Sore';
            } else {
                $greeting = 'Malam';
            }

            // User class statistics
            $enrolledClasses = Myclass::where('user_id', $userId)->count();
            $activeClasses = Myclass::where('user_id', $userId)
                ->where('status', 'Aktif')
                ->count();
            $completedClasses = Myclass::where('user_id', $userId)
                ->where('status', 'Aktif')
                ->whereHas('kelas', function ($query) {
                    $query->where('pelaksanaan', '<', Carbon::now());
                })
                ->count();

            // Pending payments
            $pendingPayments = Transaksi::where('user_id', $userId)
                ->where('status_pembayaran', 'Pending')
                ->count();

            // User's classes
            $myClasses = Myclass::with(['kelas.kategori', 'kelas.vendor'])
                ->where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->take(4)
                ->get();

            // Recommended classes (classes not enrolled yet)
            $enrolledKelasIds = Myclass::where('user_id', $userId)->pluck('kelas_id');
            $recommendedClasses = Kelas::where('status', 'Aktif')
                ->whereNotIn('id', $enrolledKelasIds)
                ->where('pelaksanaan', '>=', Carbon::now())
                ->orderBy('created_at', 'desc')
                ->take(4)
                ->get();

            return view('dashboard.user.home', [
                'greeting' => $greeting,
                'enrolledClasses' => $enrolledClasses,
                'activeClasses' => $activeClasses,
                'completedClasses' => $completedClasses,
                'pendingPayments' => $pendingPayments,
                'myClasses' => $myClasses,
                'recommendedClasses' => $recommendedClasses,
            ]);
        } catch (\Exception $e) {
            $this->logError($e, 'loading user dashboard');

            return view('dashboard.user.home', [
                'greeting' => 'Pagi',
                'enrolledClasses' => 0,
                'activeClasses' => 0,
                'completedClasses' => 0,
                'pendingPayments' => 0,
                'myClasses' => collect(),
                'recommendedClasses' => collect(),
            ])->with('error', 'Gagal memuat beberapa data.');
        }
    }
}
