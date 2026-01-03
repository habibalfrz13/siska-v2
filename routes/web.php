<?php

use App\Http\Controllers\BiodataController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MyclassController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use App\Models\Myclass;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [App\Http\Controllers\frontController::class, 'index']);

// Secure image serving with path traversal protection
Route::get('images/{folder}/{filename}', function ($folder, $filename) {
    // Validate folder and filename to prevent path traversal
    if (!preg_match('/^[a-zA-Z0-9_-]+$/', $folder) || 
        !preg_match('/^[a-zA-Z0-9_.\-]+$/', $filename)) {
        abort(400, 'Invalid request');
    }
    
    $basePath = storage_path('app/images');
    $requestedPath = realpath($basePath . '/' . $folder . '/' . $filename);
    
    // Ensure the resolved path is within the allowed directory
    if ($requestedPath === false || !str_starts_with($requestedPath, realpath($basePath))) {
        abort(404);
    }

    if (!file_exists($requestedPath)) {
        abort(404);
    }

    return response()->file($requestedPath);
})->name('show-image');

// Authentication routes
Auth::routes();

// Public certificate verification (no auth required)
Route::get('/verify/{certificateNumber}', [App\Http\Controllers\CertificateController::class, 'verify'])->name('certificates.verify');

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [HomeController::class, 'index'])->name('home');
    
    // User profile
    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
    
    // User class browsing and enrollment
    Route::get('/userkelas', [KelasController::class, 'userIndex'])->name('kelas.userIndex');
    
    // User's enrolled classes
    Route::get('/usermyclass', [MyclassController::class, 'userIndex'])->name('myclass.userIndex');
    Route::get('/usermyclassdetail', [MyclassController::class, 'userIndexDetail'])->name('myclass.userIndexDetail');
    Route::post('/myclass/store', [MyclassController::class, 'store'])->name('myclass.store'); // Allow users to enroll
    
    // User transactions
    Route::get('/transaksiuser/{id}', [TransaksiController::class, 'userIndex'])->name('transaksi.userIndex');
    Route::get('/pembayaran/batalkan/{id}', [TransaksiController::class, 'batalkan'])->name('transaksi.batalkan');
    Route::get('/transaksi/sukses/{id}', [TransaksiController::class, 'sukses'])->name('transaksi.sukses');
    
    // Biodata management (user's own data)
    Route::get('/biodata', [BiodataController::class, 'index'])->name('biodata.index');
    Route::get('/biodata/create', [BiodataController::class, 'create'])->name('biodata.create');
    Route::post('/biodata/store', [BiodataController::class, 'store'])->name('biodata.store');
    Route::patch('/biodata/update/{id}', [BiodataController::class, 'update'])->name('biodata.update');
    Route::get('/biodata/show/{id}', [BiodataController::class, 'show'])->name('biodata.show');
    Route::get('/biodata/edit/{id}', [BiodataController::class, 'edit'])->name('biodata.edit');
});

/*
|--------------------------------------------------------------------------
| Learning Routes (Authenticated Users)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('learn')->name('learn.')->group(function () {
    Route::get('/', [App\Http\Controllers\CourseController::class, 'index'])->name('index');
    Route::get('/course/{id}', [App\Http\Controllers\CourseController::class, 'course'])->name('course');
    Route::get('/material/{id}', [App\Http\Controllers\CourseController::class, 'material'])->name('material');
    Route::post('/material/{id}/complete', [App\Http\Controllers\CourseController::class, 'markComplete'])->name('complete');
});

/*
|--------------------------------------------------------------------------
| Certificate Routes (Authenticated Users)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('certificates')->name('certificates.')->group(function () {
    Route::get('/', [App\Http\Controllers\CertificateController::class, 'index'])->name('index');
    Route::post('/generate/{kelasId}', [App\Http\Controllers\CertificateController::class, 'generate'])->name('generate');
    Route::get('/{id}', [App\Http\Controllers\CertificateController::class, 'show'])->name('show');
    Route::get('/{id}/download', [App\Http\Controllers\CertificateController::class, 'download'])->name('download');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Role-protected)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {
    
    // Kelas Management
    Route::get('/kelas', [KelasController::class, 'index'])->name('kelas.index');
    Route::get('/kelas/create', [KelasController::class, 'create'])->name('kelas.create');
    Route::post('/kelas/store', [KelasController::class, 'store'])->name('kelas.store');
    Route::patch('/kelas/update/{id}', [KelasController::class, 'update'])->name('kelas.update');
    Route::delete('/kelas/destroy/{id}', [KelasController::class, 'destroy'])->name('kelas.destroy');
    Route::get('/kelas/show/{id}', [KelasController::class, 'show'])->name('kelas.show');
    Route::get('/kelas/edit/{id}', [KelasController::class, 'edit'])->name('kelas.edit');
    
    // User Management
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
    Route::patch('/user/update/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/user/destroy/{id}', [UserController::class, 'destroy'])->name('user.destroy');
    Route::get('/user/show/{id}', [UserController::class, 'show'])->name('user.show');
    Route::get('/user/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
    
    // Transaksi Management
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::get('/transaksi/create', [TransaksiController::class, 'create'])->name('transaksi.create');
    Route::post('/transaksi/store', [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::patch('/transaksi/update/{id}', [TransaksiController::class, 'update'])->name('transaksi.update');
    Route::delete('/transaksi/destroy/{id}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');
    Route::get('/transaksi/show/{id}', [TransaksiController::class, 'show'])->name('transaksi.show');
    Route::get('/transaksi/edit/{id}', [TransaksiController::class, 'edit'])->name('transaksi.edit');
    
    // Myclass Management (Admin view)
    Route::get('/myclass', [MyclassController::class, 'index'])->name('myclass.index');
    Route::get('/myclass/create', [MyclassController::class, 'create'])->name('myclass.create');
    // Route::post('/myclass/store', [MyclassController::class, 'store'])->name('myclass.store'); // Moved to auth group
    Route::patch('/myclass/update/{id}', [MyclassController::class, 'update'])->name('myclass.update');
    Route::delete('/myclass/destroy/{id}', [MyclassController::class, 'destroy'])->name('myclass.destroy');
    Route::get('/myclass/show/{id}', [MyclassController::class, 'show'])->name('myclass.show');
    Route::get('/myclass/edit/{id}', [MyclassController::class, 'edit'])->name('myclass.edit');
    
    // Kategori Management
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::get('/kategori/create', [KategoriController::class, 'create'])->name('kategori.create');
    Route::post('/kategori/store', [KategoriController::class, 'store'])->name('kategori.store');
    Route::patch('/kategori/update/{id}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/destroy/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');
    Route::get('/kategori/show/{id}', [KategoriController::class, 'show'])->name('kategori.show');
    Route::get('/kategori/edit/{id}', [KategoriController::class, 'edit'])->name('kategori.edit');
    
    // Vendor Management
    Route::get('/vendor', [VendorController::class, 'index'])->name('vendor.index');
    Route::get('/vendor/create', [VendorController::class, 'create'])->name('vendor.create');
    Route::post('/vendor/store', [VendorController::class, 'store'])->name('vendor.store');
    Route::patch('/vendor/update/{id}', [VendorController::class, 'update'])->name('vendor.update');
    Route::delete('/vendor/destroy/{id}', [VendorController::class, 'destroy'])->name('vendor.destroy');
    Route::get('/vendor/show/{id}', [VendorController::class, 'show'])->name('vendor.show');
    Route::get('/vendor/edit/{id}', [VendorController::class, 'edit'])->name('vendor.edit');
    
    // Peserta Management
    Route::get('/peserta', [PesertaController::class, 'index'])->name('peserta.index');
    Route::get('/peserta/create', [PesertaController::class, 'create'])->name('peserta.create');
    Route::post('/peserta/store', [PesertaController::class, 'store'])->name('peserta.store');
    Route::patch('/peserta/update/{id}', [PesertaController::class, 'update'])->name('peserta.update');
    Route::delete('/peserta/destroy/{id}', [PesertaController::class, 'destroy'])->name('peserta.destroy');
    Route::get('/peserta/show/{id}', [PesertaController::class, 'show'])->name('peserta.show');
    Route::get('/peserta/edit/{id}', [PesertaController::class, 'edit'])->name('peserta.edit');
    
    // Biodata Management (Admin - delete only)
    Route::delete('/biodata/destroy/{id}', [BiodataController::class, 'destroy'])->name('biodata.destroy');
    
    // Print Reports
    Route::get('/cetakPeserta', [PesertaController::class, 'cetak'])->name('peserta.cetak');
    Route::get('/cetakTransaksi', [TransaksiController::class, 'cetak'])->name('transaksi.cetak');
    Route::get('/cetakTransaksiSuccess', [TransaksiController::class, 'cetaksuccess'])->name('transaksi.cetaksuccess');
});

/*
|--------------------------------------------------------------------------
| Course Module & Material Management Routes (Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Modules
    Route::get('/kelas/{kelasId}/modules', [App\Http\Controllers\ModuleController::class, 'index'])->name('modules.index');
    Route::get('/kelas/{kelasId}/modules/create', [App\Http\Controllers\ModuleController::class, 'create'])->name('modules.create');
    Route::post('/kelas/{kelasId}/modules', [App\Http\Controllers\ModuleController::class, 'store'])->name('modules.store');
    Route::get('/modules/{id}/edit', [App\Http\Controllers\ModuleController::class, 'edit'])->name('modules.edit');
    Route::patch('/modules/{id}', [App\Http\Controllers\ModuleController::class, 'update'])->name('modules.update');
    Route::delete('/modules/{id}', [App\Http\Controllers\ModuleController::class, 'destroy'])->name('modules.destroy');
    
    // Materials
    Route::get('/modules/{moduleId}/materials', [App\Http\Controllers\MaterialController::class, 'index'])->name('materials.index');
    Route::get('/modules/{moduleId}/materials/create', [App\Http\Controllers\MaterialController::class, 'create'])->name('materials.create');
    Route::post('/modules/{moduleId}/materials', [App\Http\Controllers\MaterialController::class, 'store'])->name('materials.store');
    Route::get('/materials/{id}/edit', [App\Http\Controllers\MaterialController::class, 'edit'])->name('materials.edit');
    Route::patch('/materials/{id}', [App\Http\Controllers\MaterialController::class, 'update'])->name('materials.update');
    Route::delete('/materials/{id}', [App\Http\Controllers\MaterialController::class, 'destroy'])->name('materials.destroy');
});