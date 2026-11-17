@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-950">Data Pengguna & Peserta</h1>
            <p class="text-xs text-gray-500 mt-1">Daftar akun peserta dan panitia yang terdaftar dalam sistem WORKSHOPTIX.</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            <div class="sm:col-span-8">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, email, no. telp, atau institusi..."
                    class="w-full rounded-xl border border-gray-300 px-3.5 py-2 text-xs text-gray-900 focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
            </div>

            <div class="sm:col-span-4">
                <select name="role" onchange="this.form.submit()" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs text-gray-700 focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
                    <option value="">Semua Peran (Role)</option>
                    <option value="participant" {{ request('role') === 'participant' ? 'selected' : '' }}>Peserta</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Panitia / Admin</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
            <thead class="bg-gray-50 text-[11px] font-bold uppercase tracking-wider text-gray-500">
                <tr>
                    <th class="px-6 py-3.5">Nama & Kontak</th>
                    <th class="px-4 py-3.5">Instansi / Sekolah</th>
                    <th class="px-4 py-3.5">Role</th>
                    <th class="px-4 py-3.5">Total Pendaftaran</th>
                    <th class="px-4 py-3.5">Tiket Diterbitkan</th>
                    <th class="px-6 py-3.5 text-right">Terdaftar Pada</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse ($users as $u)
                    <tr class="hover:bg-gray-50/70 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-bold text-gray-900 block">{{ $u->name }}</span>
                            <span class="text-[11px] text-gray-500">{{ $u->email }} • {{ $u->phone ?? '-' }}</span>
                        </td>

                        <td class="px-4 py-4 whitespace-nowrap text-gray-600">
                            {{ $u->institution ?? 'Umum' }}
                        </td>

                        <td class="px-4 py-4 whitespace-nowrap">
                            @if ($u->role === 'admin')
                                <span class="rounded bg-red-100 px-2.5 py-0.5 text-[10px] font-bold text-red-800 uppercase tracking-wider">
                                    Panitia
                                </span>
                            @else
                                <span class="rounded bg-gray-100 px-2.5 py-0.5 text-[10px] font-bold text-gray-800 uppercase tracking-wider">
                                    Peserta
                                </span>
                            @endif
                        </td>

                        <td class="px-4 py-4 whitespace-nowrap font-bold text-gray-900">
                            {{ $u->registrations_count }} workshop
                        </td>

                        <td class="px-4 py-4 whitespace-nowrap font-bold text-emerald-600">
                            {{ $u->tickets_count }} tiket
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right text-gray-400">
                            {{ $u->created_at->format('d/m/Y') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-xs text-gray-500">
                            Belum ada data pengguna.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4 border-t border-gray-100">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
