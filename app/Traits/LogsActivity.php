<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            $model->logActivity('created');
        });

        static::updated(function ($model) {
            $model->logActivity('updated');
        });

        static::deleted(function ($model) {
            $model->logActivity('deleted');
        });
    }

    protected function logActivity(string $action)
    {
        $businessId = $this->business_id ?? null;

        ActivityLog::create([
            'user_id'     => Auth::id(),
            'business_id' => $businessId,
            'action'      => $action,
            'model_type'  => static::class,
            'model_id'    => $this->getKey(),
            'old_values'  => $action === 'updated' ? $this->getOriginal() : null,
            'new_values'  => $action !== 'deleted' ? $this->getAttributes() : null,
            'description' => $this->getActivityDescription($action),
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
            'created_at'  => now(),
        ]);
    }

    protected function getActivityDescription(string $action): string
    {
        $modelName = class_basename($this);
        $identifier = $this->name ?? $this->description ?? $this->item ?? $this->id;

        return match($action) {
            'created' => "{$modelName} '{$identifier}' was created.",
            'updated' => "{$modelName} '{$identifier}' was updated.",
            'deleted' => "{$modelName} '{$identifier}' was deleted.",
            default   => "{$modelName} '{$identifier}' was {$action}.",
        };
    }
}
