<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_number',
        'lead_id',
        'created_by',
        'approved_by',
        'status',
        'notes',
        'rejection_reason',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public const STATUS_LABELS = [
        'draft' => 'Draf',
        'pending_approval' => 'Menunggu Persetujuan',
        'approved' => 'Disetujui',
        'rejected' => 'Ditolak',
        'completed' => 'Selesai',
    ];

    public const STATUS_COLORS = [
        'draft' => 'gray',
        'pending_approval' => 'yellow',
        'approved' => 'green',
        'rejected' => 'red',
        'completed' => 'blue',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            if (empty($project->project_number)) {
                $project->project_number = 'PRJ-' . date('Ymd') . '-' . str_pad(
                    static::whereDate('created_at', today())->count() + 1,
                    4,
                    '0',
                    STR_PAD_LEFT
                );
            }
        });
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'gray';
    }

    public function getTotalPriceAttribute(): float
    {
        return $this->products->sum(function ($product) {
            return $product->pivot->price * $product->pivot->quantity;
        });
    }

    public function getFormattedTotalPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'project_products')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class);
    }

    public function canBeApproved(): bool
    {
        return $this->status === 'pending_approval';
    }

    public function canBeSubmitted(): bool
    {
        return $this->status === 'draft' && $this->products()->count() > 0;
    }

    public function submitForApproval(): bool
    {
        if (!$this->canBeSubmitted()) {
            return false;
        }

        $this->status = 'pending_approval';
        return $this->save();
    }

    public function approve(User $approver): bool
    {
        if (!$this->canBeApproved()) {
            return false;
        }

        $this->status = 'approved';
        $this->approved_by = $approver->id;
        $this->approved_at = now();
        return $this->save();
    }

    public function reject(User $approver, string $reason): bool
    {
        if (!$this->canBeApproved()) {
            return false;
        }

        $this->status = 'rejected';
        $this->approved_by = $approver->id;
        $this->rejection_reason = $reason;
        $this->approved_at = now();
        return $this->save();
    }

    public function scopePendingApproval($query)
    {
        return $query->where('status', 'pending_approval');
    }
}
