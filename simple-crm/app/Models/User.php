<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'permissions',
        'department',
        'phone',
        'timezone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'permissions' => 'array',
    ];

    // Relationships
    public function activities()
    {
        return $this->hasMany(Activity::class, 'assigned_to');
    }

    // Helper methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function hasPermission($permission)
    {
        if ($this->isAdmin()) {
            return true;
        }

        $permissions = $this->permissions ?? [];
        return in_array($permission, $permissions);
    }
}
