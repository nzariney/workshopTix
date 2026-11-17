<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workshop extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'instructor_name',
        'instructor_title',
        'description',
        'target_audience',
        'learning_points',
        'prerequisites',
        'date',
        'start_time',
        'end_time',
        'location_type',
        'location',
        'price',
        'quota',
        'image',
        'status',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'price' => 'decimal:2',
            'quota' => 'integer',
            'is_featured' => 'boolean',
        ];
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('status', 'available');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function getRegisteredCountAttribute(): int
    {
        return $this->registrations()->where('status', '!=', 'cancelled')->count();
    }

    public function getRemainingQuotaAttribute(): int
    {
        return max(0, $this->quota - $this->registered_count);
    }

    public function getIsFullAttribute(): bool
    {
        return $this->remaining_quota <= 0 || $this->status === 'full';
    }

    public function getFormattedPriceAttribute(): string
    {
        if ((float) $this->price === 0.0) {
            return 'Gratis';
        }

        return 'Rp '.number_format($this->price, 0, ',', '.');
    }

    public function getFormattedDateAttribute(): string
    {
        return Carbon::parse($this->date)->translatedFormat('d F Y');
    }

    public function getLearningPointsListAttribute(): array
    {
        if (empty($this->learning_points)) {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode("\n", $this->learning_points))));
    }
}
