<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    public function isSales(): bool
    {
        return $this->role === 'sales';
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'created_by');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'created_by');
    }

    public function approvedProjects(): HasMany
    {
        return $this->hasMany(Project::class, 'approved_by');
    }
}

