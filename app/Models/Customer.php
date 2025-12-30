<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'customer_number',
        'company_name',
        'contact_person',
        'email',
        'phone',
        'address',
        'status',
        'subscription_start',
    ];

    protected $casts = [
        'subscription_start' => 'date',
    ];

    public const STATUS_LABELS = [
        'active' => 'Aktif',
        'inactive' => 'Tidak Aktif',
        'suspended' => 'Ditangguhkan',
    ];

    public const STATUS_COLORS = [
        'active' => 'green',
        'inactive' => 'gray',
        'suspended' => 'red',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($customer) {
            if (empty($customer->customer_number)) {
                $customer->customer_number = 'CUST-' . date('Ymd') . '-' . str_pad(
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

    public function getTotalMonthlyAttribute(): float
    {
        return $this->products()
            ->wherePivot('status', 'active')
            ->sum('customer_products.monthly_price');
    }

    public function getFormattedTotalMonthlyAttribute(): string
    {
        return 'Rp ' . number_format($this->total_monthly, 0, ',', '.');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'customer_products')
            ->withPivot('monthly_price', 'start_date', 'end_date', 'status')
            ->withTimestamps();
    }

    public function activeProducts(): BelongsToMany
    {
        return $this->products()->wherePivot('status', 'active');
    }

    public static function createFromProject(Project $project): self
    {
        $customer = self::create([
            'project_id' => $project->id,
            'company_name' => $project->lead->company_name,
            'contact_person' => $project->lead->contact_person,
            'email' => $project->lead->email,
            'phone' => $project->lead->phone,
            'address' => $project->lead->address,
            'status' => 'active',
            'subscription_start' => now(),
        ]);

        foreach ($project->products as $product) {
            $customer->products()->attach($product->id, [
                'monthly_price' => $product->pivot->price,
                'start_date' => now(),
                'status' => 'active',
            ]);
        }

        $project->status = 'completed';
        $project->save();

        $project->lead->status = 'qualified';
        $project->lead->save();

        return $customer;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
