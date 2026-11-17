@extends('layouts.app')

@section('content')
<div class="py-12 sm:py-16">
    <div class="mx-auto max-w-xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl border border-gray-200 bg-white p-8 shadow-lg shadow-gray-200/50">
            <!-- Top Icon & Header -->
            <div class="text-center pb-6 border-b border-gray-100">
                @if ($ticket && $ticket->status === 'valid')
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-600 mb-4 shadow-sm">
                        <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-800 uppercase tracking-wider">
                        TIKET RESMI & VALID
                    </span>
                    <h1 class="text-2xl font-black text-gray-950 mt-2">Verifikasi Tiket Berhasil</h1>
                    <p class="text-xs text-gray-500 mt-1">Tiket digital ini terdaftar sah dalam sistem database WORKSHOPTIX.</p>
                @elseif ($ticket && $ticket->status === 'used')
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-amber-100 text-amber-600 mb-4 shadow-sm">
                        <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-800 uppercase tracking-wider">
                        TIKET SUDAH DIGUNAKAN
                    </span>
                    <h1 class="text-2xl font-black text-gray-950 mt-2">Peserta Sudah Check-in</h1>
                    <p class="text-xs text-gray-500 mt-1">
                        Tiket ini telah divalidasi sebelumnya pada {{ $ticket->checked_in_at?->translatedFormat('d F Y H:i:s') ?? '-' }} WIB.
                    </p>
                @else
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-red-100 text-red-600 mb-4 shadow-sm">
                        <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-bold text-red-800 uppercase tracking-wider">
                        TIKET TIDAK VALID
                    </span>
                    <h1 class="text-2xl font-black text-gray-950 mt-2">Tiket Tidak Ditemukan</h1>
                    <p class="text-xs text-gray-500 mt-1">Kode token tiket tidak sesuai atau tiket telah dibatalkan.</p>
                @endif
            </div>

            <!-- Details Section -->
            @if ($ticket)
                <div class="py-6 space-y-4 text-xs text-gray-600">
                    <div class="rounded-xl bg-gray-50 p-4 border border-gray-100 space-y-2">
                        <div class="flex items-center justify-between border-b border-gray-200/60 pb-2">
                            <span class="text-gray-400 font-semibold uppercase text-[10px]">Kode Tiket</span>
                            <span class="font-mono font-bold text-gray-900">{{ $ticket->ticket_code }}</span>
                        </div>
                        <div class="flex items-center justify-between border-b border-gray-200/60 pb-2">
                            <span class="text-gray-400 font-semibold uppercase text-[10px]">Nama Peserta</span>
                            <span class="font-bold text-gray-900">{{ $ticket->registration->full_name }}</span>
                        </div>
                        <div class="flex items-center justify-between border-b border-gray-200/60 pb-2">
                            <span class="text-gray-400 font-semibold uppercase text-[10px]">Instansi / Sekolah</span>
                            <span class="font-medium text-gray-800">{{ $ticket->registration->institution ?? '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between border-b border-gray-200/60 pb-2">
                            <span class="text-gray-400 font-semibold uppercase text-[10px]">Workshop</span>
                            <span class="font-bold text-gray-900 text-right max-w-xs">{{ $ticket->workshop->title }}</span>
                        </div>
                        <div class="flex items-center justify-between border-b border-gray-200/60 pb-2">
                            <span class="text-gray-400 font-semibold uppercase text-[10px]">Jadwal Pelaksanaan</span>
                            <span class="font-medium text-gray-800">{{ $ticket->workshop->formatted_date }} ({{ $ticket->workshop->start_time }} WIB)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400 font-semibold uppercase text-[10px]">Lokasi</span>
                            <span class="font-medium text-gray-800 truncate max-w-xs">{{ $ticket->workshop->location }}</span>
                        </div>
                    </div>

                    <!-- Admin Check-In Action Button -->
                    @auth
                        @if (Auth::user()->isAdmin() && $ticket->status === 'valid')
                            <div class="pt-2">
                                <form method="POST" action="{{ route('admin.tickets.checkIn', $ticket->ticket_code) }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full rounded-xl bg-red-600 py-3 text-xs font-bold text-white shadow-md shadow-red-600/20 hover:bg-red-700 transition">
                                        Konfirmasi Check-In Peserta di Lokasi Acara
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>
            @else
                <div class="py-6 text-center text-xs text-gray-500">
                    Pastikan barcode atau QR Code yang Anda scan berasal dari aplikasi resmi WORKSHOPTIX.
                </div>
            @endif

            <!-- Back to Home -->
            <div class="border-t border-gray-100 pt-6 text-center">
                <a href="{{ route('home') }}" class="text-xs font-bold text-red-600 hover:text-red-700 transition">
                    Kembali ke Halaman Utama
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
