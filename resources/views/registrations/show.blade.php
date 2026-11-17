@extends('layouts.app')

@section('content')
<div class="py-10 sm:py-14">
    <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl border border-gray-200 bg-white p-8 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                <div>
                    <span class="text-[10px] uppercase font-bold text-gray-400 block tracking-wider">Tanda Bukti Pendaftaran</span>
                    <h1 class="text-xl font-black text-gray-950 mt-0.5">{{ $registration->registration_code }}</h1>
                </div>

                <div>
                    @if ($registration->status === 'paid')
                        <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700 border border-emerald-200">
                            Lunas & Terdaftar
                        </span>
                    @else
                        <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700 border border-amber-200">
                            Menunggu Pembayaran
                        </span>
                    @endif
                </div>
            </div>

            <!-- Details -->
            <div class="space-y-4 text-xs text-gray-600">
                <div class="rounded-xl bg-gray-50 p-4 border border-gray-100 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400 font-bold uppercase text-[10px]">Workshop</span>
                        <span class="font-bold text-gray-900 text-right">{{ $registration->workshop->title }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400 font-bold uppercase text-[10px]">Jadwal</span>
                        <span class="font-semibold text-gray-800">{{ $registration->workshop->formatted_date }} ({{ $registration->workshop->start_time }} WIB)</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400 font-bold uppercase text-[10px]">Lokasi</span>
                        <span class="font-semibold text-gray-800 truncate max-w-xs">{{ $registration->workshop->location }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400 font-bold uppercase text-[10px]">Peserta</span>
                        <span class="font-semibold text-gray-800">{{ $registration->full_name }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400 font-bold uppercase text-[10px]">Email</span>
                        <span class="font-semibold text-gray-800">{{ $registration->email }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400 font-bold uppercase text-[10px]">Nomor WhatsApp</span>
                        <span class="font-semibold text-gray-800">{{ $registration->phone }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400 font-bold uppercase text-[10px]">Instansi</span>
                        <span class="font-semibold text-gray-800">{{ $registration->institution ?? '-' }}</span>
                    </div>
                </div>

                <div class="pt-2 flex flex-col sm:flex-row gap-3">
                    @if ($registration->ticket)
                        <a href="{{ route('tickets.show', $registration->ticket->ticket_code) }}"
                            class="flex-1 rounded-xl bg-emerald-600 py-3 text-center text-xs font-bold text-white hover:bg-emerald-700 transition">
                            Lihat Tiket Digital QR Code
                        </a>
                    @elseif ($registration->payment)
                        <a href="{{ route('payments.show', $registration->payment->payment_code) }}"
                            class="flex-1 rounded-xl bg-red-600 py-3 text-center text-xs font-bold text-white hover:bg-red-700 transition">
                            Lanjut ke Halaman Pembayaran
                        </a>
                    @endif

                    <a href="{{ route('workshops.index') }}"
                        class="rounded-xl border border-gray-300 px-5 py-3 text-center text-xs font-semibold text-gray-700 hover:bg-gray-100 transition">
                        Kembali ke Katalog
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
