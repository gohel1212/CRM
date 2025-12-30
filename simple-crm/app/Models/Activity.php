<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    protected $fillable = [
        'subject',
        'description',
        'type',
        'status',
        'start_date',
        'end_date',
        'due_date',
        'activityable_type',
        'activityable_id',
        'assigned_to',
        'created_by',
        'is_all_day'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'completed_at' => 'datetime',
        'is_all_day' => 'boolean',
    ];

    // Relationships
    public function activityable()
    {
        return $this->morphTo();
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participants()
    {
        return $this->hasMany(ActivityParticipant::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function deal()
    {
        return $this->belongsTo(Deal::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeOverdue($query)
    {
        return $query->where('end_date', '<', now())
            ->where('status', 'pending');
    }

    public function scopeUpcoming($query, $days = 7)
    {
        return $query->where('end_date', '>=', now())
            ->where('end_date', '<=', now()->addDays($days))
            ->where('status', 'pending');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('assigned_to', $userId)
                ->orWhereHas('participants', function ($q) use ($userId) {
                    $q->where('participant_type', User::class)
                        ->where('participant_id', $userId);
                });
        });
    }

    // Helper Methods
    public function complete($outcome = null)
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'outcome' => $outcome,
        ]);
    }

    public function cancel()
    {
        $this->update([
            'status' => 'cancelled',
            'completed_at' => now(),
        ]);
    }

    public function reschedule($newEndDate)
    {
        $this->update([
            'end_date' => $newEndDate,
        ]);
    }

    public function addParticipant($participant, $status = 'pending')
    {
        return $this->participants()->create([
            'participant_type' => get_class($participant),
            'participant_id' => $participant->id,
            'status' => $status,
        ]);
    }

    public function removeParticipant($participant)
    {
        return $this->participants()
            ->where('participant_type', get_class($participant))
            ->where('participant_id', $participant->id)
            ->delete();
    }

    public function getIsOverdueAttribute()
    {
        return $this->status === 'pending' && $this->end_date < now();
    }

    public function getDurationAttribute()
    {
        if (!$this->completed_at) {
            return null;
        }

        return $this->start_date->diffInMinutes($this->completed_at);
    }
}
