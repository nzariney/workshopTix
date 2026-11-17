<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'WORKSHOPTIX' }} – Digital Workshop Registration & Ticketing System</title>
    <meta name="description" content="Platform pendaftaran dan tiket digital workshop IT resmi. Tingkatkan keahlian rekayasa perangkat lunak dan teknologi digital Anda.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-full flex-col bg-gray-50 font-sans text-gray-900 antialiased selection:bg-red-600 selection:text-white">

    <!-- Top Notice / Quick Demo Banner for Grading -->
    <div class="no-print border-b border-red-900 bg-gray-950 px-4 py-2 text-xs text-gray-300">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-2">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center rounded bg-red-600/30 px-2 py-0.5 text-[11px] font-semibold text-red-400 border border-red-600/50">
                    LKPD XI RPL
                </span>
                <span class="text-gray-400">Drill Pra Sertifikasi SMK Pesat IT XPRO – Workshop IT & Tiket Digital</span>
            </div>
            
            <div class="flex items-center gap-3">
                @guest
                    <span class="text-gray-400 hidden sm:inline">Uji Akses Cepat:</span>
                    <form method="POST" action="{{ route('quick.login') }}" class="inline">
                        @csrf
                        <input type="hidden" name="role" value="admin">
                        <button type="submit" class="rounded bg-red-700 hover:bg-red-600 px-2.5 py-1 text-[11px] font-medium text-white transition">
                            Login Panitia (Admin)
                        </button>
                    </form>
                    <form method="POST" action="{{ route('quick.login') }}" class="inline">
                        @csrf
                        <input type="hidden" name="role" value="participant">
                        <button type="submit" class="rounded bg-gray-800 hover:bg-gray-700 px-2.5 py-1 text-[11px] font-medium text-gray-200 transition">
                            Login Peserta Demo
                        </button>
                    </form>
                @else
                    <div class="flex items-center gap-2">
                        <span class="text-gray-400">Login sebagai:</span>
                        <span class="font-semibold text-white">{{ Auth::user()->name }}</span>
                        <span class="rounded bg-red-900/60 px-2 py-0.5 text-[10px] uppercase font-bold text-red-300 border border-red-700/50">
                            {{ Auth::user()->role === 'admin' ? 'Panitia / Admin' : 'Peserta' }}
                        </span>
                        @if (Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="ml-2 font-medium text-red-400 hover:text-red-300 underline">
                                Buka Dashboard Panitia
                            </a>
                        @endif
                    </div>
                @endguest
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <header class="no-print sticky top-0 z-40 border-b border-gray-200/80 bg-white/95 backdrop-blur-md">
        <div class="mx-auto flex h-18 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-red-600 to-red-800 text-white shadow-md shadow-red-600/20 group-hover:scale-105 transition duration-200">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z" />
                    </svg>
                </div>
                <div>
                    <span class="text-xl font-black tracking-tight text-gray-950">
                        WORKSHOP<span class="text-red-600">TIX</span>
                    </span>
                    <span class="block text-[10px] font-semibold uppercase tracking-wider text-gray-500">
                        Digital Workshop IT
                    </span>
                </div>
            </a>

            <!-- Desktop Navigation Links -->
            <nav class="hidden md:flex items-center gap-8">
                <a href="{{ route('home') }}" class="text-sm font-semibold {{ request()->routeIs('home') ? 'text-red-600' : 'text-gray-700 hover:text-red-600' }} transition">
                    Beranda
                </a>
                <a href="{{ route('workshops.index') }}" class="text-sm font-semibold {{ request()->routeIs('workshops.*') ? 'text-red-600' : 'text-gray-700 hover:text-red-600' }} transition">
                    Katalog Workshop
                </a>
                <a href="{{ route('home') }}#tentang" class="text-sm font-semibold text-gray-700 hover:text-red-600 transition">
                    Tentang
                </a>
                <a href="{{ route('home') }}#cara-daftar" class="text-sm font-semibold text-gray-700 hover:text-red-600 transition">
                    Alur Pendaftaran
                </a>
                @auth
                    <a href="{{ route('tickets.myTickets') }}" class="relative text-sm font-semibold {{ request()->routeIs('tickets.*') ? 'text-red-600' : 'text-gray-700 hover:text-red-600' }} transition flex items-center gap-1.5">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z" />
                        </svg>
                        <span>Tiket Saya</span>
                    </a>
                @endauth
            </nav>

            <!-- User Action Menu -->
            <div class="hidden md:flex items-center gap-3">
                @guest
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-700 hover:text-red-600 px-3 py-2 transition">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-red-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 transition">
                        Daftar Akun
                    </a>
                @else
                    @if (Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 rounded-xl border border-red-200 bg-red-50 px-3.5 py-2 text-xs font-bold text-red-700 hover:bg-red-100 transition">
                            <svg class="h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                            </svg>
                            <span>Panel Panitia</span>
                        </a>
                    @endif

                    <div class="relative flex items-center gap-3 border-l border-gray-200 pl-4">
                        <div class="text-right">
                            <div class="text-xs font-semibold text-gray-900 leading-tight">{{ Auth::user()->name }}</div>
                            <div class="text-[11px] text-gray-500">{{ Auth::user()->email }}</div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" title="Keluar" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-red-600 transition">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                                </svg>
                            </button>
                        </form>
                    </div>
                @endguest
            </div>

            <!-- Mobile Hamburger Toggle -->
            <div class="flex md:hidden">
                <button type="button" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" class="rounded-lg p-2 text-gray-700 hover:bg-gray-100">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-gray-200 bg-white px-4 pt-3 pb-6 space-y-3">
            <a href="{{ route('home') }}" class="block rounded-lg px-3 py-2 text-base font-medium text-gray-800 hover:bg-gray-50">Beranda</a>
            <a href="{{ route('workshops.index') }}" class="block rounded-lg px-3 py-2 text-base font-medium text-gray-800 hover:bg-gray-50">Katalog Workshop</a>
            <a href="{{ route('home') }}#tentang" class="block rounded-lg px-3 py-2 text-base font-medium text-gray-800 hover:bg-gray-50">Tentang</a>
            <a href="{{ route('home') }}#cara-daftar" class="block rounded-lg px-3 py-2 text-base font-medium text-gray-800 hover:bg-gray-50">Alur Pendaftaran</a>
            @auth
                <a href="{{ route('tickets.myTickets') }}" class="block rounded-lg px-3 py-2 text-base font-medium text-red-600 bg-red-50">Tiket Saya</a>
                @if (Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="block rounded-lg px-3 py-2 text-base font-medium text-gray-900 bg-gray-100">Panel Panitia</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="pt-2">
                    @csrf
                    <button type="submit" class="w-full text-left rounded-lg px-3 py-2 text-base font-medium text-red-600 hover:bg-red-50">Keluar</button>
                </form>
            @else
                <div class="grid grid-cols-2 gap-2 pt-2">
                    <a href="{{ route('login') }}" class="rounded-xl border border-gray-300 py-2.5 text-center text-sm font-semibold text-gray-700">Masuk</a>
                    <a href="{{ route('register') }}" class="rounded-xl bg-red-600 py-2.5 text-center text-sm font-semibold text-white">Daftar Akun</a>
                </div>
            @endauth
        </div>
    </header>

    <!-- Main Content Body -->
    <main class="flex-1">
        <div class="no-print mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pt-6">
            @include('components.alerts')
        </div>
        
        @yield('content')
    </main>

    <!-- Global Footer -->
    <footer class="no-print mt-20 border-t border-gray-200 bg-white text-gray-600">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-4">
                <!-- Col 1: Brand Info -->
                <div class="md:col-span-2 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-red-600 text-white">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold tracking-tight text-gray-900">
                            WORKSHOP<span class="text-red-600">TIX</span>
                        </span>
                    </div>
                    <p class="max-w-md text-sm text-gray-500 leading-relaxed">
                        Sistem pendaftaran dan penerbitan tiket digital terintegrasi untuk workshop teknologi informasi. Mendukung verifikasi pembayaran otomatis dan validasi QR Code presisi tinggi.
                    </p>
                    <div class="flex items-center gap-2 text-xs font-semibold text-gray-400">
                        <span>SMK Pesat IT XPRO</span>
                        <span>•</span>
                        <span>Rekayasa Perangkat Lunak</span>
                        <span>•</span>
                        <span>Drill Pra Sertifikasi</span>
                    </div>
                </div>

                <!-- Col 2: Navigation Links -->
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-900">Navigasi Utama</h3>
                    <ul class="mt-4 space-y-2.5 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-red-600 transition">Beranda</a></li>
                        <li><a href="{{ route('workshops.index') }}" class="hover:text-red-600 transition">Katalog Workshop</a></li>
                        <li><a href="{{ route('home') }}#tentang" class="hover:text-red-600 transition">Tentang Kami</a></li>
                        <li><a href="{{ route('home') }}#cara-daftar" class="hover:text-red-600 transition">Panduan Pendaftaran</a></li>
                    </ul>
                </div>

                <!-- Col 3: Portal & Verifikasi -->
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-900">Portal & Validasi</h3>
                    <ul class="mt-4 space-y-2.5 text-sm">
                        <li><a href="{{ route('tickets.myTickets') }}" class="hover:text-red-600 transition">Tiket Peserta</a></li>
                        <li><a href="{{ route('admin.dashboard') }}" class="hover:text-red-600 transition">Portal Panitia (Admin)</a></li>
                        <li><a href="{{ route('admin.scanner') }}" class="hover:text-red-600 transition">Gate QR Scanner</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-red-600 transition">Masuk Akun</a></li>
                    </ul>
                </div>
            </div>

            <div class="mt-12 flex flex-col md:flex-row items-center justify-between border-t border-gray-100 pt-8 text-xs text-gray-400">
                <p>&copy; {{ date('Y') }} WORKSHOPTIX – Digital Workshop Registration & Ticketing System. All rights reserved.</p>
                <div class="mt-4 md:mt-0 flex gap-6">
                    <span class="text-gray-400">Laravel 12 / 13 • Tailwind CSS v4 • MySQL • QR Code SVG</span>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
