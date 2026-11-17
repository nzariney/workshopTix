<?php

namespace App\Models;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_code',
        'registration_id',
        'user_id',
        'workshop_id',
        'qr_token',
        'status',
        'checked_in_at',
        'checked_in_by',
    ];

    protected function casts(): array
    {
        return [
            'checked_in_at' => 'datetime',
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

    public function workshop(): BelongsTo
    {
        return $this->belongsTo(Workshop::class);
    }

    public function checkInAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_in_by');
    }

    public function getVerificationUrlAttribute(): string
    {
        return route('tickets.verify', ['code' => $this->ticket_code]);
    }

    public function getQrCodeBase64Attribute(): string
    {
        try {
            $options = new QROptions([
                'outputType' => QRCode::OUTPUT_MARKUP_SVG,
                'imageBase64' => true,
            ]);

            return (new QRCode($options))->render($this->verification_url);
        } catch (\Throwable $e) {
            return (new QRCode)->render($this->ticket_code);
        }
    }
}
