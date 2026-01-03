<?php

namespace App\Http\Controllers;

use App\Models\CourseMaterial;
use App\Models\CourseModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    /**
     * Display materials for a module
     */
    public function index($moduleId)
    {
        try {
            $module = CourseModule::with(['kelas', 'materials'])->findOrFail($moduleId);
            $materials = $module->materials()->orderBy('order')->get();
            $kelas = $module->kelas;
            
            return view('dashboard.admin.materials.index', compact('module', 'materials', 'kelas'));
        } catch (\Exception $e) {
            Log::error('Material index error', ['message' => $e->getMessage()]);
            return redirect()->route('kelas.index')
                ->with('error', 'Gagal memuat materi.');
        }
    }

    /**
     * Show create form
     */
    public function create($moduleId)
    {
        try {
            $module = CourseModule::with('kelas')->findOrFail($moduleId);
            $kelas = $module->kelas;
            $nextOrder = $module->materials()->max('order') + 1;
            
            return view('dashboard.admin.materials.create', compact('module', 'kelas', 'nextOrder'));
        } catch (\Exception $e) {
            Log::error('Material create form error', ['message' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Gagal memuat form.');
        }
    }

    /**
     * Store new material
     */
    public function store(Request $request, $moduleId)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:video,text,file,link',
            'content' => 'nullable|string',
            'video_url' => 'nullable|url',
            'file' => 'nullable|file|max:51200', // 50MB max
            'duration' => 'nullable|integer|min:0',
            'order' => 'nullable|integer|min:0',
            'is_published' => 'nullable|boolean',
        ], [
            'title.required' => 'Judul materi wajib diisi.',
            'type.required' => 'Tipe materi wajib dipilih.',
            'file.max' => 'Ukuran file maksimal 50MB.',
        ]);

        try {
            $module = CourseModule::findOrFail($moduleId);
            
            $filePath = null;
            if ($request->hasFile('file')) {
                $filePath = $request->file('file')->store('materials', 'public');
            }

            $material = CourseMaterial::create([
                'module_id' => $module->id,
                'title' => $validated['title'],
                'type' => $validated['type'],
                'content' => $validated['content'] ?? null,
                'video_url' => $validated['video_url'] ?? null,
                'file_path' => $filePath,
                'duration' => $validated['duration'] ?? null,
                'order' => $validated['order'] ?? ($module->materials()->max('order') + 1),
                'is_published' => $request->has('is_published'),
            ]);

            Log::info('Material created', ['id' => $material->id, 'module_id' => $moduleId]);

            return redirect()->route('admin.materials.index', $moduleId)
                ->with('success', 'Materi berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Material store error', ['message' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Gagal menambahkan materi.')
                ->withInput();
        }
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        try {
            $material = CourseMaterial::with('module.kelas')->findOrFail($id);
            $module = $material->module;
            $kelas = $module->kelas;
            
            return view('dashboard.admin.materials.edit', compact('material', 'module', 'kelas'));
        } catch (\Exception $e) {
            Log::error('Material edit form error', ['message' => $e->getMessage()]);
            return redirect()->route('kelas.index')
                ->with('error', 'Materi tidak ditemukan.');
        }
    }

    /**
     * Update material
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:video,text,file,link',
            'content' => 'nullable|string',
            'video_url' => 'nullable|url',
            'file' => 'nullable|file|max:51200',
            'duration' => 'nullable|integer|min:0',
            'order' => 'nullable|integer|min:0',
            'is_published' => 'nullable|boolean',
        ]);

        try {
            $material = CourseMaterial::findOrFail($id);
            
            $filePath = $material->file_path;
            if ($request->hasFile('file')) {
                // Delete old file
                if ($material->file_path) {
                    Storage::disk('public')->delete($material->file_path);
                }
                $filePath = $request->file('file')->store('materials', 'public');
            }

            $material->update([
                'title' => $validated['title'],
                'type' => $validated['type'],
                'content' => $validated['content'] ?? null,
                'video_url' => $validated['video_url'] ?? null,
                'file_path' => $filePath,
                'duration' => $validated['duration'] ?? null,
                'order' => $validated['order'] ?? $material->order,
                'is_published' => $request->has('is_published'),
            ]);

            Log::info('Material updated', ['id' => $id]);

            return redirect()->route('admin.materials.index', $material->module_id)
                ->with('success', 'Materi berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Material update error', ['message' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Gagal memperbarui materi.')
                ->withInput();
        }
    }

    /**
     * Delete material
     */
    public function destroy($id)
    {
        try {
            $material = CourseMaterial::findOrFail($id);
            $moduleId = $material->module_id;
            
            // Delete file if exists
            if ($material->file_path) {
                Storage::disk('public')->delete($material->file_path);
            }
            
            $material->delete();

            Log::info('Material deleted', ['id' => $id]);

            return redirect()->route('admin.materials.index', $moduleId)
                ->with('success', 'Materi berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Material delete error', ['message' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Gagal menghapus materi.');
        }
    }
}
