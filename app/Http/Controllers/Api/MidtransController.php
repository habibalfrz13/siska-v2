<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Myclass;
use App\Models\Transaksi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Notification;

/**
 * API Midtrans Controller
 * 
 * Handles Midtrans payment notification webhooks
 */
class MidtransController extends Controller
{
    public function __construct()
    {
        // Configure Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized', true);
        Config::$is3ds = config('midtrans.is_3ds', true);
    }

    /**
     * Handle Midtrans payment notification webhook
     * 
     * This endpoint receives POST requests from Midtrans server
     * when payment status changes (success, pending, failure, etc.)
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function notification(Request $request): JsonResponse
    {
        try {
            // Try to use Midtrans SDK Notification class first
            $orderId = null;
            $transactionStatus = null;
            $paymentType = null;
            $fraudStatus = 'accept';
            
            try {
                $notification = new Notification();
                $orderId = $notification->order_id;
                $transactionStatus = $notification->transaction_status;
                $paymentType = $notification->payment_type ?? 'unknown';
                $fraudStatus = $notification->fraud_status ?? 'accept';
            } catch (\Exception $sdkException) {
                // Fallback: Parse raw JSON from request body
                Log::warning('Midtrans SDK failed, using raw request', [
                    'error' => $sdkException->getMessage()
                ]);
                
                $payload = $request->all();
                $orderId = $payload['order_id'] ?? null;
                $transactionStatus = $payload['transaction_status'] ?? null;
                $paymentType = $payload['payment_type'] ?? 'unknown';
                $fraudStatus = $payload['fraud_status'] ?? 'accept';
            }
            
            if (!$orderId || !$transactionStatus) {
                return response()->json([
                    'success' => false,
                    'message' => 'Missing required fields: order_id or transaction_status',
                ], 400);
            }
            
            Log::info('Midtrans Notification Received', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'payment_type' => $paymentType,
                'fraud_status' => $fraudStatus,
            ]);
            
            // Extract transaksi ID from order_id
            // Supports formats: SISKA-{transaksi_id}-{timestamp} or ORDER-{transaksi_id}-{timestamp}
            $transaksiId = null;
            if (preg_match('/SISKA-(\d+)-/', $orderId, $matches)) {
                $transaksiId = $matches[1];
            } elseif (preg_match('/ORDER-(\d+)-/', $orderId, $matches)) {
                $transaksiId = $matches[1];
            }
            
            if (!$transaksiId) {
                Log::warning('Invalid order_id format', ['order_id' => $orderId]);
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid order_id format',
                ], 400);
            }
            
            $transaksi = Transaksi::find($transaksiId);
            
            if (!$transaksi) {
                Log::warning('Transaction not found', ['transaksi_id' => $transaksiId]);
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction not found',
                ], 404);
            }
            
            // Don't process if already successful
            if ($transaksi->status_pembayaran === 'Berhasil') {
                Log::info('Transaction already successful, skipping', ['transaksi_id' => $transaksiId]);
                return response()->json([
                    'success' => true,
                    'message' => 'Transaction already processed',
                ]);
            }
            
            DB::beginTransaction();
            
            try {
                // Process based on transaction status
                if ($transactionStatus == 'capture') {
                    // For credit card payments
                    if ($fraudStatus == 'accept') {
                        $this->markAsSuccess($transaksi);
                    } elseif ($fraudStatus == 'challenge') {
                        // Payment needs manual review
                        Log::warning('Payment flagged for review', ['transaksi_id' => $transaksiId]);
                    }
                } elseif ($transactionStatus == 'settlement') {
                    // Payment successful (for non-credit card payments)
                    $this->markAsSuccess($transaksi);
                } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                    // Payment failed
                    $this->markAsFailed($transaksi);
                } elseif ($transactionStatus == 'pending') {
                    // Payment pending - already in this state, no action needed
                    Log::info('Payment still pending', ['transaksi_id' => $transaksiId]);
                }
                
                DB::commit();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Notification processed successfully',
                ]);
                
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
            
        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error processing notification',
            ], 500);
        }
    }

    /**
     * Mark transaction as successful and activate class
     */
    private function markAsSuccess(Transaksi $transaksi): void
    {
        // Update transaction status
        $transaksi->update([
            'status_pembayaran' => 'Berhasil',
            'tanggal_pembayaran' => now(),
        ]);
        
        // Activate Myclass enrollment
        if ($transaksi->myclass_id) {
            Myclass::where('id', $transaksi->myclass_id)
                ->update(['status' => 'Aktif']);
                
            Log::info('Myclass activated', [
                'myclass_id' => $transaksi->myclass_id,
                'transaksi_id' => $transaksi->id,
            ]);
        } else {
            // Fallback: find myclass by user_id and kelas_id
            Myclass::where('user_id', $transaksi->user_id)
                ->where('kelas_id', $transaksi->kelas_id)
                ->update(['status' => 'Aktif']);
                
            Log::info('Myclass activated via fallback', [
                'user_id' => $transaksi->user_id,
                'kelas_id' => $transaksi->kelas_id,
            ]);
        }
        
        Log::info('Transaction marked as successful', [
            'transaksi_id' => $transaksi->id,
        ]);
    }

    /**
     * Mark transaction as failed
     */
    private function markAsFailed(Transaksi $transaksi): void
    {
        $transaksi->update([
            'status_pembayaran' => 'Gagal',
        ]);
        
        Log::info('Transaction marked as failed', [
            'transaksi_id' => $transaksi->id,
        ]);
    }

    /**
     * Manually check and sync transaction status with Midtrans
     * Useful for debugging or recovering missed webhooks
     * 
     * @param int $transaksiId
     * @return JsonResponse
     */
    public function checkStatus(int $transaksiId): JsonResponse
    {
        try {
            $transaksi = Transaksi::find($transaksiId);
            
            if (!$transaksi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction not found',
                ], 404);
            }
            
            if (!$transaksi->snap_token) {
                return response()->json([
                    'success' => false,
                    'message' => 'No snap token found for this transaction',
                ], 400);
            }
            
            // Query status from Midtrans
            // Format order_id: SISKA-{id}-{timestamp from snap_token or created_at}
            $orderId = 'SISKA-' . $transaksi->id . '-' . strtotime($transaksi->created_at);
            
            $status = \Midtrans\Transaction::status($orderId);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'local_status' => $transaksi->status_pembayaran,
                    'midtrans_status' => $status,
                ],
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
