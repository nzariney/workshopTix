@extends('layouts.app')

@section('content')
<div class="py-10 sm:py-14">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <span class="text-xs font-bold uppercase tracking-wider text-red-600">Sistem Pembayaran Digital</span>
            <h1 class="text-3xl font-black tracking-tight text-gray-950 mt-1">Pembayaran Workshop</h1>
            <p class="text-sm text-gray-500 mt-1">Selesaikan transaksi melalui Midtrans Payment Gateway untuk menerbitkan e-ticket digital resmi.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Column: Payment Processing -->
            <div class="lg:col-span-7 space-y-6">
                <!-- Status Banner Card -->
                <div class="rounded-2xl border p-6 {{ $payment->status === 'verified' ? 'border-emerald-200 bg-emerald-50/60' : ($payment->status === 'rejected' ? 'border-red-200 bg-red-50/60' : 'border-amber-200 bg-amber-50/60') }}">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-[10px] uppercase font-bold tracking-wider text-gray-500 block">Kode Pesanan / Order ID</span>
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
                                    Dibatalkan / Ditolak
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-600 px-3 py-1 text-xs font-bold text-white shadow-sm">
                                    <span class="h-2 w-2 rounded-full bg-white animate-pulse"></span>
                                    Menunggu Pembayaran
                                </span>
                            @endif
                        </div>
                    </div>

                    @if ($payment->status === 'verified')
                        <div class="mt-4 pt-4 border-t border-emerald-200/80">
                            <p class="text-xs text-emerald-800 font-medium">
                                Pembayaran transaksi ini telah terkonfirmasi lunas oleh sistem. Tiket digital QR Code Anda telah aktif dan siap digunakan.
                            </p>
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
                            <p class="text-xs text-red-900 font-bold">Catatan Pembayaran:</p>
                            <p class="text-xs text-red-700 mt-0.5">{{ $payment->admin_notes ?? 'Transaksi telah dibatalkan atau kedaluwarsa.' }}</p>
                        </div>
                    @else
                        <div class="mt-4 pt-4 border-t border-amber-200/80 flex items-center justify-between text-xs text-amber-900">
                            <span>Selesaikan transaksi tepat sesuai nominal sebelum kuota kelas terisi penuh.</span>
                            <form method="POST" action="{{ route('payments.checkStatus', $payment->payment_code) }}" class="inline">
                                @csrf
                                <button type="submit" class="font-bold text-amber-800 underline hover:text-amber-950">
                                    Refresh Status
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

                <!-- Section: Real Midtrans Payment Gateway -->
                @if ($payment->status !== 'verified')
                    <div class="rounded-2xl border-2 border-red-600 bg-white p-6 shadow-md shadow-red-600/5 space-y-5">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                            <div class="flex items-center gap-2.5">
                                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-red-600 text-white font-black text-sm">
                                    M
                                </div>
                                <div>
                                    <h2 class="text-base font-black text-gray-950">Midtrans Payment Gateway</h2>
                                    <span class="text-[11px] text-gray-500">Pembayaran Online Resmi & Terverifikasi Otomatis</span>
                                </div>
                            </div>
                            <span class="rounded bg-red-100 px-2 py-0.5 text-[10px] font-bold text-red-800 uppercase">
                                SNAP GATEWAY
                            </span>
                        </div>

                        <!-- Amount Display -->
                        <div class="rounded-xl bg-gray-50 p-4 border border-gray-200">
                            <span class="text-xs text-gray-500 block">Total Nominal Tagihan:</span>
                            <div class="text-3xl font-black text-red-600 mt-1">
                                {{ $payment->formatted_amount }}
                            </div>
                            <span class="text-[11px] text-gray-400 mt-0.5 block">Termasuk semua metode pembayaran digital.</span>
                        </div>

                        <!-- Channels Grid -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-center text-[11px] font-semibold text-gray-600">
                            <div class="rounded-lg border border-gray-200 p-2 bg-gray-50">QRIS (GoPay/Dana)</div>
                            <div class="rounded-lg border border-gray-200 p-2 bg-gray-50">Virtual Account BCA</div>
                            <div class="rounded-lg border border-gray-200 p-2 bg-gray-50">VA Mandiri / BNI</div>
                            <div class="rounded-lg border border-gray-200 p-2 bg-gray-50">Kartu Kredit / Debit</div>
                        </div>

                        <!-- Midtrans Snap Action Button -->
                        @if ($isMidtransConfigured)
                            <div class="pt-2 space-y-3">
                                <!-- 1. Primary Redirection Button (Opens real Midtrans Snap hosted page) -->
                                <a href="{{ route('payments.pay', $payment->payment_code) }}" target="_blank"
                                    class="w-full inline-flex items-center justify-center gap-2.5 rounded-xl bg-red-600 py-3.5 px-6 text-sm font-bold text-white shadow-lg shadow-red-600/25 hover:bg-red-700 transition">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                    </svg>
                                    <span>Buka Halaman Pembayaran Midtrans Snap</span>
                                </a>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                    @if ($snapToken)
                                        <!-- 2. Popup Option -->
                                        <button type="button" id="pay-button"
                                            class="inline-flex items-center justify-center gap-2 rounded-xl border border-red-600/30 bg-red-50/50 py-2.5 px-4 text-xs font-bold text-red-700 hover:bg-red-100 transition">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 7.5h-.75A2.25 2.25 0 004.5 9.75v7.5A2.25 2.25 0 006.75 19.5h7.5A2.25 2.25 0 0016.5 17.25v-.75m-6-6h7.5A2.25 2.25 0 0120.25 12v7.5a2.25 2.25 0 01-2.25 2.25h-7.5a2.25 2.25 0 01-2.25-2.25V12a2.25 2.25 0 012.25-2.25z" />
                                            </svg>
                                            <span>Buka Snap Popup</span>
                                        </button>
                                    @endif

                                    <!-- 3. Real-Time Status Check -->
                                    <form method="POST" action="{{ route('payments.checkStatus', $payment->payment_code) }}" class="{{ $snapToken ? '' : 'sm:col-span-2' }}">
                                        @csrf
                                        <button type="submit"
                                            class="w-full inline-flex items-center justify-center gap-2 rounded-xl border border-gray-300 bg-white py-2.5 px-4 text-xs font-bold text-gray-800 hover:bg-gray-50 transition shadow-sm">
                                            <svg class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                            </svg>
                                            <span>Periksa Status Transaksi</span>
                                        </button>
                                    </form>
                                </div>

                                <!-- Sandbox Testing Helper -->
                                <div class="rounded-xl border border-gray-200 bg-gray-50 p-3.5 text-xs text-gray-600 space-y-1.5">
                                    <div class="font-bold text-gray-900 flex items-center justify-between">
                                        <span>Panduan Uji Coba Midtrans Sandbox:</span>
                                        <a href="https://simulator.sandbox.midtrans.com/" target="_blank"
                                            class="inline-flex items-center gap-1 font-bold text-red-600 hover:underline">
                                            <span>Midtrans Simulator</span>
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                                        </a>
                                    </div>
                                    <p class="text-[11px] leading-relaxed text-gray-500">
                                        Buka halaman Midtrans Snap di atas, pilih Virtual Account atau QRIS. Bayar menggunakan Midtrans Simulator, lalu klik <strong>Periksa Status Transaksi</strong> untuk otomatis menerbitkan tiket digital QR Code.
                                    </p>
                                </div>
                            </div>
                        @else
                            <!-- Midtrans Not Yet Configured Banner -->
                            <div class="rounded-xl border border-amber-300 bg-amber-50 p-4 text-xs text-amber-900 space-y-2">
                                <div class="font-bold flex items-center gap-1.5">
                                    <svg class="h-4 w-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                    </svg>
                                    <span>Konfigurasi API Key Midtrans Diperlukan</span>
                                </div>
                                <p class="text-[11px] text-amber-800 leading-relaxed">
                                    Untuk menggunakan pembayaran online Midtrans Snap, masukkan Server Key dan Client Key akun Midtrans Anda pada file <code class="bg-amber-200/60 px-1 py-0.5 rounded font-mono text-[10px]">.env</code>:
                                </p>
                                <pre class="bg-gray-900 text-gray-100 p-2.5 rounded-lg font-mono text-[11px] overflow-x-auto">MIDTRANS_SERVER_KEY=SB-Mid-server-xxxx
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxx
MIDTRANS_IS_PRODUCTION=false</pre>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Alternative: Manual Bank Transfer Option -->
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm space-y-4">
                    <h2 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3">Alternatif: Transfer Manual Panitia</h2>

                    <div class="space-y-3">
                        <div class="rounded-xl border border-gray-200 p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-xs font-bold text-gray-900">Bank Central Asia (BCA)</span>
                                    <div class="font-mono text-sm font-black text-gray-800 mt-0.5">8730 1928 3721</div>
                                    <span class="text-[11px] text-gray-500">a.n. Panitia Workshop IT Pesat</span>
                                </div>
                                <button type="button" onclick="navigator.clipboard.writeText('873019283721'); alert('Nomor rekening BCA disalin!');"
                                    class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-100 transition">
                                    Salin Rekening
                                </button>
                            </div>
                        </div>

                        <div class="rounded-xl border border-gray-200 p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-xs font-bold text-gray-900">Bank Mandiri</span>
                                    <div class="font-mono text-sm font-black text-gray-800 mt-0.5">133 00 987654 3</div>
                                    <span class="text-[11px] text-gray-500">a.n. SMK Pesat Rekayasa Perangkat Lunak</span>
                                </div>
                                <button type="button" onclick="navigator.clipboard.writeText('133009876543'); alert('Nomor rekening Mandiri disalin!');"
                                    class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-100 transition">
                                    Salin Rekening
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Form for manual transfer -->
                    @if ($payment->status !== 'verified')
                        <div class="border-t border-gray-100 pt-4 space-y-3">
                            <span class="text-xs font-bold text-gray-800 block">Unggah Bukti Struk Manual (Jika Transfer ke Rekening di Atas):</span>
                            @if ($payment->payment_proof)
                                <div class="rounded-xl border border-gray-200 bg-gray-50 p-3 flex items-center gap-3">
                                    <a href="{{ $payment->proof_url }}" target="_blank" class="h-12 w-12 rounded border bg-white overflow-hidden shrink-0">
                                        <img src="{{ $payment->proof_url }}" alt="" class="h-full w-full object-cover">
                                    </a>
                                    <span class="text-xs text-gray-600">Bukti transfer telah tersimpan. Panitia akan meninjau.</span>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('payments.uploadProof', $payment->payment_code) }}" enctype="multipart/form-data" class="space-y-3">
                                @csrf
                                <input id="payment_proof" type="file" name="payment_proof" accept="image/*" required
                                    class="block w-full text-xs text-gray-700 file:mr-4 file:rounded-lg file:border-0 file:bg-gray-100 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-gray-800 hover:file:bg-gray-200">
                                <button type="submit" class="rounded-xl bg-gray-900 px-4 py-2 text-xs font-bold text-white hover:bg-gray-800 transition">
                                    Unggah Bukti Transfer
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
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
                            <span>Metode:</span>
                            <span class="font-bold text-gray-900">{{ $payment->payment_method }}</span>
                        </div>
                        <div class="flex items-center justify-between text-base font-black text-gray-900 mt-2">
                            <span>Total Pembayaran:</span>
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

<!-- Hidden Midtrans Finish Form -->
<form id="midtrans-finish-form" method="POST" action="{{ route('payments.midtransFinish', $payment->payment_code) }}" class="hidden">
    @csrf
    <input type="hidden" name="result_data" id="midtrans-result-data">
</form>

@if ($isMidtransConfigured && $snapToken && $payment->status !== 'verified')
    <script src="{{ $snapUrl }}" data-client-key="{{ $clientKey }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const payButton = document.getElementById('pay-button');
            if (payButton) {
                payButton.addEventListener('click', function () {
                    window.snap.pay('{{ $snapToken }}', {
                        onSuccess: function (result) {
                            document.getElementById('midtrans-result-data').value = JSON.stringify(result);
                            document.getElementById('midtrans-finish-form').submit();
                        },
                        onPending: function (result) {
                            document.getElementById('midtrans-result-data').value = JSON.stringify(result);
                            document.getElementById('midtrans-finish-form').submit();
                        },
                        onError: function (result) {
                            document.getElementById('midtrans-result-data').value = JSON.stringify(result);
                            document.getElementById('midtrans-finish-form').submit();
                        },
                        onClose: function () {
                            // User closed popup without finishing
                            console.log('Midtrans Snap popup ditutup.');
                        }
                    });
                });
            }
        });
    </script>
@endif
@endsection
