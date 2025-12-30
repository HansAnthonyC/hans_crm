<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'contact_person',
        'email',
        'phone',
        'address',
        'status',
        'created_by',
    ];

    public const STATUS_LABELS = [
        'new' => 'Baru',
        'contacted' => 'Sudah Dihubungi',
        'qualified' => 'Memenuhi Syarat',
        'unqualified' => 'Tidak Memenuhi Syarat',
    ];

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function project(): HasOne
    {
        return $this->hasOne(Project::class);
    }

    public function hasProject(): bool
    {
        return $this->project()
            ->whereNotIn('status', ['rejected'])
            ->exists();
    }

    public function canCreateProject(): bool
    {
        if ($this->status === 'unqualified') {
            return false;
        }

        return !$this->hasProject();
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
