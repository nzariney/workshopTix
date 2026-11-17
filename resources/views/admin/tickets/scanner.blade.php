@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="text-center">
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-red-600 text-white shadow-lg shadow-red-600/30 mb-3">
            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5zM13.5 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5z" />
            </svg>
        </div>
        <h1 class="text-2xl font-black text-gray-950">Gate Scanner & Presensi Kehadiran</h1>
        <p class="text-xs text-gray-500 mt-1">Gunakan scanner barcode / QR Code kamera atau ketikkan kode tiket untuk validasi masuk peserta.</p>
    </div>

    <!-- Scanner Input Box -->
    <div class="rounded-3xl border-2 border-gray-900 bg-white p-8 shadow-xl text-center space-y-6">
        <form method="POST" action="{{ route('admin.scanner.process') }}" class="space-y-4">
            @csrf

            <div>
                <label for="code" class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">
                    Arahkan Scanner / Masukkan Kode Tiket / Scan QR
                </label>
                <div class="relative">
                    <input id="code" type="text" name="code" autofocus required autocomplete="off"
                        class="w-full rounded-2xl border-2 border-red-600 p-4 text-center font-mono text-xl font-black tracking-widest text-gray-900 shadow-inner focus:outline-none focus:ring-4 focus:ring-red-100"
                        placeholder="Contoh: TIX-WTX-2026-XXXX">
                </div>
                <span class="text-[11px] text-gray-400 mt-2 block">
                    Tekan Enter atau klik tombol di bawah untuk validasi langsung.
                </span>
            </div>

            <button type="submit"
                class="w-full rounded-2xl bg-red-600 py-3.5 text-sm font-bold text-white shadow-md shadow-red-600/30 hover:bg-red-700 transition">
                Validasi & Konfirmasi Check-In
            </button>
        </form>

        <!-- Quick Demo Ticket Codes -->
        @php
            $sampleTickets = \App\Models\Ticket::with('user')->latest()->take(3)->get();
        @endphp
        @if ($sampleTickets->isNotEmpty())
            <div class="border-t border-gray-100 pt-4 text-left">
                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block mb-2">Klik untuk Coba Kode Tiket Tersedia:</span>
                <div class="space-y-1.5">
                    @foreach ($sampleTickets as $st)
                        <div class="flex items-center justify-between rounded-xl bg-gray-50 p-2.5 border border-gray-200 text-xs">
                            <div>
                                <span class="font-mono font-bold text-red-600">{{ $st->ticket_code }}</span>
                                <span class="text-gray-500 block text-[11px]">{{ $st->user->name }} ({{ $st->status }})</span>
                            </div>
                            <button type="button"
                                onclick="document.getElementById('code').value = '{{ $st->ticket_code }}'; document.getElementById('code').focus();"
                                class="rounded-lg bg-gray-200 px-2.5 py-1 text-[11px] font-semibold text-gray-700 hover:bg-gray-300 transition">
                                Pakai Kode
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Instructions Card -->
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm text-xs text-gray-600 space-y-2">
        <h3 class="font-bold text-gray-900">SOP Meja Registrasi Acara:</h3>
        <p>1. Peserta menunjukkan tiket digital pada layar smartphone atau cetakan kertas.</p>
        <p>2. Arahkan barcode scanner fisik atau masukkan 10 digit kode tiket.</p>
        <p>3. Jika sistem menampilkan warna hijau "Check-In Berhasil", serahkan name tag dan perlengkapan workshop kepada peserta.</p>
        <p>4. Jika tiket sudah pernah di-scan sebelumnya, sistem akan menolak dan menampilkan jam check-in sebelumnya.</p>
    </div>
</div>
@endsection
