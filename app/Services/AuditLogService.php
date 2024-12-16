<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AuditLogService
{
    public function log($action, $description, $model = null)
    {
        try {
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'description' => $description,
                'model_type' => $model ? get_class($model) : null,
                'model_id' => $model ? $model->id : null,
                'ip_address' => request()->ip()
            ]);
        } catch (Exception $e) {
            // Fallback logging
            Log::warning('Audit log failed: ' . $e->getMessage());
        }
    }

    public static function createAuditLog($action, $description)
    {
        return (new self())->log($action, $description);
    }
}