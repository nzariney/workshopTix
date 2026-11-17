@extends('layouts.app')

@section('content')
<div class="py-10 sm:py-14">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('workshops.show', $workshop->slug) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-red-600 transition mb-3">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                <span>Kembali ke Detail Workshop</span>
            </a>
            <h1 class="text-3xl font-black tracking-tight text-gray-950">Formulir Pendaftaran Workshop</h1>
            <p class="text-sm text-gray-500 mt-1">Lengkapi data kepesertaan Anda di bawah ini secara teliti untuk pencetakan e-ticket dan sertifikat resmi.</p>
        </div>

        <form method="POST" action="{{ route('registrations.store', $workshop->slug) }}" class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            @csrf

            <!-- Left: Participant Form -->
            <div class="lg:col-span-7 space-y-6">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 sm:p-8 shadow-sm space-y-5">
                    <h2 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3">Informasi Peserta</h2>

                    <div>
                        <label for="full_name" class="block text-xs font-bold uppercase tracking-wider text-gray-700">Nama Lengkap (Sesuai Sertifikat)</label>
                        <input id="full_name" type="text" name="full_name" value="{{ old('full_name', $user->name) }}" required
                            class="mt-1.5 block w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600"
                            placeholder="Nama Lengkap dengan Gelar jika ada">
                        @error('full_name')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-gray-700">Email Peserta</label>
                            <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="mt-1.5 block w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600"
                                placeholder="email@domain.com">
                            @error('email')
                                <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-gray-700">No. WhatsApp / Telepon</label>
                            <input id="phone" type="text" name="phone" value="{{ old('phone', $user->phone) }}" required
                                class="mt-1.5 block w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600"
                                placeholder="08123456789">
                            @error('phone')
                                <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="institution" class="block text-xs font-bold uppercase tracking-wider text-gray-700">Sekolah / Universitas / Instansi</label>
                        <input id="institution" type="text" name="institution" value="{{ old('institution', $user->institution) }}"
                            class="mt-1.5 block w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600"
                            placeholder="Contoh: SMK Pesat IT XPRO / Umum">
                        @error('institution')
                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="notes" class="block text-xs font-bold uppercase tracking-wider text-gray-700">Alasan / Catatan Tambahan (Opsional)</label>
                        <textarea id="notes" name="notes" rows="3"
                            class="mt-1.5 block w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600"
                            placeholder="Apa ekspektasi Anda mengikuti workshop ini?">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- Payment Method Selection -->
                <div class="rounded-2xl border border-gray-200 bg-white p-6 sm:p-8 shadow-sm space-y-4">
                    <h2 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3">Pilihan Metode Pembayaran</h2>

                    <div class="space-y-3">
                        <label class="flex items-start gap-3 rounded-xl border-2 border-red-300 bg-red-50/40 p-4 hover:border-red-600 cursor-pointer transition">
                            <input type="radio" name="payment_method" value="Midtrans" checked class="mt-1 text-red-600 focus:ring-red-600">
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-sm text-gray-900">Midtrans Payment Gateway</span>
                                    <span class="rounded bg-red-600 px-2 py-0.5 text-[10px] font-bold text-white uppercase tracking-wider">Otomatis / Real-Time</span>
                                </div>
                                <span class="text-xs text-gray-600 block mt-1">
                                    Pembayaran resmi via Midtrans Snap: QRIS, GoPay, ShopeePay, Virtual Account (BCA, Mandiri, BNI, BRI, Permata), serta Kartu Kredit/Debit.
                                </span>
                            </div>
                        </label>

                        <label class="flex items-start gap-3 rounded-xl border border-gray-200 p-4 hover:border-red-600 cursor-pointer transition">
                            <input type="radio" name="payment_method" value="Bank Transfer BCA" class="mt-1 text-red-600 focus:ring-red-600">
                            <div>
                                <span class="font-bold text-sm text-gray-900 block">Transfer Manual Bank BCA</span>
                                <span class="text-xs text-gray-500">Rekening Resmi Panitia (Konfirmasi via upload bukti transfer)</span>
                            </div>
                        </label>

                        <label class="flex items-start gap-3 rounded-xl border border-gray-200 p-4 hover:border-red-600 cursor-pointer transition">
                            <input type="radio" name="payment_method" value="Bank Transfer Mandiri" class="mt-1 text-red-600 focus:ring-red-600">
                            <div>
                                <span class="font-bold text-sm text-gray-900 block">Transfer Manual Bank Mandiri</span>
                                <span class="text-xs text-gray-500">Rekening Resmi Panitia Mandiri</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Right: Order Summary Sidebar -->
            <div class="lg:col-span-5">
                <div class="sticky top-24 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm space-y-6">
                    <h2 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3">Ringkasan Workshop</h2>

                    <div class="space-y-3">
                        <span class="inline-block rounded-md bg-gray-100 px-2 py-0.5 text-[11px] font-bold text-gray-700">
                            {{ $workshop->category }}
                        </span>
                        <h3 class="text-base font-bold text-gray-900 leading-snug">{{ $workshop->title }}</h3>

                        <div class="space-y-1 text-xs text-gray-500 pt-2 border-t border-gray-100">
                            <div><strong>Pemateri:</strong> {{ $workshop->instructor_name }}</div>
                            <div><strong>Jadwal:</strong> {{ $workshop->formatted_date }} ({{ $workshop->start_time }} WIB)</div>
                            <div><strong>Lokasi:</strong> {{ $workshop->location }}</div>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-4 space-y-2">
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span>Harga Tiket:</span>
                            <span class="font-semibold text-gray-900">{{ $workshop->formatted_price }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span>Biaya Layanan:</span>
                            <span class="font-semibold text-emerald-600">Rp 0 (Gratis)</span>
                        </div>
                        <div class="flex items-center justify-between text-sm font-bold text-gray-900 border-t border-gray-100 pt-3">
                            <span>Total Pembayaran:</span>
                            <span class="text-lg font-black text-red-600">{{ $workshop->formatted_price }}</span>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full rounded-xl bg-red-600 py-3.5 text-sm font-bold text-white shadow-md shadow-red-600/25 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2 transition">
                        Konfirmasi & Lanjutkan
                    </button>

                    <div class="text-[11px] text-gray-400 text-center leading-relaxed">
                        Dengan melanjutkan pendaftaran, Anda menyetujui ketentuan kehadiran dan tata tertib workshop IT.
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
