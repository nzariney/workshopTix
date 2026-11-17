@extends('layouts.admin')

@section('content')
<div class="space-y-8">
    <!-- Header Welcome Banner -->
    <div class="rounded-2xl bg-gradient-to-r from-red-600 via-red-700 to-gray-950 p-6 sm:p-8 text-white shadow-lg shadow-red-600/10">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="rounded bg-white/20 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-red-100">
                    Sistem Manajemen WORKSHOPTIX
                </span>
                <h1 class="text-2xl sm:text-3xl font-black mt-2">Selamat Datang, {{ Auth::user()->name }}</h1>
                <p class="text-xs sm:text-sm text-red-100 mt-1 max-w-xl">
                    Panel kendali panitia workshop IT: Kelola kuota peserta, pantau verifikasi mutasi transfer, dan validasi tiket digital QR Code.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.scanner') }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-xs font-bold text-gray-950 shadow-md hover:bg-gray-100 transition">
                    <svg class="h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5zM13.5 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5z" />
                    </svg>
                    <span>Buka Gate Scanner</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Metrics Cards Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 sm:gap-6">
        <!-- Metric 1: Total Peserta -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-gray-500">Total Peserta</span>
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-black text-gray-900 mt-2">{{ $metrics['total_participants'] }}</div>
            <span class="text-[11px] text-gray-400 mt-0.5 block">Akun terdaftar</span>
        </div>

        <!-- Metric 2: Total Workshop -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-gray-500">Workshop IT</span>
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-black text-gray-900 mt-2">{{ $metrics['total_workshops'] }}</div>
            <span class="text-[11px] text-gray-400 mt-0.5 block">Kelas aktif</span>
        </div>

        <!-- Metric 3: Pending Payments -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-gray-500">Menunggu Bayar</span>
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-black text-amber-600 mt-2">{{ $metrics['pending_payments'] }}</div>
            <a href="{{ route('admin.payments.index', ['status' => 'pending']) }}" class="text-[11px] font-bold text-amber-600 hover:underline mt-0.5 block">
                Verifikasi sekarang &rarr;
            </a>
        </div>

        <!-- Metric 4: Issued Tickets -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-gray-500">Tiket Terbit</span>
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z" />
                    </svg>
                </div>
            </div>
            <div class="text-2xl font-black text-emerald-600 mt-2">{{ $metrics['issued_tickets'] }}</div>
            <span class="text-[11px] text-gray-400 mt-0.5 block">{{ $metrics['checked_in_tickets'] }} sudah check-in</span>
        </div>

        <!-- Metric 5: Total Revenue -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm col-span-2 lg:col-span-1">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-gray-500">Total Pemasukan</span>
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-purple-50 text-purple-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                </div>
            </div>
            <div class="text-xl font-black text-gray-900 mt-2 truncate">Rp {{ number_format($metrics['total_revenue'], 0, ',', '.') }}</div>
            <span class="text-[11px] text-gray-400 mt-0.5 block">Lunas terverifikasi</span>
        </div>
    </div>

    <!-- Main Two Column Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left: Pending Payments Queue (7 cols) -->
        <div class="lg:col-span-7 space-y-6">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                    <div>
                        <h2 class="text-base font-bold text-gray-900">Pembayaran Perlu Verifikasi</h2>
                        <p class="text-xs text-gray-500">Konfirmasi bukti transfer peserta untuk menerbitkan tiket digital.</p>
                    </div>
                    <a href="{{ route('admin.payments.index') }}" class="text-xs font-bold text-red-600 hover:underline">
                        Lihat Semua
                    </a>
                </div>

                @if ($pendingPayments->isEmpty())
                    <div class="rounded-xl border border-dashed border-gray-200 p-8 text-center text-xs text-gray-500">
                        Tidak ada transaksi pembayaran yang menunggu verifikasi saat ini.
                    </div>
                @else
                    <div class="divide-y divide-gray-100">
                        @foreach ($pendingPayments as $pay)
                            <div class="py-3.5 flex items-center justify-between gap-4">
                                <div>
                                    <span class="font-mono text-[11px] font-bold text-gray-400">{{ $pay->payment_code }}</span>
                                    <h3 class="font-bold text-sm text-gray-900">{{ $pay->user->name }}</h3>
                                    <span class="text-xs text-gray-500 block truncate max-w-xs">{{ $pay->registration->workshop->title }}</span>
                                    <span class="text-xs font-semibold text-red-600">{{ $pay->formatted_amount }} ({{ $pay->payment_method }})</span>
                                </div>

                                <div class="flex items-center gap-2 shrink-0">
                                    @if ($pay->payment_proof)
                                        <a href="{{ $pay->proof_url }}" target="_blank"
                                            class="rounded-lg border border-gray-300 px-2.5 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 transition">
                                            Bukti
                                        </a>
                                    @endif

                                    <form method="POST" action="{{ route('admin.payments.verify', $pay->id) }}">
                                        @csrf
                                        <button type="submit"
                                            class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-bold text-white hover:bg-emerald-700 transition">
                                            Verifikasi
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Recent Registrations (with N+1 Prevention Demonstration) -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-base font-bold text-gray-900">Pendaftaran Peserta Terbaru</h2>
                            <span class="rounded bg-emerald-100 px-1.5 py-0.5 text-[10px] font-bold text-emerald-800">
                                Eager Loaded (No N+1)
                            </span>
                        </div>
                        <p class="text-xs text-gray-500">Query dioptimalkan dengan eager loading relasi User dan Workshop.</p>
                    </div>
                    <a href="{{ route('admin.registrations.index') }}" class="text-xs font-bold text-red-600 hover:underline">
                        Lihat Semua
                    </a>
                </div>

                <div class="divide-y divide-gray-100">
                    @foreach ($recentRegistrations as $reg)
                        <div class="py-3 flex items-center justify-between text-xs">
                            <div>
                                <span class="font-bold text-gray-900">{{ $reg->full_name }}</span>
                                <span class="text-gray-500 block text-[11px]">{{ $reg->workshop->title }}</span>
                                <span class="text-[10px] text-gray-400 font-mono">{{ $reg->registration_code }}</span>
                            </div>
                            <div>
                                @if ($reg->status === 'paid')
                                    <span class="rounded bg-emerald-50 px-2 py-0.5 text-[10px] font-bold text-emerald-700 border border-emerald-200">
                                        Lunas
                                    </span>
                                @else
                                    <span class="rounded bg-amber-50 px-2 py-0.5 text-[10px] font-bold text-amber-700 border border-amber-200">
                                        Pending
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right: Active Workshops Quota Overview (5 cols) -->
        <div class="lg:col-span-5 space-y-6">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                    <h2 class="text-base font-bold text-gray-900">Kapasitas Workshop Aktif</h2>
                    <a href="{{ route('admin.workshops.create') }}" class="text-xs font-bold text-red-600 hover:underline">
                        + Tambah
                    </a>
                </div>

                <div class="space-y-4">
                    @foreach ($activeWorkshops as $ws)
                        <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-3.5 space-y-2">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xs font-bold text-gray-900 truncate max-w-[200px]">{{ $ws->title }}</h3>
                                <span class="text-[10px] font-bold text-gray-500 uppercase">{{ $ws->category }}</span>
                            </div>

                            <div class="flex items-center justify-between text-[11px] text-gray-500">
                                <span>Terisi: {{ $ws->registrations_count }} / {{ $ws->quota }}</span>
                                <span class="font-semibold text-gray-700">Sisa: {{ $ws->remaining_quota }}</span>
                            </div>

                            <div class="h-1.5 w-full overflow-hidden rounded-full bg-gray-200">
                                @php
                                    $pct = min(100, round(($ws->registrations_count / max(1, $ws->quota)) * 100));
                                @endphp
                                <div class="h-full bg-red-600 rounded-full" style="width: {{ $pct }}%"></div>
                            </div>

                            <div class="flex items-center justify-between text-[10px] text-gray-400 pt-1">
                                <span>{{ $ws->formatted_date }}</span>
                                <a href="{{ route('admin.workshops.edit', $ws->id) }}" class="font-bold text-red-600 hover:underline">
                                    Edit Workshop
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
