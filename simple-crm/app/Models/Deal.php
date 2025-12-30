<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Deal extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    protected $fillable = [
        'name',
        'description',
        'amount',
        'currency',
        'status',
        'expected_close_date',
        'pipeline_id',
        'pipeline_stage_id',
        'position',
        'customer_id',
        'contact_id',
        'owner_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expected_close_date' => 'date',
    ];

    // Relationships
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function pipeline(): BelongsTo
    {
        return $this->belongsTo(Pipeline::class);
    }

    public function stage(): BelongsTo
    {
        return $this->belongsTo(PipelineStage::class, 'pipeline_stage_id');
    }

    public function products()
    {
        return $this->hasMany(DealProduct::class);
    }

    public function activities()
    {
        return $this->morphMany(Activity::class, 'activityable');
    }

    public function history()
    {
        return $this->hasMany(DealHistory::class);
    }


    // Scopes
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeWon($query)
    {
        return $query->where('status', 'won');
    }

    public function scopeLost($query)
    {
        return $query->where('status', 'lost');
    }

    public function scopeOverdue($query)
    {
        return $query->where('expected_close_date', '<', now())
            ->where('status', 'open');
    }

    // Helper Methods
    public function markAsWon()
    {
        $this->update([
            'status' => 'won',
            'completed_at' => now(),
        ]);

        // Record history
        $this->recordHistory('status', 'open', 'won');
    }

    public function markAsLost($reason = null)
    {
        $this->update([
            'status' => 'lost',
            'lost_reason' => $reason,
            'completed_at' => now(),
        ]);

        // Record history
        $this->recordHistory('status', 'open', 'lost');
    }

    public function moveToStage(PipelineStage $stage)
    {
        $oldStage = $this->stage;
        $this->update(['pipeline_stage_id' => $stage->id]);

        // Record history
        $this->recordHistory(
            'pipeline_stage_id',
            $oldStage ? $oldStage->name : null,
            $stage->name
        );
    }

    public function recordHistory($field, $oldValue, $newValue)
    {
        return $this->history()->create([
            'user_id' => auth()->id(),
            'field_name' => $field,
            'old_value' => $oldValue,
            'new_value' => $newValue,
        ]);
    }

    public function getTotalProductsValueAttribute()
    {
        return $this->products->sum(function ($product) {
            return ($product->price * $product->quantity) - $product->discount;
        });
    }

    public function getWeightedValueAttribute()
    {
        return $this->amount * ($this->stage->probability / 100);
    }

    public function getDaysInPipelineAttribute()
    {
        return $this->created_at->diffInDays(
            $this->completed_at ?? now()
        );
    }

    public function getNextActivityAttribute()
    {
        return $this->activities()
            ->where('status', 'pending')
            ->orderBy('due_date')
            ->first();
    }
}