<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    protected $fillable = [
        'customer_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'position',
        'department',
        'is_primary',
        'notes'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
