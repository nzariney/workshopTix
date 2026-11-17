@extends('layouts.app')

@section('content')
<div class="py-10 sm:py-14">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        <!-- Top Action Bar -->
        <div class="no-print mb-8 flex flex-wrap items-center justify-between gap-4">
            <a href="{{ route('tickets.myTickets') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-red-600 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                <span>Kembali ke Tiket Saya</span>
            </a>

            <div class="flex items-center gap-2">
                <button onclick="window.print()" class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2 text-xs font-bold text-gray-700 hover:bg-gray-50 transition shadow-sm">
                    <svg class="h-4 w-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.056-.867-1.92-1.72-2.329m14 0a4.5 4.5 0 00-1.72 2.329m-10.56 0A4.5 4.5 0 014.5 12c0-2.485 2.015-4.5 4.5-4.5h6c2.485 0 4.5 2.015 4.5 4.5 0 .668-.145 1.298-.406 1.829m-14.188 0h14.188m-14.188 0A4.5 4.5 0 004.5 16.5v1.875c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V16.5a4.5 4.5 0 00-1.906-2.671" />
                    </svg>
                    <span>Cetak Tiket</span>
                </button>

                <a href="{{ route('tickets.print', $ticket->ticket_code) }}" target="_blank" class="inline-flex items-center gap-2 rounded-xl bg-red-600 px-4 py-2 text-xs font-bold text-white hover:bg-red-700 transition shadow-sm">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                    </svg>
                    <span>Tampilan Cetak Khusus</span>
                </a>
            </div>
        </div>

        <!-- Digital Boarding Pass Ticket Container -->
        <div class="relative overflow-hidden rounded-3xl border-2 border-gray-900 bg-white shadow-xl shadow-gray-200/50">
            <!-- Top Gradient Bar -->
            <div class="bg-gradient-to-r from-red-600 via-red-700 to-gray-950 px-8 py-5 text-white flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-red-600 font-black shadow-sm">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z" />
                        </svg>
                    </div>
                    <div>
                        <span class="text-lg font-black tracking-wider uppercase">WORKSHOPTIX PASS</span>
                        <span class="block text-[11px] text-red-200 font-medium">Digital Workshop IT Registration & Certification</span>
                    </div>
                </div>

                <div class="text-right">
                    <span class="text-[10px] uppercase font-bold text-red-200 block">Status Validasi</span>
                    @if ($ticket->status === 'valid')
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/20 px-3 py-1 text-xs font-bold text-white border border-emerald-400/30">
                            <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                            VALID & AKTIF
                        </span>
                    @elseif ($ticket->status === 'used')
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-500/20 px-3 py-1 text-xs font-bold text-amber-200 border border-amber-400/30">
                            SUDAH DIGUNAKAN (CHECKED-IN)
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-red-500/20 px-3 py-1 text-xs font-bold text-red-200 border border-red-400/30">
                            TIDAK BERLAKU
                        </span>
                    @endif
                </div>
            </div>

            <!-- Perforated Ticket Body (Two Column) -->
            <div class="grid grid-cols-1 md:grid-cols-12 divide-y md:divide-y-0 md:divide-x-2 md:divide-dashed divide-gray-200">
                <!-- Left 8 Cols: Workshop & Attendee Details -->
                <div class="md:col-span-8 p-8 space-y-6">
                    <div>
                        <span class="inline-block rounded-md bg-red-50 px-2.5 py-1 text-[11px] font-bold text-red-700 border border-red-200">
                            {{ $ticket->workshop->category }}
                        </span>
                        <h2 class="text-2xl font-black text-gray-950 mt-2 leading-tight">
                            {{ $ticket->workshop->title }}
                        </h2>
                        <p class="text-xs text-gray-500 mt-1">Pemateri: <span class="font-bold text-gray-800">{{ $ticket->workshop->instructor_name }}</span> ({{ $ticket->workshop->instructor_title }})</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 border-t border-gray-100 pt-4 text-xs text-gray-600">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-gray-400 block">Nama Peserta</span>
                            <span class="font-bold text-gray-950 text-sm block">{{ $ticket->registration->full_name }}</span>
                            <span class="text-gray-500 text-[11px]">{{ $ticket->registration->institution ?? 'Peserta Umum' }}</span>
                        </div>

                        <div>
                            <span class="text-[10px] uppercase font-bold text-gray-400 block">Kode Registrasi</span>
                            <span class="font-mono font-bold text-gray-900 text-sm block">{{ $ticket->registration->registration_code }}</span>
                            <span class="text-gray-500 text-[11px]">{{ $ticket->registration->email }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 border-t border-gray-100 pt-4 text-xs text-gray-600">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-gray-400 block">Tanggal & Jam</span>
                            <span class="font-bold text-gray-950 block">{{ $ticket->workshop->formatted_date }}</span>
                            <span class="text-gray-500 text-[11px]">{{ $ticket->workshop->start_time }} – {{ $ticket->workshop->end_time }} WIB</span>
                        </div>

                        <div>
                            <span class="text-[10px] uppercase font-bold text-gray-400 block">Lokasi Pelaksanaan</span>
                            <span class="font-bold text-gray-950 block">{{ $ticket->workshop->location_type === 'online' ? 'Online Streaming' : 'Tatap Muka (Offline)' }}</span>
                            <span class="text-gray-500 text-[11px] truncate block">{{ $ticket->workshop->location }}</span>
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div class="rounded-xl bg-gray-50 p-4 border border-gray-200 text-xs text-gray-600 space-y-1">
                        <span class="font-bold text-gray-900 block">Petunjuk Masuk & Verifikasi:</span>
                        <p class="text-[11px]">1. Tunjukkan QR Code pada tiket ini ke panitia di meja registrasi saat tiba di lokasi acara.</p>
                        <p class="text-[11px]">2. Setiap QR Code hanya dapat di-scan 1 kali untuk presensi kehadiran.</p>
                    </div>
                </div>

                <!-- Right 4 Cols: QR Code Stub -->
                <div class="md:col-span-4 p-8 flex flex-col items-center justify-between bg-gray-50/50 text-center space-y-4">
                    <div>
                        <span class="text-[10px] uppercase font-bold tracking-wider text-gray-400 block">KODE TIKET RESMI</span>
                        <span class="font-mono font-black text-sm text-red-600 block mt-0.5 tracking-wider">{{ $ticket->ticket_code }}</span>
                    </div>

                    <!-- High Quality SVG QR Code -->
                    <div class="rounded-2xl border-2 border-gray-900 bg-white p-3 shadow-md">
                        <img src="{{ $ticket->qr_code_base64 }}" alt="QR Code Tiket {{ $ticket->ticket_code }}" class="h-44 w-44 object-contain">
                    </div>

                    <div class="text-[11px] text-gray-500">
                        <span class="font-semibold text-gray-800 block">Scan untuk Validasi</span>
                        <span class="text-[10px] text-gray-400">Terhubung dengan sistem verifikasi panitia</span>
                    </div>

                    <div class="no-print pt-2 w-full">
                        <a href="{{ route('tickets.verify', $ticket->ticket_code) }}" target="_blank"
                            class="block w-full rounded-xl border border-gray-300 bg-white py-2 text-center text-xs font-bold text-gray-700 hover:bg-gray-100 transition shadow-sm">
                            Cek URL Verifikasi
                        </a>
                    </div>
                </div>
            </div>

            <!-- Bottom Notches & Barcode Simulation -->
            <div class="bg-gray-950 px-8 py-3 text-white flex items-center justify-between text-xs font-mono">
                <span class="text-gray-400">SMK PESAT IT XPRO • REKAYASA PERANGKAT LUNAK</span>
                <span class="text-red-400 font-bold">WORKSHOPTIX VALIDATED</span>
            </div>
        </div>
    </div>
</div>
@endsection
