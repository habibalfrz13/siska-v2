<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Log;

/**
 * Error Handling Trait
 * 
 * Provides consistent error handling for web controllers
 */
trait HandlesErrors
{
    /**
     * Execute action with error handling
     *
     * @param callable $callback
     * @param string $successRoute
     * @param string $successMessage
     * @param string $errorMessage
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function executeWithErrorHandling(
        callable $callback,
        string $successRoute,
        string $successMessage = 'Operasi berhasil.',
        ?string $errorMessage = null
    ) {
        try {
            $result = $callback();
            
            return redirect()
                ->route($successRoute)
                ->with('success', $successMessage);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('Model not found', [
                'model' => $e->getModel(),
                'ids' => $e->getIds(),
            ]);
            
            return redirect()
                ->back()
                ->with('error', 'Data tidak ditemukan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Operation failed', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            
            return redirect()
                ->back()
                ->with('error', $errorMessage ?? 'Terjadi kesalahan. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Execute action with transaction
     *
     * @param callable $callback
     * @param string $successRoute
     * @param string $successMessage
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function executeInTransaction(
        callable $callback,
        string $successRoute,
        string $successMessage = 'Operasi berhasil.'
    ) {
        try {
            \DB::beginTransaction();
            
            $result = $callback();
            
            \DB::commit();
            
            return redirect()
                ->route($successRoute)
                ->with('success', $successMessage);
        } catch (\Exception $e) {
            \DB::rollBack();
            
            Log::error('Transaction failed', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan. Data tidak tersimpan.')
                ->withInput();
        }
    }

    /**
     * Log error with context
     *
     * @param \Exception $e
     * @param string $action
     * @param array $context
     * @return void
     */
    protected function logError(\Exception $e, string $action, array $context = []): void
    {
        Log::error("Error during {$action}", array_merge([
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'user_id' => auth()->id(),
        ], $context));
    }
}
