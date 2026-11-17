@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-950">Daftar Tiket & Presensi Gate</h1>
            <p class="text-xs text-gray-500 mt-1">Status tiket digital peserta dan pencatatan check-in kehadiran di venue.</p>
        </div>
        <a href="{{ route('admin.scanner') }}" class="inline-flex items-center gap-2 rounded-xl bg-red-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-red-700 transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5zM13.5 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5z" />
            </svg>
            <span>Buka Scanner Meja Registrasi</span>
        </a>
    </div>

    <!-- Toolbar Filters -->
    <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.tickets.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            <div class="sm:col-span-5">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kode tiket atau nama peserta..."
                    class="w-full rounded-xl border border-gray-300 px-3.5 py-2 text-xs text-gray-900 focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
            </div>

            <div class="sm:col-span-4">
                <select name="workshop_id" onchange="this.form.submit()" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs text-gray-700 focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
                    <option value="">Semua Workshop</option>
                    @foreach ($workshops as $w)
                        <option value="{{ $w->id }}" {{ request('workshop_id') == $w->id ? 'selected' : '' }}>{{ $w->title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-3">
                <select name="status" onchange="this.form.submit()" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs text-gray-700 focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
                    <option value="">Semua Status</option>
                    <option value="valid" {{ request('status') === 'valid' ? 'selected' : '' }}>Valid (Belum Check-In)</option>
                    <option value="used" {{ request('status') === 'used' ? 'selected' : '' }}>Used (Sudah Check-In)</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
            <thead class="bg-gray-50 text-[11px] font-bold uppercase tracking-wider text-gray-500">
                <tr>
                    <th class="px-6 py-3.5">Kode Tiket</th>
                    <th class="px-4 py-3.5">Peserta</th>
                    <th class="px-4 py-3.5">Workshop</th>
                    <th class="px-4 py-3.5">Status Tiket</th>
                    <th class="px-4 py-3.5">Waktu Check-In</th>
                    <th class="px-6 py-3.5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse ($tickets as $tix)
                    <tr class="hover:bg-gray-50/70 transition">
                        <td class="px-6 py-4 whitespace-nowrap font-mono font-bold text-red-600">
                            {{ $tix->ticket_code }}
                        </td>

                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="font-bold text-gray-900 block">{{ $tix->user->name }}</span>
                            <span class="text-[11px] text-gray-500">{{ $tix->registration->institution ?? 'Umum' }}</span>
                        </td>

                        <td class="px-4 py-4">
                            <span class="font-semibold text-gray-900 block max-w-xs truncate">{{ $tix->workshop->title }}</span>
                            <span class="text-[11px] text-gray-500">{{ $tix->workshop->formatted_date }}</span>
                        </td>

                        <td class="px-4 py-4 whitespace-nowrap">
                            @if ($tix->status === 'valid')
                                <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-bold text-emerald-700 border border-emerald-200">
                                    Valid (Siap Check-in)
                                </span>
                            @elseif ($tix->status === 'used')
                                <span class="rounded-full bg-amber-50 px-2.5 py-1 text-[11px] font-bold text-amber-700 border border-amber-200">
                                    Sudah Check-In
                                </span>
                            @else
                                <span class="rounded-full bg-red-50 px-2.5 py-1 text-[11px] font-bold text-red-700 border border-red-200">
                                    Batal
                                </span>
                            @endif
                        </td>

                        <td class="px-4 py-4 whitespace-nowrap text-gray-500">
                            @if ($tix->checked_in_at)
                                <span class="font-bold text-gray-900 block">{{ $tix->checked_in_at->format('d/m/Y H:i') }} WIB</span>
                                <span class="text-[10px] text-gray-400">Oleh: {{ $tix->checkInAdmin->name ?? 'Panitia' }}</span>
                            @else
                                <span class="text-gray-400 italic">Belum hadir</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
                            @if ($tix->status === 'valid')
                                <form method="POST" action="{{ route('admin.tickets.checkIn', $tix->ticket_code) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="rounded-lg bg-red-600 px-3 py-1.5 text-xs font-bold text-white hover:bg-red-700 transition">
                                        Check-In
                                    </button>
                                </form>
                            @endif

                            <a href="{{ route('tickets.show', $tix->ticket_code) }}" target="_blank"
                                class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 transition inline-block">
                                Pratinjau
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-xs text-gray-500">
                            Belum ada data tiket yang terbit.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4 border-t border-gray-100">
            {{ $tickets->links() }}
        </div>
    </div>
</div>
@endsection
