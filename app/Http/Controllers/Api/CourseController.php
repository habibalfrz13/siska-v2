<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CourseMaterial;
use App\Models\CourseModule;
use App\Models\Kelas;
use App\Models\Myclass;
use App\Models\UserProgress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * API Course Controller
 * 
 * Handles learning flow: accessing courses, modules, and materials
 */
class CourseController extends Controller
{
    /**
     * List user's enrolled active classes for learning
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        // Get enrolled classes that are active
        $myClasses = Myclass::with(['kelas.kategori', 'kelas.vendor'])
            ->where('user_id', $user->id)
            ->where('status', 'Aktif')
            ->orderBy('created_at', 'desc')
            ->get();

        $data = $myClasses->map(function ($myClass) use ($user) {
            // Calculate progress
            $totalMaterials = 0;
            $completedMaterials = 0;
            
            $kelas = $myClass->kelas;

            // Note: In a real app we might want to eager load these relationships carefully
            // But for now we'll stick to the logic used in web controller
            $modules = $kelas->publishedModules; // Assuming relation exists and scope applies
            
            foreach ($modules as $module) {
                foreach ($module->publishedMaterials as $material) {
                    $totalMaterials++;
                    if ($material->isCompletedByUser($user->id)) {
                        $completedMaterials++;
                    }
                }
            }

            $progress = $totalMaterials > 0 ? round(($completedMaterials / $totalMaterials) * 100) : 0;

            return [
                'id' => $kelas->id,
                'judul' => $kelas->judul,
                'deskripsi_singkat' => $kelas->deskripsi, // Using deskripsi as summary
                'thumbnail' => $kelas->thumbnail ? url('storage/' . $kelas->thumbnail) : null,
                'kategori' => $kelas->kategori ? $kelas->kategori->nama : null,
                'vendor' => $kelas->vendor ? $kelas->vendor->nama : null,
                'progress_percent' => $progress,
                'total_materials' => $totalMaterials,
                'completed_materials' => $completedMaterials,
                'enrolled_at' => $myClass->created_at->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get course details with hierarchical modules and materials
     * 
     * @param int $id Class ID
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $user = Auth::user();

        // Validate enrollment
        $enrollment = Myclass::where('user_id', $user->id)
            ->where('kelas_id', $id)
            ->where('status', 'Aktif')
            ->first();

        if (!$enrollment) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum terdaftar di kelas ini atau status belum aktif.',
            ], 403);
        }

        $kelas = Kelas::with(['vendor', 'kategori'])->findOrFail($id);
        
        // Get modules with materials
        // We use the published scopes to ensure content is visible
        $modules = CourseModule::where('kelas_id', $id)
            ->where('is_published', true)
            ->with(['publishedMaterials' => function($query) {
                $query->orderBy('order');
            }])
            ->orderBy('order')
            ->get();

        $modulesData = $modules->map(function ($module) use ($user) {
            return [
                'id' => $module->id,
                'title' => $module->title,
                'materials' => $module->publishedMaterials->map(function ($material) use ($user) {
                    return [
                        'id' => $material->id,
                        'title' => $material->title,
                        'type' => $material->type, // video, document, quiz, etc.
                        'duration_minutes' => $material->duration_minutes,
                        'is_completed' => $material->isCompletedByUser($user->id),
                    ];
                }),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'course' => [
                    'id' => $kelas->id,
                    'judul' => $kelas->judul,
                    'deskripsi' => $kelas->deskripsi,
                    'thumbnail' => $kelas->thumbnail ? url('storage/' . $kelas->thumbnail) : null,
                    'mentor' => $kelas->vendor ? $kelas->vendor->nama : 'Instruktur',
                ],
                'modules' => $modulesData,
            ],
        ]);
    }

    /**
     * Get specific material content
     * 
     * @param int $id Material ID
     * @return JsonResponse
     */
    public function material($id): JsonResponse
    {
        $user = Auth::user();
        
        $material = CourseMaterial::with(['module.kelas'])->findOrFail($id);
        
        // Validate access through enrollment in the class
        $kelasId = $material->module->kelas_id;
        $enrollment = Myclass::where('user_id', $user->id)
            ->where('kelas_id', $kelasId)
            ->where('status', 'Aktif')
            ->first();

        if (!$enrollment) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke materi ini.',
            ], 403);
        }

        // Get prev/next navigation
        // Logic: Flatten all materials in the course and find neighbors
        // For simplicity in this iteration, we might skip complex next/prev logic or keep it simple
        $nextMaterialId = null;
        $prevMaterialId = null;
        
        // Basic implementation for next/prev could go here if needed
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $material->id,
                'title' => $material->title,
                'type' => $material->type,
                'content' => $material->content, // Text content
                'file_url' => $material->file_path ? url('storage/' . $material->file_path) : null,
                'video_url' => $material->video_url,
                'duration_minutes' => $material->duration_minutes,
                'is_completed' => $material->isCompletedByUser($user->id),
                'module_id' => $material->module_id,
                'module_title' => $material->module->title,
            ],
        ]);
    }

    /**
     * Mark material as completed
     * 
     * @param Request $request
     * @param int $id Material ID
     * @return JsonResponse
     */
    public function markComplete(Request $request, int $id): JsonResponse
    {
        $user = Auth::user();
        $material = CourseMaterial::with('module')->findOrFail($id);
        
        // Validate access
        $kelasId = $material->module->kelas_id;
        $enrollment = Myclass::where('user_id', $user->id)
            ->where('kelas_id', $kelasId)
            ->where('status', 'Aktif')
            ->first();

        if (!$enrollment) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak.',
            ], 403);
        }

        // Check if already completed
        $existing = UserProgress::where('user_id', $user->id)
            ->where('material_id', $id)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => true,
                'message' => 'Materi sudah diselesaikan sebelumnya.',
            ]);
        }

        UserProgress::create([
            'user_id' => $user->id,
            'material_id' => $id,
            'completed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Materi berhasil diselesaikan.',
        ]);
    }
}
