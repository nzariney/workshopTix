@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-950">Manajemen Workshop IT</h1>
            <p class="text-xs text-gray-500 mt-1">Daftar kelas pelatihan, kuota peserta, dan jadwal pelaksanaan.</p>
        </div>
        <a href="{{ route('admin.workshops.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-red-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-red-700 transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            <span>Tambah Workshop Baru</span>
        </a>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.workshops.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            <div class="sm:col-span-6">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul workshop atau instruktur..."
                    class="w-full rounded-xl border border-gray-300 px-3.5 py-2 text-xs text-gray-900 focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
            </div>

            <div class="sm:col-span-3">
                <select name="category" onchange="this.form.submit()" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs text-gray-700 focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-3">
                <select name="status" onchange="this.form.submit()" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs text-gray-700 focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
                    <option value="">Semua Status</option>
                    <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Available (Tersedia)</option>
                    <option value="full" {{ request('status') === 'full' ? 'selected' : '' }}>Full (Penuh)</option>
                    <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed (Ditutup)</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Workshops Table -->
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
            <thead class="bg-gray-50 text-[11px] font-bold uppercase tracking-wider text-gray-500">
                <tr>
                    <th class="px-6 py-3.5">Workshop</th>
                    <th class="px-4 py-3.5">Kategori</th>
                    <th class="px-4 py-3.5">Jadwal & Tempat</th>
                    <th class="px-4 py-3.5">Biaya</th>
                    <th class="px-4 py-3.5">Kuota</th>
                    <th class="px-4 py-3.5">Status</th>
                    <th class="px-6 py-3.5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse ($workshops as $ws)
                    <tr class="hover:bg-gray-50/70 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if ($ws->image)
                                    <img src="{{ $ws->image }}" alt="" class="h-10 w-10 shrink-0 rounded-lg object-cover">
                                @else
                                    <div class="h-10 w-10 shrink-0 rounded-lg bg-gray-900 text-white flex items-center justify-center font-bold text-xs">
                                        IT
                                    </div>
                                @endif
                                <div>
                                    <a href="{{ route('workshops.show', $ws->slug) }}" target="_blank" class="font-bold text-gray-950 hover:text-red-600 transition block">
                                        {{ $ws->title }}
                                    </a>
                                    <span class="text-[11px] text-gray-500">{{ $ws->instructor_name }}</span>
                                </div>
                            </div>
                        </td>

                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="rounded bg-gray-100 px-2 py-0.5 text-[11px] font-medium text-gray-700">
                                {{ $ws->category }}
                            </span>
                        </td>

                        <td class="px-4 py-4 whitespace-nowrap text-gray-600">
                            <span class="font-semibold text-gray-900 block">{{ $ws->formatted_date }}</span>
                            <span class="text-[11px] text-gray-500">{{ $ws->start_time }} WIB • {{ $ws->location_type }}</span>
                        </td>

                        <td class="px-4 py-4 whitespace-nowrap font-bold text-gray-900">
                            {{ $ws->formatted_price }}
                        </td>

                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="font-bold {{ $ws->remaining_quota <= 5 ? 'text-red-600' : 'text-gray-900' }}">
                                {{ $ws->registrations_count }} / {{ $ws->quota }}
                            </span>
                            <span class="text-[10px] text-gray-400 block">Sisa {{ $ws->remaining_quota }}</span>
                        </td>

                        <td class="px-4 py-4 whitespace-nowrap">
                            @if ($ws->status === 'available')
                                <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-bold text-emerald-700 border border-emerald-200">
                                    Available
                                </span>
                            @elseif ($ws->status === 'full')
                                <span class="rounded-full bg-amber-50 px-2.5 py-1 text-[11px] font-bold text-amber-700 border border-amber-200">
                                    Full
                                </span>
                            @else
                                <span class="rounded-full bg-gray-100 px-2.5 py-1 text-[11px] font-bold text-gray-700 border border-gray-200">
                                    Closed
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
                            <a href="{{ route('admin.workshops.edit', $ws->id) }}" class="font-bold text-red-600 hover:text-red-800">
                                Edit
                            </a>

                            <form method="POST" action="{{ route('admin.workshops.toggleStatus', $ws->id) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="font-medium text-gray-600 hover:text-gray-900">
                                    {{ $ws->status === 'available' ? 'Tutup' : 'Buka' }}
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.workshops.destroy', $ws->id) }}" class="inline" onsubmit="return confirm('Hapus workshop ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-medium text-red-600 hover:text-red-800">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-xs text-gray-500">
                            Tidak ada workshop yang cocok dengan kriteria pencarian.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4 border-t border-gray-100">
            {{ $workshops->links() }}
        </div>
    </div>
</div>
@endsection
