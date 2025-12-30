<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pipeline extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'is_active',
        'order',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    // Relationships
    public function stages()
    {
        return $this->hasMany(PipelineStage::class)->orderBy('order');
    }

    public function deals()
    {
        return $this->hasMany(Deal::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
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

    public function getWinRateAttribute()
    {
        $totalDeals = $this->deals()->whereIn('status', ['won', 'lost'])->count();
        $wonDeals = $this->deals()->where('status', 'won')->count();
        
        return $totalDeals > 0 ? ($wonDeals / $totalDeals) * 100 : 0;
    }
} 