<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\CourseModule;
use App\Models\Kelas;
use App\Models\Myclass;
use App\Models\UserProgress;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    /**
     * Display user's certificates
     */
    public function index()
    {
        try {
            $certificates = Certificate::with(['kelas', 'user'])
                ->where('user_id', Auth::id())
                ->orderBy('issued_at', 'desc')
                ->get();

            return view('dashboard.certificates.index', compact('certificates'));
        } catch (\Exception $e) {
            Log::error('Certificate index error', ['message' => $e->getMessage()]);
            return redirect()->route('home')
                ->with('error', 'Gagal memuat daftar sertifikat.');
        }
    }

    /**
     * Generate certificate for a class
     */
    public function generate($kelasId)
    {
        try {
            $userId = Auth::id();

            // Check if user has access
            $myClass = Myclass::where('user_id', $userId)
                ->where('kelas_id', $kelasId)
                ->where('status', 'Aktif')
                ->first();

            if (!$myClass) {
                return redirect()->back()
                    ->with('error', 'Anda tidak memiliki akses ke kelas ini.');
            }

            $kelas = Kelas::with('publishedModules.publishedMaterials')->findOrFail($kelasId);

            // Check if already has certificate
            if (Certificate::existsFor($userId, $kelasId)) {
                $certificate = Certificate::getFor($userId, $kelasId);
                return redirect()->route('certificates.show', $certificate->id)
                    ->with('info', 'Anda sudah memiliki sertifikat untuk kelas ini.');
            }

            // Calculate progress
            $totalMaterials = 0;
            $completedMaterials = 0;

            foreach ($kelas->publishedModules as $module) {
                foreach ($module->publishedMaterials as $material) {
                    $totalMaterials++;
                    if ($material->isCompletedByUser($userId)) {
                        $completedMaterials++;
                    }
                }
            }

            // Check if 100% complete
            if ($totalMaterials === 0 || $completedMaterials < $totalMaterials) {
                $progressPercent = $totalMaterials > 0 
                    ? round(($completedMaterials / $totalMaterials) * 100) 
                    : 0;
                return redirect()->back()
                    ->with('error', "Anda harus menyelesaikan 100% materi. Progress saat ini: {$progressPercent}%");
            }

            // Get completion date (last material completed)
            $lastProgress = UserProgress::where('user_id', $userId)
                ->whereNotNull('completed_at')
                ->orderBy('completed_at', 'desc')
                ->first();

            // Create certificate
            $certificate = Certificate::create([
                'user_id' => $userId,
                'kelas_id' => $kelasId,
                'certificate_number' => Certificate::generateCertificateNumber(),
                'issued_at' => now(),
                'completion_date' => $lastProgress ? $lastProgress->completed_at->toDateString() : now()->toDateString(),
                'is_valid' => true,
            ]);

            Log::info('Certificate generated', [
                'certificate_id' => $certificate->id,
                'user_id' => $userId,
                'kelas_id' => $kelasId,
            ]);

            return redirect()->route('certificates.show', $certificate->id)
                ->with('success', 'Selamat! Sertifikat Anda berhasil dibuat.');
        } catch (\Exception $e) {
            Log::error('Certificate generation error', ['message' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Gagal membuat sertifikat. Silakan coba lagi.');
        }
    }

    /**
     * Show certificate
     */
    public function show($id)
    {
        try {
            $certificate = Certificate::with(['user', 'kelas.vendor', 'kelas.kategori'])
                ->where('user_id', Auth::id())
                ->findOrFail($id);

            return view('dashboard.certificates.show', compact('certificate'));
        } catch (\Exception $e) {
            Log::error('Certificate show error', ['message' => $e->getMessage()]);
            return redirect()->route('certificates.index')
                ->with('error', 'Sertifikat tidak ditemukan.');
        }
    }

    /**
     * Download certificate as PDF
     */
    public function download($id)
    {
        try {
            $certificate = Certificate::with(['user', 'kelas.vendor'])
                ->where('user_id', Auth::id())
                ->findOrFail($id);

            $pdf = Pdf::loadView('dashboard.certificates.pdf', compact('certificate'))
                ->setPaper('a4', 'landscape')
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                    'defaultFont' => 'sans-serif',
                ]);

            $filename = 'Sertifikat_' . str_replace(' ', '_', $certificate->user->name) . '_' . $certificate->certificate_number . '.pdf';

            return $pdf->download($filename);
        } catch (\Exception $e) {
            Log::error('Certificate download error', ['message' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Gagal mengunduh sertifikat.');
        }
    }

    /**
     * Public verification page
     */
    public function verify($certificateNumber)
    {
        $certificate = Certificate::with(['user', 'kelas'])
            ->where('certificate_number', $certificateNumber)
            ->first();

        $isValid = $certificate && $certificate->is_valid;

        return view('dashboard.certificates.verify', compact('certificate', 'isValid', 'certificateNumber'));
    }
}
