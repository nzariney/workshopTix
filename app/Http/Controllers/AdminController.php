<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Registration;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $metrics = [
            'total_participants' => User::where('role', 'participant')->count(),
            'total_workshops' => Workshop::count(),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'issued_tickets' => Ticket::count(),
            'checked_in_tickets' => Ticket::where('status', 'used')->count(),
            'total_revenue' => Payment::where('status', 'verified')->sum('amount'),
        ];

        // Eager loaded to prevent N+1 queries
        $recentRegistrations = Registration::with(['user', 'workshop', 'payment'])
            ->latest('id')
            ->take(5)
            ->get();

        $pendingPayments = Payment::with(['user', 'registration.workshop'])
            ->where('status', 'pending')
            ->latest('id')
            ->take(5)
            ->get();

        $activeWorkshops = Workshop::withCount(['registrations' => function ($q) {
            $q->where('status', '!=', 'cancelled');
        }])->latest('date')->take(5)->get();

        return view('admin.dashboard', compact('metrics', 'recentRegistrations', 'pendingPayments', 'activeWorkshops'));
    }

    /*
    |--------------------------------------------------------------------------
    | Workshops Management (CRUD)
    |--------------------------------------------------------------------------
    */

    public function workshopsIndex(Request $request): View
    {
        $query = Workshop::withCount(['registrations' => function ($q) {
            $q->where('status', '!=', 'cancelled');
        }]);

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('instructor_name', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $workshops = $query->latest('id')->paginate(10)->withQueryString();

        $categories = Workshop::select('category')->distinct()->pluck('category');

        return view('admin.workshops.index', compact('workshops', 'categories'));
    }

    public function workshopCreate(): View
    {
        return view('admin.workshops.create');
    }

    public function workshopStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'instructor_name' => ['required', 'string', 'max:255'],
            'instructor_title' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'target_audience' => ['nullable', 'string'],
            'learning_points' => ['nullable', 'string'],
            'prerequisites' => ['nullable', 'string'],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'string'],
            'end_time' => ['required', 'string'],
            'location_type' => ['required', 'in:offline,online,hybrid'],
            'location' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'quota' => ['required', 'integer', 'min:1'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'image_url' => ['nullable', 'url', 'max:500'],
            'status' => ['required', 'in:available,full,closed'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $counter = 1;
        while (Workshop::where('slug', $slug)->exists()) {
            $slug = $originalSlug.'-'.$counter;
            $counter++;
        }

        $imagePath = $validated['image_url'] ?? null;
        if ($request->hasFile('image')) {
            $imagePath = Storage::url($request->file('image')->store('workshops', 'public'));
        }

        Workshop::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'category' => $validated['category'],
            'instructor_name' => $validated['instructor_name'],
            'instructor_title' => $validated['instructor_title'] ?? null,
            'description' => $validated['description'],
            'target_audience' => $validated['target_audience'] ?? null,
            'learning_points' => $validated['learning_points'] ?? null,
            'prerequisites' => $validated['prerequisites'] ?? null,
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'location_type' => $validated['location_type'],
            'location' => $validated['location'],
            'price' => $validated['price'],
            'quota' => $validated['quota'],
            'image' => $imagePath,
            'status' => $validated['status'],
            'is_featured' => $request->boolean('is_featured'),
        ]);

        return redirect()->route('admin.workshops.index')
            ->with('success', 'Workshop IT baru berhasil ditambahkan.');
    }

    public function workshopEdit(int $id): View
    {
        $workshop = Workshop::findOrFail($id);

        return view('admin.workshops.edit', compact('workshop'));
    }

    public function workshopUpdate(Request $request, int $id): RedirectResponse
    {
        $workshop = Workshop::findOrFail($id);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'instructor_name' => ['required', 'string', 'max:255'],
            'instructor_title' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'target_audience' => ['nullable', 'string'],
            'learning_points' => ['nullable', 'string'],
            'prerequisites' => ['nullable', 'string'],
            'date' => ['required', 'date'],
            'start_time' => ['required', 'string'],
            'end_time' => ['required', 'string'],
            'location_type' => ['required', 'in:offline,online,hybrid'],
            'location' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'quota' => ['required', 'integer', 'min:1'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'image_url' => ['nullable', 'url', 'max:500'],
            'status' => ['required', 'in:available,full,closed'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $imagePath = $workshop->image;
        if (! empty($validated['image_url'])) {
            $imagePath = $validated['image_url'];
        }

        if ($request->hasFile('image')) {
            $imagePath = Storage::url($request->file('image')->store('workshops', 'public'));
        }

        $workshop->update([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'instructor_name' => $validated['instructor_name'],
            'instructor_title' => $validated['instructor_title'] ?? null,
            'description' => $validated['description'],
            'target_audience' => $validated['target_audience'] ?? null,
            'learning_points' => $validated['learning_points'] ?? null,
            'prerequisites' => $validated['prerequisites'] ?? null,
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'location_type' => $validated['location_type'],
            'location' => $validated['location'],
            'price' => $validated['price'],
            'quota' => $validated['quota'],
            'image' => $imagePath,
            'status' => $validated['status'],
            'is_featured' => $request->boolean('is_featured'),
        ]);

        return redirect()->route('admin.workshops.index')
            ->with('success', 'Perubahan data workshop berhasil disimpan.');
    }

    public function workshopDestroy(int $id): RedirectResponse
    {
        $workshop = Workshop::withCount('registrations')->findOrFail($id);

        if ($workshop->registrations_count > 0) {
            return back()->with('error', 'Workshop tidak dapat dihapus karena sudah memiliki '.$workshop->registrations_count.' pendaftar. Anda dapat mengubah statusnya menjadi "closed".');
        }

        $workshop->delete();

        return redirect()->route('admin.workshops.index')
            ->with('success', 'Workshop berhasil dihapus dari sistem.');
    }

    public function toggleWorkshopStatus(int $id): RedirectResponse
    {
        $workshop = Workshop::findOrFail($id);
        $newStatus = $workshop->status === 'available' ? 'closed' : 'available';
        $workshop->update(['status' => $newStatus]);

        return back()->with('success', 'Status workshop diubah menjadi '.ucfirst($newStatus).'.');
    }

    /*
    |--------------------------------------------------------------------------
    | Registrations Management (Demonstrates N+1 query prevention)
    |--------------------------------------------------------------------------
    */

    public function registrationsIndex(Request $request): View
    {
        // LKPD Step 05: Demonstrating eager loading with(['user', 'workshop', 'payment'])
        $query = Registration::with(['user', 'workshop', 'payment', 'ticket']);

        if ($workshopId = $request->input('workshop_id')) {
            $query->where('workshop_id', $workshopId);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('registration_code', 'like', "%{$search}%");
            });
        }

        $registrations = $query->latest('id')->paginate(15)->withQueryString();
        $workshops = Workshop::select('id', 'title')->orderBy('title')->get();

        return view('admin.registrations.index', compact('registrations', 'workshops'));
    }

    public function registrationDestroy(int $id): RedirectResponse
    {
        $registration = Registration::findOrFail($id);
        $registration->delete();

        return back()->with('success', 'Data pendaftaran berhasil dihapus.');
    }

    /*
    |--------------------------------------------------------------------------
    | Payments Verification
    |--------------------------------------------------------------------------
    */

    public function paymentsIndex(Request $request): View
    {
        $query = Payment::with(['user', 'registration.workshop', 'verifier']);

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('payment_code', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $payments = $query->latest('id')->paginate(15)->withQueryString();

        return view('admin.payments.index', compact('payments'));
    }

    public function verifyPayment(Request $request, int $id): RedirectResponse
    {
        $payment = Payment::with('registration.workshop')->findOrFail($id);

        if ($payment->status === 'verified') {
            return back()->with('info', 'Pembayaran ini sudah berstatus terverifikasi.');
        }

        DB::transaction(function () use ($payment) {
            $payment->update([
                'status' => 'verified',
                'verified_at' => now(),
                'verified_by' => auth()->id(),
                'admin_notes' => 'Pembayaran telah diverifikasi secara manual oleh panitia.',
            ]);

            $payment->registration->update([
                'status' => 'paid',
            ]);

            // Issue ticket if not yet issued
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

        return back()->with('success', 'Pembayaran '.$payment->payment_code.' berhasil diverifikasi dan tiket digital otomatis diterbitkan!');
    }

    public function rejectPayment(Request $request, int $id): RedirectResponse
    {
        $payment = Payment::findOrFail($id);

        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ], [
            'reason.required' => 'Alasan penolakan bukti pembayaran wajib diisi.',
        ]);

        $payment->update([
            'status' => 'rejected',
            'admin_notes' => $validated['reason'],
            'verified_at' => now(),
            'verified_by' => auth()->id(),
        ]);

        return back()->with('success', 'Pembayaran '.$payment->payment_code.' telah ditolak dengan catatan.');
    }

    /*
    |--------------------------------------------------------------------------
    | Tickets & QR Code Scanner / Gate Check-in
    |--------------------------------------------------------------------------
    */

    public function ticketsIndex(Request $request): View
    {
        $query = Ticket::with(['user', 'workshop', 'registration', 'checkInAdmin']);

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($workshopId = $request->input('workshop_id')) {
            $query->where('workshop_id', $workshopId);
        }

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('ticket_code', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $tickets = $query->latest('id')->paginate(15)->withQueryString();
        $workshops = Workshop::select('id', 'title')->orderBy('title')->get();

        return view('admin.tickets.index', compact('tickets', 'workshops'));
    }

    public function checkInTicket(string $code): RedirectResponse
    {
        $ticket = Ticket::with(['user', 'workshop'])
            ->where('ticket_code', $code)
            ->orWhere('qr_token', $code)
            ->firstOrFail();

        if ($ticket->status === 'used') {
            return back()->with('warning', 'Peringatan: Tiket '.$ticket->ticket_code.' sudah digunakan sebelumnya pada '.$ticket->checked_in_at->format('d/m/Y H:i').' WIB.');
        }

        $ticket->update([
            'status' => 'used',
            'checked_in_at' => now(),
            'checked_in_by' => auth()->id(),
        ]);

        return back()->with('success', 'Check-in Berhasil! Peserta "'.$ticket->user->name.'" telah divalidasi untuk workshop "'.$ticket->workshop->title.'".');
    }

    public function scannerView(): View
    {
        return view('admin.tickets.scanner');
    }

    public function scanProcess(Request $request): RedirectResponse
    {
        $code = trim($request->input('code'));

        if (! $code) {
            return back()->with('error', 'Silakan masukkan kode tiket atau scan QR Code.');
        }

        $ticket = Ticket::with(['user', 'workshop'])
            ->where('ticket_code', $code)
            ->orWhere('qr_token', $code)
            ->first();

        if (! $ticket) {
            return back()->with('error', 'Tiket tidak ditemukan dalam sistem! Pastikan kode tiket benar.');
        }

        if ($ticket->status === 'used') {
            return back()->with('warning', 'TIKET SUDAH DIGUNAKAN! Peserta: '.$ticket->user->name.' ('.$ticket->workshop->title.') sudah check-in pada '.$ticket->checked_in_at->format('d/m/Y H:i').' WIB.');
        }

        if ($ticket->status === 'cancelled') {
            return back()->with('error', 'TIKET DIBATALKAN. Tiket ini tidak sah untuk mengikuti workshop.');
        }

        $ticket->update([
            'status' => 'used',
            'checked_in_at' => now(),
            'checked_in_by' => auth()->id(),
        ]);

        return back()->with('success', 'VALIDASI SUKSES: Check-in berhasil untuk '.$ticket->user->name.' - '.$ticket->workshop->title.' (Kode: '.$ticket->ticket_code.').');
    }

    /*
    |--------------------------------------------------------------------------
    | Users / Participants List
    |--------------------------------------------------------------------------
    */

    public function usersIndex(Request $request): View
    {
        $query = User::withCount(['registrations', 'tickets']);

        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('institution', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->latest('id')->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }
}
