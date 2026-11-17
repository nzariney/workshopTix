<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_code',
        'registration_id',
        'user_id',
        'amount',
        'payment_method',
        'snap_token',
        'midtrans_response',
        'payment_proof',
        'status',
        'admin_notes',
        'verified_at',
        'verified_by',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'verified_at' => 'datetime',
            'midtrans_response' => 'array',
        ];
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp '.number_format($this->amount, 0, ',', '.');
    }

    public function getPaymentUrlAttribute(): ?string
    {
        if ($this->midtrans_response && ! empty($this->midtrans_response['redirect_url'])) {
            return $this->midtrans_response['redirect_url'];
        }

        if ($this->snap_token) {
            $isProduction = (bool) config('midtrans.is_production', false);
            $baseUrl = $isProduction ? 'https://app.midtrans.com' : 'https://app.sandbox.midtrans.com';

            return "{$baseUrl}/snap/v4/redirection/{$this->snap_token}";
        }

        return null;
    }

    public function getProofUrlAttribute(): ?string
    {
        if (! $this->payment_proof) {
            return null;
        }

        if (str_starts_with($this->payment_proof, 'http')) {
            return $this->payment_proof;
        }

        return Storage::url($this->payment_proof);
    }
}
