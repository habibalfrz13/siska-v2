<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * API Vendor Controller
 */
class VendorController extends Controller
{
    /**
     * Get all vendors
     * 
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $vendors = Vendor::all();

        return response()->json([
            'success' => true,
            'data' => $vendors
        ]);
    }
}
