<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Kelas;
use App\Models\Myclass;
use App\Models\UserProgress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * API Certificate Controller
 * 
 * Handles certificate operations for mobile app
 */
class CertificateController extends Controller
{
    /**
     * Display user's certificates
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $certificates = Certificate::with(['kelas.kategori', 'kelas.vendor'])
            ->where('user_id', $user->id)
            ->orderBy('issued_at', 'desc')
            ->get();

        $data = $certificates->map(function ($certificate) {
            return [
                'id' => $certificate->id,
                'certificate_number' => $certificate->certificate_number,
                'course_name' => $certificate->kelas->judul,
                'course_image' => $certificate->kelas->thumbnail ? url('storage/' . $certificate->kelas->thumbnail) : null,
                'issued_at' => $certificate->issued_at->format('Y-m-d'),
                'download_url' => route('certificates.download', $certificate->id), // Reusing web route for download if session auth works, otherwise might need API specific download
                'public_url' => route('certificates.verify', $certificate->certificate_number),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Generate certificate for a class
     * 
     * @param Request $request
     * @param int $kelasId
     * @return JsonResponse
     */
    public function generate(Request $request, int $kelasId): JsonResponse
    {
        $user = $request->user();

        // Check enrollment
        $enrollment = Myclass::where('user_id', $user->id)
            ->where('kelas_id', $kelasId)
            ->where('status', 'Aktif')
            ->first();

        if (!$enrollment) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke kelas ini.',
            ], 403);
        }

        // Check if already exists
        if (Certificate::where('user_id', $user->id)->where('kelas_id', $kelasId)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Sertifikat sudah diterbitkan sebelumnya.',
            ], 422); // Unprocessable Entity
        }

        $kelas = Kelas::with('publishedModules.publishedMaterials')->findOrFail($kelasId);

        // Verify progress
        $totalMaterials = 0;
        $completedMaterials = 0;

        foreach ($kelas->publishedModules as $module) {
            foreach ($module->publishedMaterials as $material) {
                $totalMaterials++;
                if ($material->isCompletedByUser($user->id)) {
                    $completedMaterials++;
                }
            }
        }

        if ($totalMaterials === 0 || $completedMaterials < $totalMaterials) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum menyelesaikan seluruh materi kelas ini.',
                'progress' => [
                    'total' => $totalMaterials,
                    'completed' => $completedMaterials,
                    'percent' => $totalMaterials > 0 ? round(($completedMaterials / $totalMaterials) * 100) : 0,
                ]
            ], 400); // Bad Request
        }

        // Get completion date
        $lastProgress = UserProgress::where('user_id', $user->id)
            ->whereNotNull('completed_at')
            ->orderBy('completed_at', 'desc')
            ->first();

        // Create certificate
        $certificate = Certificate::create([
            'user_id' => $user->id,
            'kelas_id' => $kelasId,
            'certificate_number' => Certificate::generateCertificateNumber(),
            'issued_at' => now(),
            'completion_date' => $lastProgress ? $lastProgress->completed_at->toDateString() : now()->toDateString(),
            'is_valid' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Sertifikat berhasil diterbitkan.',
            'data' => [
                'id' => $certificate->id,
                'certificate_number' => $certificate->certificate_number,
                'download_url' => route('certificates.download', $certificate->id),
            ]
        ], 201);
    }

    /**
     * Show certificate details
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $user = Auth::user();
        
        $certificate = Certificate::with(['kelas.kategori', 'kelas.vendor'])
            ->where('user_id', $user->id)
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $certificate->id,
                'certificate_number' => $certificate->certificate_number,
                'course_name' => $certificate->kelas->judul,
                'issued_at' => $certificate->issued_at->format('Y-m-d'),
                'completion_date' => $certificate->completion_date->format('Y-m-d'),
                'download_url' => route('certificates.download', $certificate->id),
                'public_url' => route('certificates.verify', $certificate->certificate_number),
            ]
        ]);
    }
}
