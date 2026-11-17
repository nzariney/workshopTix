<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Registration;
use App\Models\Ticket;
use App\Models\Workshop;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function create(string $slug): View|RedirectResponse
    {
        $workshop = Workshop::withCount(['registrations' => function ($q) {
            $q->where('status', '!=', 'cancelled');
        }])->where('slug', $slug)->firstOrFail();

        // Check if workshop closed or full
        if ($workshop->status === 'closed') {
            return redirect()->route('workshops.show', $workshop->slug)
                ->with('error', 'Pendaftaran untuk workshop ini telah ditutup oleh panitia.');
        }

        if ($workshop->is_full) {
            return redirect()->route('workshops.show', $workshop->slug)
                ->with('error', 'Mohon maaf, kuota peserta workshop ini sudah penuh.');
        }

        // Check duplicate registration
        $existing = Registration::with(['payment', 'ticket'])
            ->where('user_id', auth()->id())
            ->where('workshop_id', $workshop->id)
            ->first();

        if ($existing) {
            if ($existing->ticket) {
                return redirect()->route('tickets.show', $existing->ticket->ticket_code)
                    ->with('info', 'Anda sudah terdaftar dan tiket digital Anda telah diterbitkan.');
            }

            if ($existing->payment) {
                return redirect()->route('payments.show', $existing->payment->payment_code)
                    ->with('info', 'Anda sudah terdaftar. Silakan lanjutkan atau verifikasi pembayaran Anda.');
            }
        }

        $user = auth()->user();

        return view('registrations.create', compact('workshop', 'user'));
    }

    public function store(Request $request, string $slug): RedirectResponse
    {
        $workshop = Workshop::withCount(['registrations' => function ($q) {
            $q->where('status', '!=', 'cancelled');
        }])->where('slug', $slug)->firstOrFail();

        // 1. Quota & Status check
        if ($workshop->status === 'closed' || $workshop->is_full) {
            return redirect()->route('workshops.show', $workshop->slug)
                ->with('error', 'Workshop sudah tidak menerima pendaftaran baru (kuota penuh atau pendaftaran ditutup).');
        }

        // 2. Strict Anti-Duplicate Check (As requested in brief step 03 & 06)
        $hasRegistered = Registration::where('user_id', auth()->id())
            ->where('workshop_id', $workshop->id)
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($hasRegistered) {
            return redirect()->route('workshops.show', $workshop->slug)
                ->with('error', 'Pendaftaran ganda ditolak! Anda sudah terdaftar dalam workshop ini.');
        }

        // 3. Validation
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'institution' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:500'],
            'payment_method' => ['required', 'string'],
        ], [
            'full_name.required' => 'Nama lengkap peserta wajib diisi.',
            'email.required' => 'Email peserta wajib diisi.',
            'phone.required' => 'Nomor WhatsApp / telepon wajib diisi.',
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
        ]);

        $user = auth()->user();

        // 4. Atomic Database Transaction
        $payment = DB::transaction(function () use ($validated, $workshop, $user) {
            $regCode = 'REG-'.date('Ymd').'-'.strtoupper(Str::random(5));
            $isFree = (float) $workshop->price === 0.0;

            $registration = Registration::create([
                'registration_code' => $regCode,
                'user_id' => $user->id,
                'workshop_id' => $workshop->id,
                'full_name' => $validated['full_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'institution' => $validated['institution'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'status' => $isFree ? 'paid' : 'pending_payment',
            ]);

            $payCode = 'PAY-'.date('Ymd').'-'.strtoupper(Str::random(5));
            $payment = Payment::create([
                'payment_code' => $payCode,
                'registration_id' => $registration->id,
                'user_id' => $user->id,
                'amount' => $workshop->price,
                'payment_method' => $validated['payment_method'],
                'status' => $isFree ? 'verified' : 'pending',
                'verified_at' => $isFree ? now() : null,
            ]);

            // If free workshop, issue ticket right away
            if ($isFree) {
                Ticket::create([
                    'ticket_code' => 'TIX-WTX-'.date('Y').'-'.strtoupper(Str::random(6)),
                    'registration_id' => $registration->id,
                    'user_id' => $user->id,
                    'workshop_id' => $workshop->id,
                    'qr_token' => Str::uuid()->toString(),
                    'status' => 'valid',
                ]);
            }

            // Check if quota now full
            $updatedRegisteredCount = $workshop->registrations()->where('status', '!=', 'cancelled')->count();
            if ($updatedRegisteredCount >= $workshop->quota) {
                $workshop->update(['status' => 'full']);
            }

            return $payment;
        });

        if ((float) $workshop->price === 0.0) {
            $ticket = Ticket::where('registration_id', $payment->registration_id)->first();

            return redirect()->route('tickets.show', $ticket->ticket_code)
                ->with('success', 'Pendaftaran gratis berhasil dikonfirmasi! Tiket digital Anda telah aktif.');
        }

        return redirect()->route('payments.show', $payment->payment_code)
            ->with('success', 'Formulir pendaftaran berhasil disimpan. Silakan lakukan pembayaran untuk memvalidasi tiket.');
    }

    public function show(string $code): View
    {
        $registration = Registration::with(['workshop', 'payment', 'ticket'])
            ->where('registration_code', $code)
            ->firstOrFail();

        // Authorization check: participant can only view their own registration unless admin
        if (! auth()->user()->isAdmin() && $registration->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk pendaftaran ini.');
        }

        return view('registrations.show', compact('registration'));
    }
}
