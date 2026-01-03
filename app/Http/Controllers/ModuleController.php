<?php

namespace App\Http\Controllers;

use App\Models\CourseModule;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ModuleController extends Controller
{
    /**
     * Display modules for a class
     */
    public function index($kelasId)
    {
        try {
            $kelas = Kelas::with('modules.materials')->findOrFail($kelasId);
            $modules = $kelas->modules()->orderBy('order')->get();
            
            return view('dashboard.admin.modules.index', compact('kelas', 'modules'));
        } catch (\Exception $e) {
            Log::error('Module index error', ['message' => $e->getMessage()]);
            return redirect()->route('kelas.index')
                ->with('error', 'Gagal memuat modul.');
        }
    }

    /**
     * Show create form
     */
    public function create($kelasId)
    {
        try {
            $kelas = Kelas::findOrFail($kelasId);
            $nextOrder = $kelas->modules()->max('order') + 1;
            
            return view('dashboard.admin.modules.create', compact('kelas', 'nextOrder'));
        } catch (\Exception $e) {
            Log::error('Module create form error', ['message' => $e->getMessage()]);
            return redirect()->route('admin.modules.index', $kelasId)
                ->with('error', 'Gagal memuat form.');
        }
    }

    /**
     * Store new module
     */
    public function store(Request $request, $kelasId)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'is_published' => 'nullable|boolean',
        ], [
            'title.required' => 'Judul modul wajib diisi.',
        ]);

        try {
            $kelas = Kelas::findOrFail($kelasId);
            
            $module = CourseModule::create([
                'kelas_id' => $kelas->id,
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'order' => $validated['order'] ?? ($kelas->modules()->max('order') + 1),
                'is_published' => $request->has('is_published'),
            ]);

            Log::info('Module created', ['id' => $module->id, 'kelas_id' => $kelasId]);

            return redirect()->route('admin.modules.index', $kelasId)
                ->with('success', 'Modul berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Module store error', ['message' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Gagal menambahkan modul.')
                ->withInput();
        }
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        try {
            $module = CourseModule::with('kelas')->findOrFail($id);
            $kelas = $module->kelas;
            
            return view('dashboard.admin.modules.edit', compact('module', 'kelas'));
        } catch (\Exception $e) {
            Log::error('Module edit form error', ['message' => $e->getMessage()]);
            return redirect()->route('kelas.index')
                ->with('error', 'Modul tidak ditemukan.');
        }
    }

    /**
     * Update module
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'is_published' => 'nullable|boolean',
        ]);

        try {
            $module = CourseModule::findOrFail($id);
            
            $module->update([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'order' => $validated['order'] ?? $module->order,
                'is_published' => $request->has('is_published'),
            ]);

            Log::info('Module updated', ['id' => $id]);

            return redirect()->route('admin.modules.index', $module->kelas_id)
                ->with('success', 'Modul berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Module update error', ['message' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Gagal memperbarui modul.')
                ->withInput();
        }
    }

    /**
     * Delete module
     */
    public function destroy($id)
    {
        try {
            $module = CourseModule::findOrFail($id);
            $kelasId = $module->kelas_id;
            
            $module->delete();

            Log::info('Module deleted', ['id' => $id]);

            return redirect()->route('admin.modules.index', $kelasId)
                ->with('success', 'Modul berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Module delete error', ['message' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Gagal menghapus modul.');
        }
    }
}
