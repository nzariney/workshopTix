<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Ticket;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function myTickets(): View
    {
        $user = auth()->user();

        // Eager load relationships to prevent N+1 query
        $tickets = Ticket::with(['workshop', 'registration'])
            ->where('user_id', $user->id)
            ->latest('id')
            ->get();

        $pendingRegistrations = Registration::with(['workshop', 'payment'])
            ->where('user_id', $user->id)
            ->where('status', 'pending_payment')
            ->latest('id')
            ->get();

        return view('tickets.index', compact('tickets', 'pendingRegistrations'));
    }

    public function show(string $code): View
    {
        // Eager load workshop, registration, and user
        $ticket = Ticket::with(['workshop', 'registration.payment', 'user'])
            ->where('ticket_code', $code)
            ->firstOrFail();

        // Strict authorization: Only the owner or panitia/admin can access
        if (! auth()->user()->isAdmin() && $ticket->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak! Anda tidak memiliki izin untuk melihat tiket digital milik peserta lain.');
        }

        return view('tickets.show', compact('ticket'));
    }

    public function printTicket(string $code): View
    {
        $ticket = Ticket::with(['workshop', 'registration', 'user'])
            ->where('ticket_code', $code)
            ->firstOrFail();

        if (! auth()->user()->isAdmin() && $ticket->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        return view('tickets.print', compact('ticket'));
    }

    public function verify(string $code): View
    {
        // Look up ticket by ticket_code or qr_token
        $ticket = Ticket::with(['workshop', 'registration', 'user'])
            ->where('ticket_code', $code)
            ->orWhere('qr_token', $code)
            ->first();

        return view('tickets.verify', compact('ticket', 'code'));
    }
}
