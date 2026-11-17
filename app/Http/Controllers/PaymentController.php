<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function show(string $code): View|RedirectResponse
    {
        $payment = Payment::with(['registration.workshop', 'user'])
            ->where('payment_code', $code)
            ->firstOrFail();

        // Security check: Only owner or admin can view this payment
        if (! auth()->user()->isAdmin() && $payment->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki hak akses melihat transaksi pembayaran ini.');
        }

        // If already verified, also load ticket
        $ticket = null;
        if ($payment->status === 'verified') {
            $ticket = Ticket::where('registration_id', $payment->registration_id)->first();
        }

        return view('payments.checkout', compact('payment', 'ticket'));
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

    public function simulateSandbox(Request $request, string $code): RedirectResponse
    {
        $payment = Payment::with(['registration.workshop'])->where('payment_code', $code)->firstOrFail();

        if (! auth()->user()->isAdmin() && $payment->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        if ($payment->status === 'verified') {
            $ticket = Ticket::where('registration_id', $payment->registration_id)->first();

            return redirect()->route('tickets.show', $ticket->ticket_code)
                ->with('info', 'Pembayaran transaksi ini sudah diverifikasi sebelumnya.');
        }

        // Run transaction to verify payment, mark registration paid, issue ticket
        $ticket = DB::transaction(function () use ($payment) {
            $payment->update([
                'status' => 'verified',
                'admin_notes' => 'Pembayaran terverifikasi via Midtrans Sandbox Simulator.',
                'verified_at' => now(),
            ]);

            $payment->registration->update([
                'status' => 'paid',
            ]);

            // Issue ticket if not yet issued
            $ticket = Ticket::firstOrCreate(
                ['registration_id' => $payment->registration_id],
                [
                    'ticket_code' => 'TIX-WTX-'.date('Y').'-'.strtoupper(Str::random(6)),
                    'user_id' => $payment->user_id,
                    'workshop_id' => $payment->registration->workshop_id,
                    'qr_token' => Str::uuid()->toString(),
                    'status' => 'valid',
                ]
            );

            return $ticket;
        });

        return redirect()->route('tickets.show', $ticket->ticket_code)
            ->with('success', 'Simulasi Pembayaran Midtrans Sandbox Berhasil! Tiket digital Anda telah aktif dan siap digunakan.');
    }
}
