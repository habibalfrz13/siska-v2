<?php

namespace App\Http\Controllers;

use App\Models\CourseMaterial;
use App\Models\CourseModule;
use App\Models\Kelas;
use App\Models\Myclass;
use App\Models\UserProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CourseController extends Controller
{
    /**
     * Display user's enrolled active classes for learning
     */
    public function index()
    {
        try {
            $userId = Auth::id();
            
            // Get user's active classes with modules
            $myClasses = Myclass::with(['kelas.modules.materials', 'kelas.kategori'])
                ->where('user_id', $userId)
                ->where('status', 'Aktif')
                ->orderBy('created_at', 'desc')
                ->get();

            // Calculate progress for each class
            $classesWithProgress = $myClasses->map(function ($myClass) use ($userId) {
                $kelas = $myClass->kelas;
                $totalMaterials = 0;
                $completedMaterials = 0;

                foreach ($kelas->modules as $module) {
                    foreach ($module->materials as $material) {
                        $totalMaterials++;
                        if ($material->isCompletedByUser($userId)) {
                            $completedMaterials++;
                        }
                    }
                }

                return [
                    'myclass' => $myClass,
                    'kelas' => $kelas,
                    'total_materials' => $totalMaterials,
                    'completed_materials' => $completedMaterials,
                    'progress_percent' => $totalMaterials > 0 
                        ? round(($completedMaterials / $totalMaterials) * 100) 
                        : 0,
                ];
            });

            return view('dashboard.learning.index', compact('classesWithProgress'));
        } catch (\Exception $e) {
            Log::error('Learning index error', ['message' => $e->getMessage()]);
            return redirect()->route('home')
                ->with('error', 'Gagal memuat halaman pembelajaran.');
        }
    }

    /**
     * Display course detail with modules sidebar
     */
    public function course($id)
    {
        try {
            $userId = Auth::id();
            
            // Check if user has access to this class
            $myClass = Myclass::where('user_id', $userId)
                ->where('kelas_id', $id)
                ->where('status', 'Aktif')
                ->firstOrFail();

            $kelas = Kelas::with(['modules.materials', 'kategori', 'vendor'])
                ->findOrFail($id);

            // Get first material as default
            $firstMaterial = null;
            $firstModule = $kelas->publishedModules()->first();
            if ($firstModule) {
                $firstMaterial = $firstModule->publishedMaterials()->first();
            }

            // Calculate progress
            $totalMaterials = 0;
            $completedMaterials = 0;
            $modulesWithProgress = [];

            foreach ($kelas->publishedModules as $module) {
                $moduleCompleted = 0;
                $moduleMaterials = $module->publishedMaterials;
                
                foreach ($moduleMaterials as $material) {
                    $totalMaterials++;
                    if ($material->isCompletedByUser($userId)) {
                        $completedMaterials++;
                        $moduleCompleted++;
                    }
                }

                $modulesWithProgress[] = [
                    'module' => $module,
                    'materials' => $moduleMaterials,
                    'completed' => $moduleCompleted,
                    'total' => $moduleMaterials->count(),
                    'is_complete' => $moduleCompleted === $moduleMaterials->count() && $moduleMaterials->count() > 0,
                ];
            }

            $progressPercent = $totalMaterials > 0 
                ? round(($completedMaterials / $totalMaterials) * 100) 
                : 0;

            return view('dashboard.learning.course', compact(
                'kelas', 
                'myClass',
                'modulesWithProgress', 
                'firstMaterial',
                'progressPercent',
                'completedMaterials',
                'totalMaterials'
            ));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('learn.index')
                ->with('error', 'Kelas tidak ditemukan atau Anda tidak memiliki akses.');
        } catch (\Exception $e) {
            Log::error('Course detail error', ['message' => $e->getMessage(), 'id' => $id]);
            return redirect()->route('learn.index')
                ->with('error', 'Gagal memuat detail kelas.');
        }
    }

    /**
     * Display specific material content
     */
    public function material($id)
    {
        try {
            $userId = Auth::id();
            
            $material = CourseMaterial::with(['module.kelas'])
                ->published()
                ->findOrFail($id);

            $kelas = $material->module->kelas;

            // Check user access
            $hasAccess = Myclass::where('user_id', $userId)
                ->where('kelas_id', $kelas->id)
                ->where('status', 'Aktif')
                ->exists();

            if (!$hasAccess) {
                return redirect()->route('learn.index')
                    ->with('error', 'Anda tidak memiliki akses ke materi ini.');
            }

            // Mark as viewed (create progress record)
            UserProgress::getOrCreate($userId, $material->id);

            // Get all modules with materials for sidebar
            $modulesWithProgress = [];
            foreach ($kelas->publishedModules as $module) {
                $materials = $module->publishedMaterials;
                $modulesWithProgress[] = [
                    'module' => $module,
                    'materials' => $materials->map(function ($mat) use ($userId) {
                        return [
                            'material' => $mat,
                            'is_completed' => $mat->isCompletedByUser($userId),
                            'is_current' => false,
                        ];
                    }),
                ];
            }

            // Get prev/next materials
            $allMaterials = collect();
            foreach ($kelas->publishedModules as $module) {
                $allMaterials = $allMaterials->merge($module->publishedMaterials);
            }
            
            $currentIndex = $allMaterials->search(function ($item) use ($id) {
                return $item->id == $id;
            });

            $prevMaterial = $currentIndex > 0 ? $allMaterials[$currentIndex - 1] : null;
            $nextMaterial = $currentIndex < $allMaterials->count() - 1 ? $allMaterials[$currentIndex + 1] : null;

            // Check if current material is completed
            $isCompleted = $material->isCompletedByUser($userId);

            return view('dashboard.learning.material', compact(
                'material',
                'kelas',
                'modulesWithProgress',
                'prevMaterial',
                'nextMaterial',
                'isCompleted'
            ));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('learn.index')
                ->with('error', 'Materi tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Material view error', ['message' => $e->getMessage(), 'id' => $id]);
            return redirect()->route('learn.index')
                ->with('error', 'Gagal memuat materi.');
        }
    }

    /**
     * Mark material as completed
     */
    public function markComplete(Request $request, $id)
    {
        try {
            $userId = Auth::id();
            
            $material = CourseMaterial::findOrFail($id);
            $kelas = $material->module->kelas;

            // Check access
            $hasAccess = Myclass::where('user_id', $userId)
                ->where('kelas_id', $kelas->id)
                ->where('status', 'Aktif')
                ->exists();

            if (!$hasAccess) {
                return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
            }

            // Mark as completed
            $progress = UserProgress::getOrCreate($userId, $material->id);
            
            if (!$progress->isCompleted()) {
                $progress->markAsCompleted();
            }

            // Calculate new progress
            $totalMaterials = 0;
            $completedMaterials = 0;
            
            foreach ($kelas->publishedModules as $module) {
                foreach ($module->publishedMaterials as $mat) {
                    $totalMaterials++;
                    if ($mat->isCompletedByUser($userId)) {
                        $completedMaterials++;
                    }
                }
            }

            $progressPercent = $totalMaterials > 0 
                ? round(($completedMaterials / $totalMaterials) * 100) 
                : 0;

            return response()->json([
                'success' => true,
                'message' => 'Materi ditandai selesai',
                'progress_percent' => $progressPercent,
                'completed_materials' => $completedMaterials,
                'total_materials' => $totalMaterials,
            ]);
        } catch (\Exception $e) {
            Log::error('Mark complete error', ['message' => $e->getMessage(), 'id' => $id]);
            return response()->json(['success' => false, 'message' => 'Gagal menandai selesai'], 500);
        }
    }
}
