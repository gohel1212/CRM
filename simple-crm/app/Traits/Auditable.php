<?php

namespace App\Traits;

use App\Models\AuditLog;
use App\Services\AuditLogger;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    /**
     * Boot the auditable trait
     */
    protected static function bootAuditable()
    {
        // Log when a model is created
        static::created(function ($model) {
            $model->auditCreated();
        });

        // Log when a model is updated
        static::updated(function ($model) {
            $model->auditUpdated();
        });

        // Log when a model is deleted
        static::deleted(function ($model) {
            $model->auditDeleted();
        });
    }

    /**
     * Log model creation
     */
    protected function auditCreated()
    {
        $this->createAuditLog('created', [
            'description' => $this->getAuditDescription('created'),
            'new_values' => $this->getAuditableAttributes(),
        ]);
    }

    /**
     * Log model update
     */
    protected function auditUpdated()
    {
        $changes = $this->getChanges();

        if (empty($changes)) {
            return;
        }

        // Remove timestamps from changes if not important
        unset($changes['updated_at']);

        if (empty($changes)) {
            return;
        }

        $this->createAuditLog('updated', [
            'description' => $this->getAuditDescription('updated'),
            'old_values' => $this->getOriginal(),
            'new_values' => $this->getAttributes(),
        ]);
    }

    /**
     * Log model deletion
     */
    protected function auditDeleted()
    {
        $this->createAuditLog('deleted', [
            'description' => $this->getAuditDescription('deleted'),
            'old_values' => $this->getOriginal(),
        ]);
    }

    /**
     * Create an audit log entry
     */
    protected function createAuditLog($event, $data = [])
    {
        $description = $data['description'] ?? $this->getAuditDescription($event);

        // Use the AuditLogger service which includes a safe fallback when the
        // audit_logs table is not available. This avoids throwing DB exceptions
        // from model event listeners (which would break create/update flows).
        try {
            AuditLogger::log($event, $description, array_merge($data, [
                'model_type' => get_class($this),
                'model_id' => $this->id,
            ]));
        } catch (\Exception $e) {
            // Swallow any exceptions to avoid breaking application flows.
        }
    }

    /**
     * Get auditable attributes (exclude sensitive data)
     */
    protected function getAuditableAttributes()
    {
        $attributes = $this->getAttributes();

        // Remove sensitive fields
        $excludedFields = $this->auditExclude ?? ['password', 'remember_token'];

        foreach ($excludedFields as $field) {
            unset($attributes[$field]);
        }

        return $attributes;
    }

    /**
     * Get audit description
     */
    protected function getAuditDescription($event)
    {
        $modelName = class_basename($this);
        $identifier = $this->getAuditIdentifier();

        return match ($event) {
            'created' => "{$modelName} '{$identifier}' was created",
            'updated' => "{$modelName} '{$identifier}' was updated",
            'deleted' => "{$modelName} '{$identifier}' was deleted",
            default => "{$modelName} '{$identifier}' was {$event}",
        };
    }

    /**
     * Get identifier for audit log
     */
    protected function getAuditIdentifier()
    {
        // Try common identifier fields
        if (isset($this->name)) {
            return $this->name;
        }

        if (isset($this->title)) {
            return $this->title;
        }

        if (isset($this->subject)) {
            return $this->subject;
        }

        if (isset($this->email)) {
            return $this->email;
        }

        return "#{$this->id}";
    }

    /**
     * Get audit logs for this model
     */
    public function auditLogs()
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }
}