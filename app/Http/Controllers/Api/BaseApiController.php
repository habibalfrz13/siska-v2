<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponse;

/**
 * Base API Controller
 * 
 * All API controllers should extend this class
 * Provides consistent response methods
 */
abstract class BaseApiController extends Controller
{
    use ApiResponse;
}
