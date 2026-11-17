@extends('layouts.app')

@section('content')
<div class="py-10 sm:py-12">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs text-gray-500 mb-6">
            <a href="{{ route('home') }}" class="hover:text-red-600 transition">Beranda</a>
            <span>/</span>
            <a href="{{ route('workshops.index') }}" class="hover:text-red-600 transition">Katalog Workshop</a>
            <span>/</span>
            <span class="text-gray-900 font-semibold truncate">{{ $workshop->title }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            <!-- Left Main Column (Detail) -->
            <div class="lg:col-span-8 space-y-8">
                <!-- Cover Image -->
                <div class="relative h-72 sm:h-96 w-full overflow-hidden rounded-2xl bg-gray-900">
                    @if ($workshop->image)
                        <img src="{{ $workshop->image }}" alt="{{ $workshop->title }}" class="h-full w-full object-cover">
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-950/80 via-transparent to-transparent"></div>
                    <div class="absolute bottom-6 left-6 right-6">
                        <span class="inline-block rounded-lg bg-red-600 px-3 py-1 text-xs font-bold text-white shadow-sm mb-2">
                            {{ $workshop->category }}
                        </span>
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-white tracking-tight leading-tight">
                            {{ $workshop->title }}
                        </h1>
                    </div>
                </div>

                <!-- Instructor Card -->
                <div class="flex items-center gap-4 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-gray-950 text-white font-bold text-lg">
                        {{ strtoupper(substr($workshop->instructor_name, 0, 1)) }}
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold tracking-wider text-red-600 block">Pemateri Workshop</span>
                        <h2 class="text-base font-bold text-gray-900">{{ $workshop->instructor_name }}</h2>
                        <p class="text-xs text-gray-500">{{ $workshop->instructor_title ?? 'Praktisi IT Profesional' }}</p>
                    </div>
                </div>

                <!-- Schedule & Venue Information Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="rounded-xl border border-gray-200 bg-white p-4">
                        <div class="flex items-center gap-2 text-red-600 mb-1">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5" />
                            </svg>
                            <span class="text-[10px] uppercase font-bold text-gray-500">Tanggal Pelaksanaan</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">{{ $workshop->formatted_date }}</span>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white p-4">
                        <div class="flex items-center gap-2 text-red-600 mb-1">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-[10px] uppercase font-bold text-gray-500">Waktu</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">{{ $workshop->start_time }} – {{ $workshop->end_time }} WIB</span>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white p-4">
                        <div class="flex items-center gap-2 text-red-600 mb-1">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                            </svg>
                            <span class="text-[10px] uppercase font-bold text-gray-500">Format & Lokasi</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900 capitalize">{{ $workshop->location_type }}</span>
                        <span class="block text-xs text-gray-500 truncate">{{ $workshop->location }}</span>
                    </div>
                </div>

                <!-- Description -->
                <div class="rounded-2xl border border-gray-200 bg-white p-6 sm:p-8 shadow-sm space-y-4">
                    <h2 class="text-lg font-bold text-gray-900">Deskripsi Workshop</h2>
                    <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">
                        {{ $workshop->description }}
                    </p>
                </div>

                <!-- Learning Points -->
                @if (!empty($workshop->learning_points_list))
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 sm:p-8 shadow-sm space-y-4">
                        <h2 class="text-lg font-bold text-gray-900">Materi & Hal yang Akan Dipelajari</h2>
                        <div class="grid grid-cols-1 gap-3">
                            @foreach ($workshop->learning_points_list as $point)
                                <div class="flex items-start gap-3 rounded-xl bg-gray-50 p-3.5 border border-gray-100">
                                    <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600 mt-0.5">
                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                        </svg>
                                    </div>
                                    <span class="text-xs sm:text-sm font-medium text-gray-800">{{ $point }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Prerequisites & Target Audience -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                        <h3 class="text-sm font-bold text-gray-900 mb-2">Target Peserta</h3>
                        <p class="text-xs text-gray-600 leading-relaxed">
                            {{ $workshop->target_audience ?? 'Siswa SMK RPL, Mahasiswa Teknologi Informasi, dan Developer.' }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                        <h3 class="text-sm font-bold text-gray-900 mb-2">Persyaratan / Prasyarat</h3>
                        <p class="text-xs text-gray-600 leading-relaxed">
                            {{ $workshop->prerequisites ?? 'Membawa laptop pribadi dan memiliki pemahaman dasar logika pemrograman.' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right Column: Sticky Booking Card -->
            <div class="lg:col-span-4">
                <div class="sticky top-24 rounded-2xl border border-gray-200 bg-white p-6 shadow-md shadow-gray-100 space-y-6">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-gray-400 block tracking-wider">Investasi Pelatihan</span>
                        <div class="text-3xl font-black text-gray-950 mt-0.5">
                            {{ $workshop->formatted_price }}
                        </div>
                    </div>

                    <!-- Quota Progress Card -->
                    <div class="rounded-xl bg-gray-50 p-4 border border-gray-100">
                        <div class="flex items-center justify-between text-xs font-bold text-gray-700 mb-1.5">
                            <span>Ketersediaan Kuota</span>
                            <span class="text-red-600">{{ $workshop->remaining_quota }} Kursi Tersisa</span>
                        </div>
                        <div class="h-2 w-full overflow-hidden rounded-full bg-gray-200">
                            @php
                                $pct = min(100, round(($workshop->registrations_count / max(1, $workshop->quota)) * 100));
                            @endphp
                            <div class="h-full bg-red-600 rounded-full" style="width: {{ $pct }}%"></div>
                        </div>
                        <span class="text-[11px] text-gray-400 mt-1 block">Total kuota: {{ $workshop->quota }} peserta terdaftar</span>
                    </div>

                    <!-- Registration Action Decision -->
                    @if ($existingRegistration)
                        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-xs text-emerald-900">
                            <div class="font-bold mb-1">Status Pendaftaran Anda:</div>
                            @if ($existingRegistration->ticket)
                                <p class="text-emerald-700 mb-3">Anda telah terdaftar dan tiket digital resmi Anda sudah terbit.</p>
                                <a href="{{ route('tickets.show', $existingRegistration->ticket->ticket_code) }}"
                                    class="block w-full text-center rounded-xl bg-emerald-600 py-3 text-xs font-bold text-white shadow-sm hover:bg-emerald-700 transition">
                                    Lihat Tiket Digital QR Code
                                </a>
                            @elseif ($existingRegistration->payment)
                                <p class="text-amber-800 mb-3">Pendaftaran Anda sedang menunggu proses verifikasi pembayaran.</p>
                                <a href="{{ route('payments.show', $existingRegistration->payment->payment_code) }}"
                                    class="block w-full text-center rounded-xl bg-amber-600 py-3 text-xs font-bold text-white shadow-sm hover:bg-amber-700 transition">
                                    Cek Status Pembayaran
                                </a>
                            @endif
                        </div>
                    @else
                        @if ($workshop->status === 'closed')
                            <div class="rounded-xl border border-gray-300 bg-gray-100 p-4 text-center text-xs font-bold text-gray-600">
                                Pendaftaran Ditutup oleh Panitia
                            </div>
                        @elseif ($workshop->is_full)
                            <div class="rounded-xl border border-amber-300 bg-amber-50 p-4 text-center text-xs font-bold text-amber-800">
                                Kuota Workshop Ini Telah Penuh
                            </div>
                        @else
                            @auth
                                <a href="{{ route('registrations.create', $workshop->slug) }}"
                                    class="block w-full text-center rounded-xl bg-red-600 py-3.5 text-sm font-bold text-white shadow-md shadow-red-600/25 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2 transition">
                                    Daftar Workshop Sekarang
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                    class="block w-full text-center rounded-xl bg-red-600 py-3.5 text-sm font-bold text-white shadow-md shadow-red-600/25 hover:bg-red-700 transition">
                                    Masuk untuk Mendaftar
                                </a>
                            @endauth
                        @endif
                    @endif

                    <!-- Benefit points list -->
                    <div class="space-y-2 border-t border-gray-100 pt-4 text-xs text-gray-500">
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            <span>E-Ticket Digital Resmi ber-QR Code</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            <span>Sertifikat Kompetensi Resmi Workshop IT</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            <span>Materi PDF & Repository Source Code</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Workshops -->
        @if ($relatedWorkshops->isNotEmpty())
            <div class="mt-20 border-t border-gray-200 pt-12">
                <h3 class="text-xl font-black text-gray-950 mb-6">Workshop Lain dalam Kategori Ini</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach ($relatedWorkshops as $rel)
                        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm hover:border-red-300 transition">
                            <span class="text-[10px] font-bold text-red-600 uppercase">{{ $rel->category }}</span>
                            <h4 class="text-sm font-bold text-gray-900 mt-1 line-clamp-1">
                                <a href="{{ route('workshops.show', $rel->slug) }}">{{ $rel->title }}</a>
                            </h4>
                            <div class="mt-2 flex items-center justify-between text-xs text-gray-500">
                                <span>{{ $rel->formatted_price }}</span>
                                <a href="{{ route('workshops.show', $rel->slug) }}" class="font-bold text-red-600 hover:underline">Detail</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
