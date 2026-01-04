<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * API Kategori Controller
 */
class KategoriController extends Controller
{
    /**
     * Get all categories
     * 
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $kategori = Kategori::all();

        return response()->json([
            'success' => true,
            'data' => $kategori
        ]);
    }
}
