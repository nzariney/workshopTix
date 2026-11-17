<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Ticket;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class MidtransService
{
    public function __construct()
    {
        $this->configure();
    }

    public function configure(): void
    {
        Config::$serverKey = (string) config('midtrans.server_key');
        Config::$clientKey = (string) config('midtrans.client_key');
        Config::$isProduction = (bool) config('midtrans.is_production', false);
        Config::$isSanitized = (bool) config('midtrans.is_sanitized', true);
        Config::$is3ds = (bool) config('midtrans.is_3ds', true);
    }

    public function isConfigured(): bool
    {
        $serverKey = config('midtrans.server_key');

        return ! empty($serverKey)
            && $serverKey !== 'ISI_SERVER_KEY';
    }

    public function getSnapUrl(): string
    {
        return config('midtrans.is_production')
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    }

    public function getOrCreateTransaction(Payment $payment): ?object
    {
        if ($payment->snap_token && ! empty($payment->midtrans_response['redirect_url'])) {
            return (object) [
                'token' => $payment->snap_token,
                'redirect_url' => $payment->midtrans_response['redirect_url'],
            ];
        }

        if (! $this->isConfigured()) {
            return null;
        }

        try {
            $this->configure();

            $workshop = $payment->registration->workshop;
            $user = $payment->user;
            $reg = $payment->registration;

            $params = [
                'transaction_details' => [
                    'order_id' => $payment->payment_code,
                    'gross_amount' => (int) $payment->amount,
                ],
                'customer_details' => [
                    'first_name' => $reg->full_name ?: $user->name,
                    'email' => $reg->email ?: $user->email,
                    'phone' => $reg->phone ?: ($user->phone ?: '081234567890'),
                ],
                'item_details' => [
                    [
                        'id' => (string) $workshop->id,
                        'price' => (int) $payment->amount,
                        'quantity' => 1,
                        'name' => mb_substr($workshop->title, 0, 50),
                    ],
                ],
            ];

            $transaction = Snap::createTransaction($params);

            $payment->update([
                'snap_token' => $transaction->token,
                'midtrans_response' => array_merge($payment->midtrans_response ?? [], [
                    'redirect_url' => $transaction->redirect_url,
                ]),
            ]);

            return $transaction;
        } catch (Exception $e) {
            Log::error('Gagal membuat Midtrans Snap Transaction: '.$e->getMessage(), [
                'payment_code' => $payment->payment_code,
            ]);

            return null;
        }
    }

    public function getOrCreateSnapToken(Payment $payment): ?string
    {
        if ($payment->snap_token) {
            return $payment->snap_token;
        }

        $transaction = $this->getOrCreateTransaction($payment);

        return $transaction?->token;
    }

    public function getOrCreatePaymentUrl(Payment $payment): ?string
    {
        if (! empty($payment->midtrans_response['redirect_url'])) {
            return $payment->midtrans_response['redirect_url'];
        }

        if ($payment->snap_token) {
            $isProduction = (bool) config('midtrans.is_production', false);
            $baseUrl = $isProduction ? 'https://app.midtrans.com' : 'https://app.sandbox.midtrans.com';

            return "{$baseUrl}/snap/v4/redirection/{$payment->snap_token}";
        }

        $transaction = $this->getOrCreateTransaction($payment);

        return $transaction?->redirect_url;
    }

    public function checkAndUpdateStatus(Payment $payment): string
    {
        if (! $this->isConfigured()) {
            return $payment->status;
        }

        try {
            $this->configure();
            $status = Transaction::status($payment->payment_code);

            return $this->applyStatusUpdate($payment, (array) $status);
        } catch (Exception $e) {
            Log::warning('Midtrans check status warning: '.$e->getMessage(), [
                'payment_code' => $payment->payment_code,
            ]);

            return $payment->status;
        }
    }

    public function applyStatusUpdate(Payment $payment, array $payload): string
    {
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;
        $paymentType = $payload['payment_type'] ?? 'midtrans';

        if (! $transactionStatus) {
            return $payment->status;
        }

        $methodLabel = 'Midtrans ('.strtoupper(str_replace('_', ' ', $paymentType)).')';

        if ($transactionStatus === 'capture') {
            if ($fraudStatus === 'challenge') {
                $payment->update([
                    'status' => 'pending',
                    'payment_method' => $methodLabel,
                    'admin_notes' => 'Transaksi dalam peninjauan fraud Midtrans (challenge).',
                    'midtrans_response' => $payload,
                ]);

                return 'pending';
            }

            if ($fraudStatus === 'accept') {
                $this->fulfillPayment($payment, $methodLabel, $payload);

                return 'verified';
            }
        } elseif ($transactionStatus === 'settlement') {
            $this->fulfillPayment($payment, $methodLabel, $payload);

            return 'verified';
        } elseif ($transactionStatus === 'pending') {
            $payment->update([
                'status' => 'pending',
                'payment_method' => $methodLabel,
                'midtrans_response' => $payload,
            ]);

            return 'pending';
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'], true)) {
            $payment->update([
                'status' => 'rejected',
                'payment_method' => $methodLabel,
                'admin_notes' => 'Transaksi berstatus '.$transactionStatus.' di Midtrans.',
                'midtrans_response' => $payload,
            ]);

            return 'rejected';
        }

        return $payment->status;
    }

    public function fulfillPayment(Payment $payment, string $methodLabel, array $payload): void
    {
        DB::transaction(function () use ($payment, $methodLabel, $payload) {
            $payment->update([
                'status' => 'verified',
                'payment_method' => $methodLabel,
                'admin_notes' => 'Pembayaran lunas terkonfirmasi oleh Midtrans Payment Gateway.',
                'verified_at' => now(),
                'midtrans_response' => $payload,
            ]);

            $payment->registration->update([
                'status' => 'paid',
            ]);

            // Issue ticket automatically
            Ticket::firstOrCreate(
                ['registration_id' => $payment->registration_id],
                [
                    'ticket_code' => 'TIX-WTX-'.date('Y').'-'.strtoupper(Str::random(6)),
                    'user_id' => $payment->user_id,
                    'workshop_id' => $payment->registration->workshop_id,
                    'qr_token' => Str::uuid()->toString(),
                    'status' => 'valid',
                ]
            );
        });
    }
}
