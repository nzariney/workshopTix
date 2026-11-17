@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-white border-b border-gray-200 py-16 sm:py-24">
        <!-- Background subtle accent graphics -->
        <div class="pointer-events-none absolute -top-40 right-0 -z-10 transform-gpu blur-3xl sm:-top-80" aria-hidden="true">
            <div class="aspect-[1155/678] w-[68.4375rem] bg-gradient-to-tr from-red-600/20 to-red-900/10 opacity-60" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
        </div>

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-7 space-y-6">
                    <div class="inline-flex items-center gap-2 rounded-full border border-red-200 bg-red-50/80 px-3.5 py-1 text-xs font-semibold text-red-700 shadow-sm">
                        <span class="h-2 w-2 rounded-full bg-red-600 animate-pulse"></span>
                        Platform Pendaftaran & Tiket Digital Workshop IT Resmi
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-gray-950 leading-[1.1]">
                        Tingkatkan Skill <span class="text-red-600">Teknologi IT</span> Bersama Praktisi Industri.
                    </h1>

                    <p class="max-w-2xl text-base sm:text-lg text-gray-600 leading-relaxed">
                        WORKSHOPTIX menghadirkan pelatihan intensif terkurasi di bidang Rekayasa Perangkat Lunak, Cloud, DevOps, dan Cyber Security. Nikmati kemudahan pendaftaran, pembayaran cepat, dan tiket digital instan ber-QR Code.
                    </p>

                    <div class="flex flex-wrap items-center gap-4 pt-2">
                        <a href="{{ route('workshops.index') }}" class="inline-flex items-center gap-2.5 rounded-xl bg-red-600 px-6 py-3.5 text-sm font-bold text-white shadow-md shadow-red-600/25 hover:bg-red-700 transition duration-150">
                            <span>Jelajahi Workshop IT</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                        <a href="#cara-daftar" class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-5 py-3.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition duration-150">
                            <span>Alur Pendaftaran</span>
                        </a>
                    </div>

                    <!-- Highlight Metrics Strip -->
                    <div class="grid grid-cols-3 gap-6 pt-6 border-t border-gray-200/80">
                        <div>
                            <div class="text-2xl font-black text-gray-950">{{ $stats['workshops_count'] }}+</div>
                            <div class="text-xs font-medium text-gray-500 mt-0.5">Workshop Terjadwal</div>
                        </div>
                        <div>
                            <div class="text-2xl font-black text-red-600">{{ $stats['participants_count'] }}+</div>
                            <div class="text-xs font-medium text-gray-500 mt-0.5">Peserta Terdaftar</div>
                        </div>
                        <div>
                            <div class="text-2xl font-black text-gray-950">100%</div>
                            <div class="text-xs font-medium text-gray-500 mt-0.5">Tiket Digital QR Code</div>
                        </div>
                    </div>
                </div>

                <!-- Right Card / Mockup Graphic -->
                <div class="lg:col-span-5">
                    <div class="relative mx-auto max-w-md rounded-2xl border border-gray-200/80 bg-white p-6 shadow-xl shadow-gray-200/50">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                            <div class="flex items-center gap-2.5">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-600 text-white">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-gray-900">E-TICKET DIGITAL RESMI</div>
                                    <div class="text-[10px] text-gray-500">TIX-WTX-2026-DEMO</div>
                                </div>
                            </div>
                            <span class="rounded bg-emerald-50 px-2 py-0.5 text-[10px] font-bold text-emerald-700 border border-emerald-200">
                                TERVERIFIKASI
                            </span>
                        </div>

                        <!-- Ticket details simulation -->
                        <div class="mt-4 space-y-3 text-xs text-gray-600">
                            <div>
                                <span class="text-gray-400 block text-[10px] uppercase font-bold tracking-wider">Judul Workshop</span>
                                <span class="font-bold text-gray-900 text-sm">Mastering Laravel 12 & Full-Stack</span>
                            </div>
                            <div class="grid grid-cols-2 gap-3 pt-1">
                                <div>
                                    <span class="text-gray-400 block text-[10px] uppercase font-bold tracking-wider">Jadwal</span>
                                    <span class="font-semibold text-gray-800">05 Des 2026 • 08:30 WIB</span>
                                </div>
                                <div>
                                    <span class="text-gray-400 block text-[10px] uppercase font-bold tracking-wider">Lokasi</span>
                                    <span class="font-semibold text-gray-800">Lab RPL Lt. 3 SMK Pesat</span>
                                </div>
                            </div>
                        </div>

                        <!-- Simulated QR Code Card Inside -->
                        <div class="mt-5 rounded-xl border border-dashed border-gray-300 bg-gray-50 p-4 text-center">
                            <div class="mx-auto flex h-32 w-32 items-center justify-center rounded-lg bg-white p-2 border border-gray-200 shadow-sm">
                                <svg class="h-28 w-28 text-gray-900" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <rect x="3" y="3" width="7" height="7" stroke-width="2"/>
                                    <rect x="14" y="3" width="7" height="7" stroke-width="2"/>
                                    <rect x="3" y="14" width="7" height="7" stroke-width="2"/>
                                    <path d="M14 14h3v3h-3z"/>
                                    <path d="M17 17h4v4h-4z"/>
                                    <path d="M14 20h3v1h-3z"/>
                                    <path d="M20 14h1v3h-1z"/>
                                </svg>
                            </div>
                            <span class="mt-2 block text-[11px] font-medium text-gray-500">Scan QR Code saat check-in di lokasi</span>
                        </div>

                        <div class="mt-4 flex items-center justify-between text-[11px] text-gray-400 border-t border-gray-100 pt-3">
                            <span>Sistem Validasi Otomatis</span>
                            <span class="font-mono font-semibold text-red-600">WORKSHOPTIX</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Workshops Section -->
    <section class="py-16 sm:py-20 bg-gray-50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-10">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-red-600">Pilihan Terpopuler</span>
                    <h2 class="text-3xl font-black tracking-tight text-gray-950 mt-1">Workshop Unggulan</h2>
                    <p class="text-sm text-gray-500 mt-1">Pilih kelas teknologi yang sesuai dengan kebutuhan pengembangan karir Anda.</p>
                </div>
                <a href="{{ route('workshops.index') }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-red-600 hover:text-red-700 transition">
                    <span>Lihat Semua ({{ $stats['workshops_count'] }})</span>
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </a>
            </div>

            <!-- Workshop Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($featuredWorkshops as $workshop)
                    <div class="flex flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm hover:shadow-md transition duration-200 group">
                        <!-- Image Container -->
                        <div class="relative h-48 w-full overflow-hidden bg-gray-100">
                            @if ($workshop->image)
                                <img src="{{ $workshop->image }}" alt="{{ $workshop->title }}" class="h-full w-full object-cover group-hover:scale-105 transition duration-300">
                            @else
                                <div class="flex h-full w-full items-center justify-center bg-gray-900 text-white">
                                    <span class="text-sm font-bold">{{ $workshop->category }}</span>
                                </div>
                            @endif
                            <!-- Category Badge -->
                            <div class="absolute top-3 left-3">
                                <span class="rounded-lg bg-gray-950/80 backdrop-blur-sm px-2.5 py-1 text-xs font-semibold text-white border border-gray-800">
                                    {{ $workshop->category }}
                                </span>
                            </div>
                            <!-- Status Badge -->
                            <div class="absolute top-3 right-3">
                                @if ($workshop->status === 'available')
                                    <span class="rounded-lg bg-emerald-600 px-2.5 py-1 text-xs font-bold text-white shadow-sm">
                                        Tersedia
                                    </span>
                                @elseif ($workshop->status === 'full')
                                    <span class="rounded-lg bg-amber-600 px-2.5 py-1 text-xs font-bold text-white shadow-sm">
                                        Penuh
                                    </span>
                                @else
                                    <span class="rounded-lg bg-gray-700 px-2.5 py-1 text-xs font-bold text-white shadow-sm">
                                        Ditutup
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="flex flex-1 flex-col p-6">
                            <h3 class="text-lg font-bold text-gray-950 line-clamp-2 leading-snug group-hover:text-red-600 transition">
                                <a href="{{ route('workshops.show', $workshop->slug) }}">
                                    {{ $workshop->title }}
                                </a>
                            </h3>

                            <!-- Instructor Info -->
                            <div class="mt-3 flex items-center gap-2 text-xs text-gray-600">
                                <svg class="h-4 w-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                                <span class="font-medium text-gray-800">{{ $workshop->instructor_name }}</span>
                            </div>

                            <!-- Schedule & Location -->
                            <div class="mt-4 space-y-1.5 border-t border-gray-100 pt-4 text-xs text-gray-500">
                                <div class="flex items-center gap-2">
                                    <svg class="h-4 w-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5" />
                                    </svg>
                                    <span>{{ $workshop->formatted_date }} • {{ $workshop->start_time }} WIB</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="h-4 w-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                    </svg>
                                    <span class="truncate">{{ $workshop->location }}</span>
                                </div>
                            </div>

                            <!-- Quota Progress Indicator -->
                            <div class="mt-4">
                                <div class="flex items-center justify-between text-[11px] font-semibold text-gray-500 mb-1">
                                    <span>Sisa Kuota: {{ $workshop->remaining_quota }} kursi</span>
                                    <span>{{ $workshop->registered_count }} / {{ $workshop->quota }}</span>
                                </div>
                                <div class="h-1.5 w-full overflow-hidden rounded-full bg-gray-100">
                                    @php
                                        $percentage = min(100, round(($workshop->registered_count / max(1, $workshop->quota)) * 100));
                                    @endphp
                                    <div class="h-full bg-red-600 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>

                            <!-- Footer Price & CTA Button -->
                            <div class="mt-6 flex items-center justify-between border-t border-gray-100 pt-4">
                                <div>
                                    <span class="text-[10px] uppercase font-bold text-gray-400 block">Biaya Registrasi</span>
                                    <span class="text-base font-black text-gray-900">{{ $workshop->formatted_price }}</span>
                                </div>

                                <a href="{{ route('workshops.show', $workshop->slug) }}" class="rounded-xl bg-gray-900 px-4 py-2 text-xs font-bold text-white hover:bg-red-600 transition">
                                    Detail Workshop
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="cara-daftar" class="py-16 sm:py-24 bg-white border-y border-gray-200">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-xs font-bold uppercase tracking-wider text-red-600">Alur Sederhana & Praktis</span>
                <h2 class="text-3xl font-black tracking-tight text-gray-950 mt-1">Bagaimana Cara Mengikuti Workshop?</h2>
                <p class="text-sm text-gray-500 mt-2">Empat langkah mudah mulai dari pemilihan topik hingga tiket siap di-scan saat hari pelatihan.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Step 1 -->
                <div class="relative rounded-2xl border border-gray-200 p-6 bg-gray-50/50">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-600 text-white font-black text-lg shadow-md shadow-red-600/20 mb-4">
                        01
                    </div>
                    <h3 class="text-base font-bold text-gray-900">Pilih Workshop</h3>
                    <p class="text-xs text-gray-500 mt-2 leading-relaxed">
                        Pilih jadwal dan materi workshop IT sesuai kebutuhan kompetensi Anda di katalog workshop resmi.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="relative rounded-2xl border border-gray-200 p-6 bg-gray-50/50">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-900 text-white font-black text-lg mb-4">
                        02
                    </div>
                    <h3 class="text-base font-bold text-gray-900">Isi Formulir</h3>
                    <p class="text-xs text-gray-500 mt-2 leading-relaxed">
                        Lengkapi biodata nama, email, nomor WhatsApp, serta institusi atau sekolah Anda pada sistem pendaftaran.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="relative rounded-2xl border border-gray-200 p-6 bg-gray-50/50">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-900 text-white font-black text-lg mb-4">
                        03
                    </div>
                    <h3 class="text-base font-bold text-gray-900">Bayar & Konfirmasi</h3>
                    <p class="text-xs text-gray-500 mt-2 leading-relaxed">
                        Lakukan pembayaran via transfer bank atau simulasi Sandbox, lalu upload bukti transfer untuk diverifikasi panitia.
                    </p>
                </div>

                <!-- Step 4 -->
                <div class="relative rounded-2xl border border-gray-200 p-6 bg-gray-50/50">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-600 text-white font-black text-lg shadow-md shadow-red-600/20 mb-4">
                        04
                    </div>
                    <h3 class="text-base font-bold text-gray-900">Terima E-Ticket</h3>
                    <p class="text-xs text-gray-500 mt-2 leading-relaxed">
                        Tiket digital ber-QR Code otomatis terbit di menu "Tiket Saya", siap diunduh, dicetak, atau di-scan saat check-in.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="tentang" class="py-16 sm:py-24 bg-gray-50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="space-y-6">
                    <span class="text-xs font-bold uppercase tracking-wider text-red-600">Tentang Platform</span>
                    <h2 class="text-3xl font-black tracking-tight text-gray-950 leading-tight">
                        Ekosistem Digital Pelatihan IT Berstandar Industri
                    </h2>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        WORKSHOPTIX dirancang sebagai sistem terpadu untuk mengakomodasi kebutuhan sertifikasi dan drill kejuruan IT (Rekayasa Perangkat Lunak). Menggabungkan alur pendaftaran anti-duplicate, pemrosesan transaksi yang aman, serta penerbitan digital pass ber-QR Code dengan verifikasi presisi tinggi.
                    </p>
                    <ul class="space-y-3 text-xs text-gray-700">
                        <li class="flex items-center gap-3">
                            <div class="flex h-5 w-5 items-center justify-center rounded-full bg-red-100 text-red-600">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            </div>
                            <span>Otorisasi & Keamanan Role Berjenjang (Panitia & Peserta)</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="flex h-5 w-5 items-center justify-center rounded-full bg-red-100 text-red-600">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            </div>
                            <span>Manajemen Workshop CRUD Terintegrasi Kuota Real-time</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="flex h-5 w-5 items-center justify-center rounded-full bg-red-100 text-red-600">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            </div>
                            <span>Optimalisasi Database Eloquent Eager Loading (Cegah N+1 Problem)</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="flex h-5 w-5 items-center justify-center rounded-full bg-red-100 text-red-600">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            </div>
                            <span>Gate QR Code Scanner untuk Presensi Check-in Cepat di Lokasi Acara</span>
                        </li>
                    </ul>
                </div>

                <!-- Technical Specification Card -->
                <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
                    <h3 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3">Arsitektur Teknis Sistem</h3>
                    <div class="mt-4 grid grid-cols-2 gap-4 text-xs">
                        <div class="rounded-xl bg-gray-50 p-3.5 border border-gray-100">
                            <span class="block text-gray-400 font-semibold uppercase text-[10px]">Backend Framework</span>
                            <span class="font-bold text-gray-900 mt-0.5 block">Laravel 12 / 13 (PHP 8.5)</span>
                        </div>
                        <div class="rounded-xl bg-gray-50 p-3.5 border border-gray-100">
                            <span class="block text-gray-400 font-semibold uppercase text-[10px]">Database Engine</span>
                            <span class="font-bold text-gray-900 mt-0.5 block">MySQL Relational DB</span>
                        </div>
                        <div class="rounded-xl bg-gray-50 p-3.5 border border-gray-100">
                            <span class="block text-gray-400 font-semibold uppercase text-[10px]">Frontend Styling</span>
                            <span class="font-bold text-gray-900 mt-0.5 block">Tailwind CSS v4 & Blade</span>
                        </div>
                        <div class="rounded-xl bg-gray-50 p-3.5 border border-gray-100">
                            <span class="block text-gray-400 font-semibold uppercase text-[10px]">QR Code Library</span>
                            <span class="font-bold text-gray-900 mt-0.5 block">chillerlan/php-qrcode SVG</span>
                        </div>
                    </div>

                    <div class="mt-6 rounded-xl border border-red-200 bg-red-50/60 p-4 text-xs text-red-900">
                        <div class="font-bold">Kurikulum & Standar Kompetensi:</div>
                        <div class="mt-1 text-gray-600">SMK Pesat IT XPRO – Rekayasa Perangkat Lunak (LKPD XI RPL Drill Pra Sertifikasi Kejuruan).</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bottom CTA Banner -->
    <section class="bg-gray-950 py-16 text-white border-t border-gray-800">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center space-y-6">
            <h2 class="text-3xl sm:text-4xl font-black tracking-tight">
                Siap Menjadi Talenta IT Berkompeten?
            </h2>
            <p class="max-w-2xl mx-auto text-sm text-gray-400">
                Pilih topik workshop yang diminati, daftarkan diri sekarang sebelum kuota peserta habis, dan dapatkan sertifikat berharga untuk portofolio Anda.
            </p>
            <div class="pt-2">
                <a href="{{ route('workshops.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-red-600 px-8 py-3.5 text-sm font-bold text-white shadow-lg shadow-red-600/30 hover:bg-red-700 transition">
                    <span>Lihat Daftar Workshop</span>
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </a>
            </div>
        </div>
    </section>
@endsection
