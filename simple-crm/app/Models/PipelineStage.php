<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PipelineStage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'pipeline_id',
        'name',
        'description',
        'probability',
        'order',
        'color',
        'is_active',
    ];

    protected $casts = [
        'probability' => 'integer',
        'order' => 'integer',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function pipeline()
    {
        return $this->belongsTo(Pipeline::class);
    }

    public function deals()
    {
        return $this->hasMany(Deal::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Helper Methods
    public function getTotalDealsAttribute()
    {
        return $this->deals()->count();
    }

    public function getTotalValueAttribute()
    {
        return $this->deals()->sum('amount');
    }

    public function getWeightedValueAttribute()
    {
        return $this->getTotalValueAttribute() * ($this->probability / 100);
    }

    public function getConversionRateAttribute()
    {
        $totalDeals = $this->deals()->whereIn('status', ['won', 'lost'])->count();
        $wonDeals = $this->deals()->where('status', 'won')->count();
        
        return $totalDeals > 0 ? ($wonDeals / $totalDeals) * 100 : 0;
    }

    public function getAverageDealTimeAttribute()
    {
        $deals = $this->deals()
            ->whereNotNull('completed_at')
            ->get();

        if ($deals->isEmpty()) {
            return 0;
        }

        $totalDays = $deals->sum(function ($deal) {
            return $deal->created_at->diffInDays($deal->completed_at);
        });

        return $totalDays / $deals->count();
    }
} 