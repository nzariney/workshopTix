<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Ticket;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(
        protected MidtransService $midtransService
    ) {}

    public function show(string $code): View|RedirectResponse
    {
        $payment = Payment::with(['registration.workshop', 'user'])
            ->where('payment_code', $code)
            ->firstOrFail();

        // Security check: Only owner or admin can view this payment
        if (! auth()->user()->isAdmin() && $payment->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki hak akses melihat transaksi pembayaran ini.');
        }

        // If still pending and Midtrans is configured, verify if it was already settled in Midtrans
        if ($payment->status === 'pending' && $this->midtransService->isConfigured()) {
            $this->midtransService->checkAndUpdateStatus($payment);
            $payment->refresh();
        }

        // If already verified, load ticket
        $ticket = null;
        if ($payment->status === 'verified') {
            $ticket = Ticket::where('registration_id', $payment->registration_id)->first();
        }

        // Generate or get Midtrans Snap Token & Payment URL
        $snapToken = null;
        $paymentUrl = null;
        $isMidtransConfigured = $this->midtransService->isConfigured();
        $snapUrl = $this->midtransService->getSnapUrl();
        $clientKey = config('midtrans.client_key');

        if ($payment->status !== 'verified' && $isMidtransConfigured) {
            $transaction = $this->midtransService->getOrCreateTransaction($payment);
            $snapToken = $transaction?->token ?? $payment->snap_token;
            $paymentUrl = $transaction?->redirect_url ?? $payment->payment_url;
        }

        return view('payments.checkout', compact(
            'payment',
            'ticket',
            'snapToken',
            'paymentUrl',
            'snapUrl',
            'isMidtransConfigured',
            'clientKey'
        ));
    }

    public function payViaMidtrans(string $code): RedirectResponse
    {
        $payment = Payment::with(['registration.workshop', 'user'])
            ->where('payment_code', $code)
            ->firstOrFail();

        if (! auth()->user()->isAdmin() && $payment->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        if ($payment->status === 'verified') {
            $ticket = Ticket::where('registration_id', $payment->registration_id)->first();
            if ($ticket) {
                return redirect()->route('tickets.show', $ticket->ticket_code)
                    ->with('info', 'Pembayaran transaksi ini sudah lunas.');
            }

            return redirect()->route('payments.show', $payment->payment_code);
        }

        $url = $this->midtransService->getOrCreatePaymentUrl($payment);

        if (! $url) {
            return redirect()->route('payments.show', $payment->payment_code)
                ->with('error', 'Gagal menghubungkan ke Midtrans Gateway. Silakan coba sesaat lagi.');
        }

        return redirect()->away($url);
    }

    public function uploadProof(Request $request, string $code): RedirectResponse
    {
        $payment = Payment::where('payment_code', $code)->firstOrFail();

        if (! auth()->user()->isAdmin() && $payment->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'payment_proof' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
        ], [
            'payment_proof.required' => 'Silakan pilih berkas foto atau screenshot bukti transfer.',
            'payment_proof.image' => 'Berkas bukti pembayaran harus berupa gambar.',
            'payment_proof.mimes' => 'Format file yang diperbolehkan adalah JPG, JPEG, PNG, atau WEBP.',
            'payment_proof.max' => 'Ukuran file gambar maksimal 4 MB.',
        ]);

        if ($request->hasFile('payment_proof')) {
            // Delete old file if exists and local
            if ($payment->payment_proof && ! str_starts_with($payment->payment_proof, 'http')) {
                Storage::disk('public')->delete($payment->payment_proof);
            }

            $path = $request->file('payment_proof')->store('payments', 'public');

            $payment->update([
                'payment_proof' => $path,
                'status' => 'pending',
                'admin_notes' => null, // reset rejection notes upon re-upload
            ]);
        }

        return back()->with('success', 'Bukti pembayaran berhasil diunggah! Panitia WORKSHOPTIX akan memverifikasi transaksi Anda.');
    }

    public function midtransFinish(Request $request, string $code): RedirectResponse
    {
        $payment = Payment::with(['registration.workshop'])->where('payment_code', $code)->firstOrFail();

        if (! auth()->user()->isAdmin() && $payment->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        // 1. Try to query official status from Midtrans API directly
        $status = $this->midtransService->checkAndUpdateStatus($payment);
        $payment->refresh();

        // 2. If status from API or request indicates success
        $resultData = $request->input('result_data');
        if (is_string($resultData)) {
            $resultData = json_decode($resultData, true) ?? [];
        }

        if ($payment->status !== 'verified' && is_array($resultData) && ! empty($resultData)) {
            $this->midtransService->applyStatusUpdate($payment, $resultData);
            $payment->refresh();
        }

        if ($payment->status === 'verified') {
            $ticket = Ticket::where('registration_id', $payment->registration_id)->first();

            return redirect()->route('tickets.show', $ticket->ticket_code)
                ->with('success', 'Pembayaran Midtrans Berhasil Diverifikasi! Tiket digital resmi Anda telah terbit.');
        }

        if ($payment->status === 'rejected') {
            return redirect()->route('payments.show', $payment->payment_code)
                ->with('error', 'Pembayaran Midtrans dibatalkan, ditolak, atau kedaluwarsa.');
        }

        return redirect()->route('payments.show', $payment->payment_code)
            ->with('info', 'Pembayaran Midtrans sedang diproses. Silakan selesaikan pembayaran sesuai petunjuk pada metode yang dipilih.');
    }

    public function checkStatus(Request $request, string $code): RedirectResponse
    {
        $payment = Payment::with(['registration.workshop'])->where('payment_code', $code)->firstOrFail();

        if (! auth()->user()->isAdmin() && $payment->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        $newStatus = $this->midtransService->checkAndUpdateStatus($payment);
        $payment->refresh();

        if ($payment->status === 'verified') {
            $ticket = Ticket::where('registration_id', $payment->registration_id)->first();

            return redirect()->route('tickets.show', $ticket->ticket_code)
                ->with('success', 'Pembayaran Midtrans terkonfirmasi lunas! Tiket digital Anda telah aktif.');
        }

        return back()->with('info', 'Status pembayaran saat ini: '.strtoupper($newStatus));
    }

    public function handleNotification(Request $request): JsonResponse
    {
        $payload = $request->all();

        Log::info('Midtrans Webhook Notification Received', $payload);

        $orderId = (string) $request->input('order_id');
        $statusCode = (string) $request->input('status_code');
        $grossAmount = (string) $request->input('gross_amount');
        $serverKey = (string) config('midtrans.server_key');
        $signature = (string) $request->input('signature_key');

        $calculatedSignature = hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);

        if ($signature !== $calculatedSignature) {
            Log::warning('Midtrans Webhook Invalid Signature', [
                'received' => $signature,
                'calculated' => $calculatedSignature,
            ]);

            return response()->json(['message' => 'Invalid signature key'], 403);
        }

        $payment = Payment::with(['registration.workshop'])->where('payment_code', $orderId)->first();

        if (! $payment) {
            Log::warning('Midtrans Webhook Payment Not Found: '.$orderId);

            return response()->json(['message' => 'Payment order not found'], 404);
        }

        $this->midtransService->applyStatusUpdate($payment, $payload);

        return response()->json(['status' => 'OK']);
    }
}
