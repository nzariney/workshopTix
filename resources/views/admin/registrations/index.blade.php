@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-950">Daftar Pendaftar Workshop</h1>
            <p class="text-xs text-gray-500 mt-1">Data kepesertaan, status pembayaran, dan penerbitan tiket.</p>
        </div>
    </div>

    <!-- N+1 Query Eager Loading Explanation & Comparison Banner (LKPD Step 05) -->
    <div class="rounded-2xl border border-emerald-200 bg-emerald-50/60 p-5 shadow-sm space-y-3">
        <div class="flex items-center gap-2">
            <span class="rounded bg-emerald-600 px-2.5 py-0.5 text-[10px] font-bold text-white uppercase tracking-wider">
                LKPD Step 05 • Optimasi Database
            </span>
            <span class="text-xs font-bold text-emerald-950">Pencegahan Masalah N+1 Query Berhasil Diterapkan</span>
        </div>
        <p class="text-xs text-emerald-900 leading-relaxed">
            Pada halaman ini, query telah dioptimasi menggunakan Eager Loading:
            <code class="rounded bg-white/80 px-2 py-0.5 font-mono text-[11px] text-emerald-800 border border-emerald-300">
                Registration::with(['user', 'workshop', 'payment', 'ticket'])
            </code>.
        </p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs pt-1">
            <div class="rounded-xl border border-red-200 bg-white p-3">
                <span class="font-bold text-red-700 block text-[11px] uppercase">Sebelum Eager Loading (Lazy Loading):</span>
                <span class="text-gray-600 text-[11px] mt-0.5 block">
                    Membutuhkan <strong>1 + (N × 4) queries</strong> database. Untuk 100 pendaftar, sistem mengeksekusi <strong>401 queries</strong> yang membebani server database.
                </span>
            </div>
            <div class="rounded-xl border border-emerald-300 bg-white p-3">
                <span class="font-bold text-emerald-700 block text-[11px] uppercase">Sesudah Eager Loading (Saat Ini):</span>
                <span class="text-gray-600 text-[11px] mt-0.5 block">
                    Hanya membutuhkan <strong>5 queries</strong> tetap berapapun jumlah baris data pendaftar (IN queries batching), mengurangi beban database hingga <strong>98%</strong>!
                </span>
            </div>
        </div>
    </div>

    <!-- Filters & Search Toolbar -->
    <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.registrations.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            <div class="sm:col-span-5">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama peserta, email, atau kode..."
                    class="w-full rounded-xl border border-gray-300 px-3.5 py-2 text-xs text-gray-900 focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
            </div>

            <div class="sm:col-span-4">
                <select name="workshop_id" onchange="this.form.submit()" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs text-gray-700 focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
                    <option value="">Semua Workshop</option>
                    @foreach ($workshops as $w)
                        <option value="{{ $w->id }}" {{ request('workshop_id') == $w->id ? 'selected' : '' }}>
                            {{ $w->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-3">
                <select name="status" onchange="this.form.submit()" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs text-gray-700 focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
                    <option value="">Semua Status</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Lunas (Paid)</option>
                    <option value="pending_payment" {{ request('status') === 'pending_payment' ? 'selected' : '' }}>Pending Payment</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Registrations Table -->
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
            <thead class="bg-gray-50 text-[11px] font-bold uppercase tracking-wider text-gray-500">
                <tr>
                    <th class="px-6 py-3.5">Kode Registrasi</th>
                    <th class="px-4 py-3.5">Nama Peserta</th>
                    <th class="px-4 py-3.5">Workshop Terpilih</th>
                    <th class="px-4 py-3.5">Instansi</th>
                    <th class="px-4 py-3.5">Status Bayar</th>
                    <th class="px-4 py-3.5">Tiket Digital</th>
                    <th class="px-6 py-3.5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse ($registrations as $reg)
                    <tr class="hover:bg-gray-50/70 transition">
                        <td class="px-6 py-4 whitespace-nowrap font-mono font-bold text-gray-800">
                            {{ $reg->registration_code }}
                        </td>

                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="font-bold text-gray-900 block">{{ $reg->full_name }}</span>
                            <span class="text-[11px] text-gray-500">{{ $reg->email }} • {{ $reg->phone }}</span>
                        </td>

                        <td class="px-4 py-4">
                            <span class="font-semibold text-gray-900 block max-w-xs truncate">{{ $reg->workshop->title }}</span>
                            <span class="text-[11px] text-gray-500">{{ $reg->workshop->formatted_date }}</span>
                        </td>

                        <td class="px-4 py-4 whitespace-nowrap text-gray-600">
                            {{ $reg->institution ?? '-' }}
                        </td>

                        <td class="px-4 py-4 whitespace-nowrap">
                            @if ($reg->status === 'paid')
                                <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-bold text-emerald-700 border border-emerald-200">
                                    Lunas
                                </span>
                            @elseif ($reg->status === 'cancelled')
                                <span class="rounded-full bg-red-50 px-2.5 py-1 text-[11px] font-bold text-red-700 border border-red-200">
                                    Batal
                                </span>
                            @else
                                <span class="rounded-full bg-amber-50 px-2.5 py-1 text-[11px] font-bold text-amber-700 border border-amber-200">
                                    Pending
                                </span>
                            @endif
                        </td>

                        <td class="px-4 py-4 whitespace-nowrap">
                            @if ($reg->ticket)
                                <a href="{{ route('tickets.show', $reg->ticket->ticket_code) }}" target="_blank"
                                    class="inline-flex items-center gap-1 font-mono font-bold text-red-600 hover:underline">
                                    <span>{{ $reg->ticket->ticket_code }}</span>
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            @else
                                <span class="text-gray-400 italic">Belum terbit</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
                            <form method="POST" action="{{ route('admin.registrations.destroy', $reg->id) }}" class="inline" onsubmit="return confirm('Hapus data pendaftaran ini?')">
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
                            Belum ada data pendaftar yang sesuai.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4 border-t border-gray-100">
            {{ $registrations->links() }}
        </div>
    </div>
</div>
@endsection
