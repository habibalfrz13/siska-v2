<?php

namespace App\Http\Controllers;

use App\Http\Traits\HandlesErrors;
use App\Models\Biodata;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    use HandlesErrors;

    /**
     * Display a listing of users
     */
    public function index()
    {
        try {
            $data = User::with('biodata')
                ->where('id_role', 2)
                ->orderBy('id', 'DESC')
                ->get();

            return view('dashboard.user.index', [
                'data' => $data,
                'title' => 'User',
            ]);
        } catch (\Exception $e) {
            $this->logError($e, 'fetching users');
            return redirect()->route('home')
                ->with('error', 'Gagal memuat data pengguna.');
        }
    }

    /**
     * Display current user profile
     */
    public function profile()
    {
        try {
            $user = Auth::user();
            $biodata = Biodata::where('user_id', $user->id)->first();

            return view('dashboard.user.profile', compact('user', 'biodata'));
        } catch (\Exception $e) {
            $this->logError($e, 'loading profile');
            return redirect()->route('home')
                ->with('error', 'Gagal memuat profil.');
        }
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        try {
            $roles = Role::all();

            return view('dashboard.user.create', [
                'title' => 'Create User',
                'roles' => $roles,
            ]);
        } catch (\Exception $e) {
            $this->logError($e, 'loading create form');
            return redirect()->route('user.index')
                ->with('error', 'Gagal memuat form.');
        }
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:8|same:confirm-password',
            'id_role' => 'nullable|exists:roles,id',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.same' => 'Konfirmasi password tidak cocok.',
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);
            
            // Set role explicitly (not mass assignable for security)
            $user->id_role = $validated['id_role'] ?? 2;
            $user->save();

            DB::commit();

            Log::info('User created', [
                'user_id' => $user->id,
                'created_by' => Auth::id(),
            ]);

            return redirect()->route('user.index')
                ->with('success', 'Pengguna berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError($e, 'creating user', ['email' => $request->email]);
            return redirect()->back()
                ->with('error', 'Gagal membuat pengguna.')
                ->withInput($request->except('password', 'confirm-password'));
        }
    }

    /**
     * Display the specified user
     */
    public function show($id)
    {
        try {
            $user = User::with('biodata')->findOrFail($id);

            return view('dashboard.user.show', [
                'user' => $user,
                'title' => 'Detail',
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('user.index')
                ->with('error', 'Pengguna tidak ditemukan.');
        } catch (\Exception $e) {
            $this->logError($e, 'showing user', ['id' => $id]);
            return redirect()->route('user.index')
                ->with('error', 'Gagal memuat detail pengguna.');
        }
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit($id)
    {
        try {
            $user = User::with('biodata')->findOrFail($id);
            $roles = Role::all();

            return view('dashboard.user.edit', [
                'user' => $user,
                'roles' => $roles,
                'title' => 'Edit User',
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('user.index')
                ->with('error', 'Pengguna tidak ditemukan.');
        } catch (\Exception $e) {
            $this->logError($e, 'loading edit form', ['id' => $id]);
            return redirect()->route('user.index')
                ->with('error', 'Gagal memuat form edit.');
        }
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($id)],
            'password' => 'nullable|min:8|same:confirm_password',
            'username' => ['nullable', Rule::unique('biodatas', 'username')->ignore($id, 'user_id')],
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah digunakan.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.same' => 'Konfirmasi password tidak cocok.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);
            
            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ];

            // Handle password update
            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

            // Handle profile photo
            if ($request->hasFile('foto')) {
                $image = $request->file('foto');
                $imageName = $image->hashName();
                $image->storeAs('user/', $imageName, 'public');

                // Update or create biodata
                $biodata = Biodata::firstOrNew(['user_id' => $id]);
                
                // Delete old photo
                if ($biodata->foto) {
                    Storage::disk('public')->delete('user/' . $biodata->foto);
                }
                
                $biodata->foto = $imageName;
                $biodata->username = $validated['username'] ?? $biodata->username;
                $biodata->save();
            }

            DB::commit();

            Log::info('User updated', [
                'user_id' => $id,
                'updated_by' => Auth::id(),
            ]);

            return redirect()->route('user.index')
                ->with('success', 'Pengguna berhasil diperbarui.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('user.index')
                ->with('error', 'Pengguna tidak ditemukan.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError($e, 'updating user', ['id' => $id]);
            return redirect()->back()
                ->with('error', 'Gagal memperbarui pengguna.')
                ->withInput($request->except('password', 'confirm_password'));
        }
    }

    /**
     * Remove the specified user
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);

            // Don't allow deleting self
            if ($user->id === Auth::id()) {
                return redirect()->route('user.index')
                    ->with('error', 'Anda tidak dapat menghapus akun sendiri.');
            }

            // Don't allow deleting admin
            if ($user->id_role === 1) {
                return redirect()->route('user.index')
                    ->with('error', 'Tidak dapat menghapus akun administrator.');
            }

            // Delete biodata and photo
            $biodata = Biodata::where('user_id', $id)->first();
            if ($biodata) {
                if ($biodata->foto) {
                    Storage::disk('public')->delete('user/' . $biodata->foto);
                }
                $biodata->delete();
            }

            $user->delete();

            DB::commit();

            Log::info('User deleted', [
                'user_id' => $id,
                'deleted_by' => Auth::id(),
            ]);

            return redirect()->route('user.index')
                ->with('success', 'Pengguna berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('user.index')
                ->with('error', 'Pengguna tidak ditemukan.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError($e, 'deleting user', ['id' => $id]);
            return redirect()->route('user.index')
                ->with('error', 'Gagal menghapus pengguna.');
        }
    }
}
