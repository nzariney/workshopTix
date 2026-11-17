@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-950">Verifikasi Pembayaran</h1>
            <p class="text-xs text-gray-500 mt-1">Periksa mutasi transfer atau bukti pembayaran peserta dan setujui penerbitan tiket.</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.payments.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            <div class="sm:col-span-8">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kode pembayaran, nama peserta, atau email..."
                    class="w-full rounded-xl border border-gray-300 px-3.5 py-2 text-xs text-gray-900 focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
            </div>

            <div class="sm:col-span-4">
                <select name="status" onchange="this.form.submit()" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs text-gray-700 focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending (Menunggu Verifikasi)</option>
                    <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Verified (Terverifikasi)</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected (Ditolak)</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
            <thead class="bg-gray-50 text-[11px] font-bold uppercase tracking-wider text-gray-500">
                <tr>
                    <th class="px-6 py-3.5">Kode Bayar</th>
                    <th class="px-4 py-3.5">Nama Peserta</th>
                    <th class="px-4 py-3.5">Workshop</th>
                    <th class="px-4 py-3.5">Nominal & Metode</th>
                    <th class="px-4 py-3.5">Bukti Transfer</th>
                    <th class="px-4 py-3.5">Status</th>
                    <th class="px-6 py-3.5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse ($payments as $pay)
                    <tr class="hover:bg-gray-50/70 transition">
                        <td class="px-6 py-4 whitespace-nowrap font-mono font-bold text-gray-800">
                            {{ $pay->payment_code }}
                        </td>

                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="font-bold text-gray-900 block">{{ $pay->user->name }}</span>
                            <span class="text-[11px] text-gray-500">{{ $pay->user->email }}</span>
                        </td>

                        <td class="px-4 py-4">
                            <span class="font-semibold text-gray-900 block max-w-xs truncate">{{ $pay->registration->workshop->title }}</span>
                        </td>

                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="font-bold text-gray-950 block">{{ $pay->formatted_amount }}</span>
                            <span class="text-[11px] text-gray-500">{{ $pay->payment_method }}</span>
                        </td>

                        <td class="px-4 py-4 whitespace-nowrap">
                            @if ($pay->payment_proof)
                                <a href="{{ $pay->proof_url }}" target="_blank"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-gray-50 px-2.5 py-1 text-xs font-semibold text-gray-700 hover:bg-gray-100 transition">
                                    <svg class="h-3.5 w-3.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <span>Lihat Bukti</span>
                                </a>
                            @else
                                <span class="text-gray-400 italic">Belum diunggah</span>
                            @endif
                        </td>

                        <td class="px-4 py-4 whitespace-nowrap">
                            @if ($pay->status === 'verified')
                                <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-bold text-emerald-700 border border-emerald-200">
                                    Verified
                                </span>
                            @elseif ($pay->status === 'rejected')
                                <span class="rounded-full bg-red-50 px-2.5 py-1 text-[11px] font-bold text-red-700 border border-red-200" title="{{ $pay->admin_notes }}">
                                    Rejected
                                </span>
                            @else
                                <span class="rounded-full bg-amber-50 px-2.5 py-1 text-[11px] font-bold text-amber-700 border border-amber-200">
                                    Pending
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
                            @if ($pay->status !== 'verified')
                                <form method="POST" action="{{ route('admin.payments.verify', $pay->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Verifikasi pembayaran dan terbitkan tiket digital untuk peserta ini?')"
                                        class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-bold text-white hover:bg-emerald-700 transition">
                                        Setujui
                                    </button>
                                </form>

                                <button type="button" onclick="document.getElementById('reject-modal-{{ $pay->id }}').classList.remove('hidden')"
                                    class="rounded-lg border border-red-300 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50 transition">
                                    Tolak
                                </button>

                                <!-- Reject Modal -->
                                <div id="reject-modal-{{ $pay->id }}" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/60 p-4 flex items-center justify-center">
                                    <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-xl text-left">
                                        <h3 class="text-base font-bold text-gray-950">Tolak Pembayaran</h3>
                                        <p class="text-xs text-gray-500 mt-1">Berikan alasan penolakan bukti pembayaran untuk peserta ini.</p>

                                        <form method="POST" action="{{ route('admin.payments.reject', $pay->id) }}" class="mt-4 space-y-4">
                                            @csrf
                                            <div>
                                                <label class="block text-xs font-bold text-gray-700">Alasan Penolakan</label>
                                                <textarea name="reason" rows="3" required
                                                    class="mt-1 block w-full rounded-xl border border-gray-300 p-2.5 text-xs focus:border-red-600 focus:outline-none"
                                                    placeholder="Contoh: Bukti transfer buram / nominal tidak sesuai / mutasi belum masuk."></textarea>
                                            </div>

                                            <div class="flex items-center justify-end gap-2">
                                                <button type="button" onclick="document.getElementById('reject-modal-{{ $pay->id }}').classList.add('hidden')"
                                                    class="rounded-xl border border-gray-300 px-4 py-2 text-xs font-semibold text-gray-700">
                                                    Batal
                                                </button>
                                                <button type="submit" class="rounded-xl bg-red-600 px-4 py-2 text-xs font-bold text-white hover:bg-red-700">
                                                    Kirim Penolakan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <span class="text-xs text-gray-400 font-medium">Selesai</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-xs text-gray-500">
                            Belum ada riwayat transaksi pembayaran.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4 border-t border-gray-100">
            {{ $payments->links() }}
        </div>
    </div>
</div>
@endsection
