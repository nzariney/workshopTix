@extends('layouts.app')

@section('content')
<div class="py-10 sm:py-14">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <span class="text-xs font-bold uppercase tracking-wider text-red-600">Sistem Pembayaran Digital</span>
            <h1 class="text-3xl font-black tracking-tight text-gray-950 mt-1">Status & Instruksi Pembayaran</h1>
            <p class="text-sm text-gray-500 mt-1">Selesaikan pembayaran untuk mengonfirmasi kursi dan menerbitkan e-ticket digital resmi.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Column: Payment Details & Instructions -->
            <div class="lg:col-span-7 space-y-6">
                <!-- Status Banner Card -->
                <div class="rounded-2xl border p-6 {{ $payment->status === 'verified' ? 'border-emerald-200 bg-emerald-50/60' : ($payment->status === 'rejected' ? 'border-red-200 bg-red-50/60' : 'border-amber-200 bg-amber-50/60') }}">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-[10px] uppercase font-bold tracking-wider text-gray-500 block">Kode Pembayaran</span>
                            <span class="font-mono font-bold text-base text-gray-900">{{ $payment->payment_code }}</span>
                        </div>
                        <div>
                            @if ($payment->status === 'verified')
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-600 px-3 py-1 text-xs font-bold text-white shadow-sm">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    Terverifikasi Lunas
                                </span>
                            @elseif ($payment->status === 'rejected')
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-red-600 px-3 py-1 text-xs font-bold text-white shadow-sm">
                                    Ditolak Panitia
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-600 px-3 py-1 text-xs font-bold text-white shadow-sm">
                                    <span class="h-2 w-2 rounded-full bg-white animate-pulse"></span>
                                    Menunggu Verifikasi
                                </span>
                            @endif
                        </div>
                    </div>

                    @if ($payment->status === 'verified')
                        <div class="mt-4 pt-4 border-t border-emerald-200/80">
                            <p class="text-xs text-emerald-800 font-medium">Pembayaran Anda telah diverifikasi oleh panitia. Tiket digital QR Code resmi Anda telah diterbitkan.</p>
                            @if ($ticket)
                                <a href="{{ route('tickets.show', $ticket->ticket_code) }}"
                                    class="mt-3 inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white shadow-sm hover:bg-emerald-700 transition">
                                    <span>Buka Tiket Digital</span>
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                                </a>
                            @endif
                        </div>
                    @elseif ($payment->status === 'rejected')
                        <div class="mt-4 pt-4 border-t border-red-200/80">
                            <p class="text-xs text-red-900 font-bold">Catatan Penolakan dari Panitia:</p>
                            <p class="text-xs text-red-700 mt-0.5">{{ $payment->admin_notes ?? 'Bukti transfer tidak terbaca atau nominal tidak sesuai.' }}</p>
                            <p class="text-[11px] text-gray-500 mt-2">Silakan unggah ulang bukti pembayaran yang benar melalui form di bawah ini.</p>
                        </div>
                    @else
                        <div class="mt-4 pt-4 border-t border-amber-200/80 text-xs text-amber-900">
                            Panitia sedang meninjau konfirmasi transaksi Anda. Status akan diperbarui segera setelah pengecekan mutasi bank.
                        </div>
                    @endif
                </div>

                <!-- Transfer Instructions Card -->
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm space-y-5">
                    <h2 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3">Instruksi Pembayaran</h2>

                    <div class="rounded-xl bg-gray-50 p-4 border border-gray-200">
                        <span class="text-xs text-gray-500 block">Total Nominal yang Harus Ditransfer:</span>
                        <div class="text-2xl font-black text-red-600 mt-1">
                            {{ $payment->formatted_amount }}
                        </div>
                        <span class="text-[11px] text-gray-400 mt-0.5 block">Transfer tepat sesuai nominal tanpa pembulatan.</span>
                    </div>

                    <!-- Destination Accounts -->
                    <div class="space-y-3">
                        <div class="rounded-xl border border-gray-200 p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-xs font-bold text-gray-900">Bank Central Asia (BCA)</span>
                                    <div class="font-mono text-sm font-black text-gray-800 mt-0.5" id="bca-acc">8730 1928 3721</div>
                                    <span class="text-[11px] text-gray-500">a.n. Panitia Workshop IT Pesat</span>
                                </div>
                                <button type="button" onclick="navigator.clipboard.writeText('873019283721'); alert('Nomor rekening BCA disalin!');"
                                    class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-100 transition">
                                    Salin
                                </button>
                            </div>
                        </div>

                        <div class="rounded-xl border border-gray-200 p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-xs font-bold text-gray-900">Bank Mandiri</span>
                                    <div class="font-mono text-sm font-black text-gray-800 mt-0.5" id="mandiri-acc">133 00 987654 3</div>
                                    <span class="text-[11px] text-gray-500">a.n. SMK Pesat Rekayasa Perangkat Lunak</span>
                                </div>
                                <button type="button" onclick="navigator.clipboard.writeText('133009876543'); alert('Nomor rekening Mandiri disalin!');"
                                    class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-100 transition">
                                    Salin
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upload Proof Form -->
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm space-y-4">
                    <h2 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3">Unggah Bukti Pembayaran</h2>

                    @if ($payment->payment_proof)
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 flex items-center gap-4">
                            <a href="{{ $payment->proof_url }}" target="_blank" class="block h-16 w-16 shrink-0 overflow-hidden rounded-lg border border-gray-300 bg-white">
                                <img src="{{ $payment->proof_url }}" alt="Bukti Transfer" class="h-full w-full object-cover">
                            </a>
                            <div class="flex-1 text-xs">
                                <span class="font-bold text-gray-900 block">Bukti Transfer Terunggah</span>
                                <span class="text-gray-500 text-[11px]">File tersimpan di sistem. Klik gambar untuk melihat ukuran penuh.</span>
                                <a href="{{ $payment->proof_url }}" target="_blank" class="font-semibold text-red-600 hover:underline mt-1 block">
                                    Buka Foto Bukti
                                </a>
                            </div>
                        </div>
                    @endif

                    @if ($payment->status !== 'verified')
                        <form method="POST" action="{{ route('payments.uploadProof', $payment->payment_code) }}" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <label for="payment_proof" class="block text-xs font-bold uppercase tracking-wider text-gray-700">Pilih Berkas Foto Bukti Transfer</label>
                                <input id="payment_proof" type="file" name="payment_proof" accept="image/*" required
                                    class="mt-1.5 block w-full rounded-xl border border-gray-300 px-3 py-2 text-xs text-gray-700 file:mr-4 file:rounded-lg file:border-0 file:bg-red-50 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-red-700 hover:file:bg-red-100">
                                <span class="text-[11px] text-gray-400 mt-1 block">Format diperbolehkan: JPG, PNG, WEBP. Maksimal 4 MB.</span>
                                @error('payment_proof')
                                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="rounded-xl bg-gray-950 px-5 py-2.5 text-xs font-bold text-white hover:bg-gray-800 transition">
                                Unggah Bukti Transfer
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Midtrans Sandbox Simulator (Brief Step 04 & 06 Drill Pra Sertifikasi) -->
                @if ($payment->status !== 'verified')
                    <div class="rounded-2xl border border-red-300 bg-red-50/60 p-6 shadow-sm space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="rounded bg-red-600 px-2 py-0.5 text-[10px] font-bold text-white uppercase">
                                Drill LKPD
                            </span>
                            <h3 class="text-sm font-bold text-gray-900">Simulasi Bayar Instan (Midtrans Sandbox Simulator)</h3>
                        </div>
                        <p class="text-xs text-gray-600 leading-relaxed">
                            Khusus penilaian dan simulasi pra-sertifikasi: Anda dapat menggunakan tombol di bawah ini untuk mensimulasikan status lunas otomatis secara langsung tanpa transfer nyata, sehingga tiket digital langsung diterbitkan.
                        </p>
                        <form method="POST" action="{{ route('payments.simulateSandbox', $payment->payment_code) }}">
                            @csrf
                            <button type="submit"
                                onclick="return confirm('Jalankan simulasi bayar sukses Midtrans Sandbox?')"
                                class="inline-flex items-center gap-2 rounded-xl bg-red-600 px-5 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-red-700 transition">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                                </svg>
                                <span>Simulasikan Pembayaran Sukses Sandbox</span>
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <!-- Right Column: Registration Summary -->
            <div class="lg:col-span-5">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm space-y-5">
                    <h2 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3">Detail Pesanan</h2>

                    <div class="space-y-3">
                        <span class="inline-block rounded-md bg-gray-100 px-2 py-0.5 text-[11px] font-bold text-gray-700">
                            {{ $payment->registration->workshop->category }}
                        </span>
                        <h3 class="text-base font-bold text-gray-900 leading-snug">
                            {{ $payment->registration->workshop->title }}
                        </h3>

                        <div class="space-y-1.5 text-xs text-gray-500 pt-2 border-t border-gray-100">
                            <div><strong>Kode Registrasi:</strong> {{ $payment->registration->registration_code }}</div>
                            <div><strong>Nama Peserta:</strong> {{ $payment->registration->full_name }}</div>
                            <div><strong>Email:</strong> {{ $payment->registration->email }}</div>
                            <div><strong>No. Telp:</strong> {{ $payment->registration->phone }}</div>
                            <div><strong>Jadwal:</strong> {{ $payment->registration->workshop->formatted_date }}</div>
                            <div><strong>Lokasi:</strong> {{ $payment->registration->workshop->location }}</div>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-4">
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span>Metode Pembayaran:</span>
                            <span class="font-bold text-gray-900">{{ $payment->payment_method }}</span>
                        </div>
                        <div class="flex items-center justify-between text-base font-black text-gray-900 mt-2">
                            <span>Total Tagihan:</span>
                            <span class="text-xl text-red-600">{{ $payment->formatted_amount }}</span>
                        </div>
                    </div>

                    @if ($ticket)
                        <div class="pt-2">
                            <a href="{{ route('tickets.show', $ticket->ticket_code) }}"
                                class="block w-full text-center rounded-xl bg-red-600 py-3 text-xs font-bold text-white shadow-md hover:bg-red-700 transition">
                                Buka E-Ticket Digital
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
