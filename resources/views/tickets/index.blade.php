@extends('layouts.app')

@section('content')
<div class="py-10 sm:py-14">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <span class="text-xs font-bold uppercase tracking-wider text-red-600">Portal Peserta</span>
            <h1 class="text-3xl font-black tracking-tight text-gray-950 mt-1">Tiket Digital Saya</h1>
            <p class="text-sm text-gray-500 mt-1">Daftar tiket digital resmi workshop IT yang telah terverifikasi dan siap digunakan.</p>
        </div>

        <!-- Pending Payments Alert Banner if any -->
        @if ($pendingRegistrations->isNotEmpty())
            <div class="mb-8 rounded-2xl border border-amber-300 bg-amber-50/70 p-5 shadow-sm">
                <div class="flex items-center gap-2 text-amber-900 font-bold text-sm mb-2">
                    <svg class="h-5 w-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                    <span>Pendaftaran Menunggu Pembayaran ({{ $pendingRegistrations->count() }})</span>
                </div>
                <div class="space-y-2">
                    @foreach ($pendingRegistrations as $pending)
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 rounded-xl bg-white p-3 border border-amber-200 text-xs">
                            <div>
                                <span class="font-bold text-gray-900">{{ $pending->workshop->title }}</span>
                                <span class="text-gray-500 block text-[11px]">{{ $pending->registration_code }} • {{ $pending->workshop->formatted_date }}</span>
                            </div>
                            @if ($pending->payment)
                                <a href="{{ route('payments.show', $pending->payment->payment_code) }}"
                                    class="rounded-lg bg-amber-600 px-3 py-1.5 text-xs font-bold text-white hover:bg-amber-700 transition shrink-0 text-center">
                                    Selesaikan Pembayaran
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Active Tickets Grid -->
        @if ($tickets->isEmpty())
            <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-12 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-gray-100 text-gray-400 mb-4">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-gray-900">Belum Ada Tiket yang Diterbitkan</h3>
                <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto">
                    Tiket digital akan otomatis muncul di sini setelah Anda mendaftar workshop dan pembayaran Anda berhasil diverifikasi oleh panitia.
                </p>
                <div class="mt-5">
                    <a href="{{ route('workshops.index') }}" class="rounded-xl bg-red-600 px-5 py-2.5 text-xs font-bold text-white hover:bg-red-700 transition">
                        Jelajahi Workshop Sekarang
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach ($tickets as $ticket)
                    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm hover:shadow-md transition">
                        <!-- Top Red Header -->
                        <div class="bg-gradient-to-r from-red-600 to-red-800 p-4 text-white flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="rounded bg-white/20 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider">
                                    {{ $ticket->workshop->category }}
                                </span>
                                <span class="text-xs font-medium text-red-100">WORKSHOPTIX PASS</span>
                            </div>
                            <span class="font-mono text-xs font-bold tracking-wider">
                                {{ $ticket->ticket_code }}
                            </span>
                        </div>

                        <!-- Ticket Body -->
                        <div class="p-6 space-y-4">
                            <div>
                                <h3 class="text-base font-bold text-gray-950 leading-snug line-clamp-2">
                                    {{ $ticket->workshop->title }}
                                </h3>
                                <p class="text-xs text-gray-500 mt-1">Pemateri: {{ $ticket->workshop->instructor_name }}</p>
                            </div>

                            <div class="grid grid-cols-2 gap-3 border-t border-gray-100 pt-3 text-xs text-gray-600">
                                <div>
                                    <span class="text-[10px] uppercase font-bold text-gray-400 block">Jadwal</span>
                                    <span class="font-semibold text-gray-900">{{ $ticket->workshop->formatted_date }}</span>
                                    <span class="block text-[11px] text-gray-500">{{ $ticket->workshop->start_time }} WIB</span>
                                </div>

                                <div>
                                    <span class="text-[10px] uppercase font-bold text-gray-400 block">Lokasi</span>
                                    <span class="font-semibold text-gray-900 truncate block">{{ $ticket->workshop->location }}</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between border-t border-gray-100 pt-4">
                                <div>
                                    @if ($ticket->status === 'valid')
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700 border border-emerald-200">
                                            <span class="h-2 w-2 rounded-full bg-emerald-600"></span>
                                            Tiket Valid (Siap Digunakan)
                                        </span>
                                    @elseif ($ticket->status === 'used')
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-2.5 py-1 text-xs font-bold text-gray-700 border border-gray-200">
                                            Sudah Check-In
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-2.5 py-1 text-xs font-bold text-red-700 border border-red-200">
                                            Dibatalkan
                                        </span>
                                    @endif
                                </div>

                                <a href="{{ route('tickets.show', $ticket->ticket_code) }}"
                                    class="rounded-xl bg-gray-950 px-4 py-2 text-xs font-bold text-white hover:bg-red-600 transition">
                                    Buka Tiket
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
