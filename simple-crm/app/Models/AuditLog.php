<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_name',
        'event',
        'auditable_type',
        'auditable_id',
        'description',
        'old_values',
        'new_values',
        'properties',
        'ip_address',
        'user_agent',
        'url',
        'method',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'properties' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that performed the action
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the auditable model
     */
    public function auditable()
    {
        return $this->morphTo();
    }

    /**
     * Get a human-readable event name
     */
    public function getEventNameAttribute()
    {
        return match ($this->event) {
            'created' => 'Created',
            'updated' => 'Updated',
            'deleted' => 'Deleted',
            'login' => 'Logged In',
            'logout' => 'Logged Out',
            'login_failed' => 'Failed Login',
            'viewed' => 'Viewed',
            'exported' => 'Exported',
            'imported' => 'Imported',
            'restored' => 'Restored',
            default => ucfirst($this->event),
        };
    }

    /**
     * Get the model name
     */
    public function getModelNameAttribute()
    {
        if (!$this->auditable_type) {
            return null;
        }

        $parts = explode('\\', $this->auditable_type);
        return end($parts);
    }

    /**
     * Scope to filter by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to filter by event
     */
    public function scopeByEvent($query, $event)
    {
        return $query->where('event', $event);
    }

    /**
     * Scope to filter by model
     */
    public function scopeByModel($query, $modelType)
    {
        return $query->where('auditable_type', $modelType);
    }

    /**
     * Scope to filter by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Get changes summary
     */
    public function getChangesSummary()
    {
        if (!$this->old_values || !$this->new_values) {
            return null;
        }

        $changes = [];
        foreach ($this->new_values as $key => $newValue) {
            $oldValue = $this->old_values[$key] ?? null;
            if ($oldValue != $newValue) {
                $changes[$key] = [
                    'old' => $oldValue,
                    'new' => $newValue,
                ];
            }
        }

        return $changes;
    }
}