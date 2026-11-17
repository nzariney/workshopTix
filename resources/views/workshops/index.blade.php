@extends('layouts.app')

@section('content')
<div class="py-10 sm:py-14">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <span class="text-xs font-bold uppercase tracking-wider text-red-600">Eksplorasi Keahlian IT</span>
            <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-gray-950 mt-1">Katalog Workshop IT</h1>
            <p class="text-sm text-gray-500 mt-1">Pilih dan ikuti workshop berkualitas tinggi yang dibimbing langsung oleh engineer dan praktisi berpengalaman.</p>
        </div>

        <!-- Search & Filter Controls -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm mb-10">
            <form method="GET" action="{{ route('workshops.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <!-- Search bar -->
                <div class="md:col-span-5 relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul workshop, pemateri, atau lokasi..."
                        class="w-full rounded-xl border border-gray-300 py-2.5 pl-10 pr-4 text-xs sm:text-sm text-gray-900 focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
                </div>

                <!-- Category filter -->
                <div class="md:col-span-3">
                    <select name="category" onchange="this.form.submit()"
                        class="w-full rounded-xl border border-gray-300 py-2.5 px-3 text-xs sm:text-sm text-gray-700 focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status filter -->
                <div class="md:col-span-2">
                    <select name="status" onchange="this.form.submit()"
                        class="w-full rounded-xl border border-gray-300 py-2.5 px-3 text-xs sm:text-sm text-gray-700 focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
                        <option value="">Semua Status</option>
                        <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Tersedia</option>
                        <option value="full" {{ request('status') === 'full' ? 'selected' : '' }}>Penuh</option>
                        <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Ditutup</option>
                    </select>
                </div>

                <!-- Sort -->
                <div class="md:col-span-2 flex gap-2">
                    <select name="sort" onchange="this.form.submit()"
                        class="w-full rounded-xl border border-gray-300 py-2.5 px-3 text-xs sm:text-sm text-gray-700 focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
                        <option value="date_asc" {{ request('sort') === 'date_asc' ? 'selected' : '' }}>Jadwal Terdekat</option>
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                    </select>
                    @if (request()->hasAny(['q', 'category', 'status', 'sort']))
                        <a href="{{ route('workshops.index') }}" title="Reset Filter" class="flex items-center justify-center rounded-xl border border-gray-300 px-3 text-gray-500 hover:bg-gray-100 hover:text-red-600 transition">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    @endif
                </div>
            </form>

            <!-- Quick Category Pills -->
            <div class="mt-4 flex flex-wrap items-center gap-2 border-t border-gray-100 pt-3">
                <span class="text-xs font-semibold text-gray-400 mr-1">Kategori:</span>
                <a href="{{ route('workshops.index') }}"
                    class="rounded-lg px-2.5 py-1 text-xs font-semibold transition {{ !request('category') ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Semua
                </a>
                @foreach ($categories as $cat)
                    <a href="{{ route('workshops.index', array_merge(request()->query(), ['category' => $cat])) }}"
                        class="rounded-lg px-2.5 py-1 text-xs font-semibold transition {{ request('category') === $cat ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Workshops Grid -->
        @if ($workshops->isEmpty())
            <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-12 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-gray-100 text-gray-400 mb-4">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-gray-900">Tidak ada workshop yang ditemukan</h3>
                <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto">Coba ubah kata kunci pencarian atau reset filter untuk melihat semua daftar workshop yang tersedia.</p>
                <div class="mt-5">
                    <a href="{{ route('workshops.index') }}" class="rounded-xl bg-red-600 px-4 py-2.5 text-xs font-bold text-white hover:bg-red-700 transition">
                        Reset Semua Filter
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($workshops as $workshop)
                    <div class="flex flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm hover:shadow-md transition duration-200 group">
                        <!-- Image Container -->
                        <div class="relative h-48 w-full overflow-hidden bg-gray-100">
                            @if ($workshop->image)
                                <img src="{{ $workshop->image }}" alt="{{ $workshop->title }}" class="h-full w-full object-cover group-hover:scale-105 transition duration-300">
                            @else
                                <div class="flex h-full w-full items-center justify-center bg-gray-900 text-white font-bold">
                                    {{ $workshop->category }}
                                </div>
                            @endif
                            <div class="absolute top-3 left-3">
                                <span class="rounded-lg bg-gray-950/80 backdrop-blur-sm px-2.5 py-1 text-xs font-semibold text-white border border-gray-800">
                                    {{ $workshop->category }}
                                </span>
                            </div>
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

                            <div class="mt-3 flex items-center gap-2 text-xs text-gray-600">
                                <svg class="h-4 w-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                                <span class="font-medium text-gray-800">{{ $workshop->instructor_name }}</span>
                            </div>

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

                            <!-- Quota Indicator -->
                            <div class="mt-4">
                                <div class="flex items-center justify-between text-[11px] font-semibold text-gray-500 mb-1">
                                    <span>Sisa: {{ $workshop->remaining_quota }} kursi</span>
                                    <span>{{ $workshop->registrations_count }} / {{ $workshop->quota }}</span>
                                </div>
                                <div class="h-1.5 w-full overflow-hidden rounded-full bg-gray-100">
                                    @php
                                        $pct = min(100, round(($workshop->registrations_count / max(1, $workshop->quota)) * 100));
                                    @endphp
                                    <div class="h-full bg-red-600 rounded-full" style="width: {{ $pct }}%"></div>
                                </div>
                            </div>

                            <div class="mt-6 flex items-center justify-between border-t border-gray-100 pt-4">
                                <div>
                                    <span class="text-[10px] uppercase font-bold text-gray-400 block">Biaya</span>
                                    <span class="text-base font-black text-gray-900">{{ $workshop->formatted_price }}</span>
                                </div>

                                <a href="{{ route('workshops.show', $workshop->slug) }}" class="rounded-xl bg-gray-900 px-4 py-2 text-xs font-bold text-white hover:bg-red-600 transition">
                                    Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-10">
                {{ $workshops->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
