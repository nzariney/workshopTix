<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Tiket Digital – {{ $ticket->ticket_code }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css'])

    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body class="bg-gray-100 p-4 sm:p-10 font-sans text-gray-900 antialiased">

    <!-- Print Control Bar -->
    <div class="no-print mx-auto max-w-3xl mb-6 flex items-center justify-between rounded-xl bg-gray-900 px-6 py-3 text-white">
        <div class="flex items-center gap-2 text-xs font-semibold">
            <span>Pratinjau Cetak E-Ticket Digital</span>
            <span class="text-gray-400">|</span>
            <span class="text-red-400 font-mono">{{ $ticket->ticket_code }}</span>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="rounded-lg bg-red-600 px-4 py-1.5 text-xs font-bold hover:bg-red-700 transition">
                Cetak Tiket
            </button>
            <button onclick="window.close()" class="rounded-lg border border-gray-700 px-3 py-1.5 text-xs hover:bg-gray-800 transition">
                Tutup
            </button>
        </div>
    </div>

    <!-- Official Printable Ticket Card -->
    <div class="mx-auto max-w-3xl border-2 border-gray-900 rounded-2xl bg-white overflow-hidden shadow-lg">
        <!-- Header -->
        <div class="bg-gray-950 text-white p-6 flex items-center justify-between border-b-2 border-red-600">
            <div>
                <h1 class="text-xl font-black tracking-wider uppercase text-white">
                    WORKSHOP<span class="text-red-500">TIX</span> PASS
                </h1>
                <p class="text-xs text-gray-400">Pendaftaran & Tiket Digital Workshop IT Terstandar</p>
            </div>
            <div class="text-right">
                <span class="text-[10px] uppercase font-bold text-gray-400 block">Kode Tiket Resmi</span>
                <span class="font-mono text-sm font-black text-red-400">{{ $ticket->ticket_code }}</span>
            </div>
        </div>

        <!-- Content -->
        <div class="p-8 grid grid-cols-12 gap-8 items-center border-b-2 border-dashed border-gray-300">
            <!-- Left Info (8 cols) -->
            <div class="col-span-8 space-y-5">
                <div>
                    <span class="inline-block rounded bg-red-100 px-2.5 py-0.5 text-xs font-bold text-red-700 uppercase">
                        {{ $ticket->workshop->category }}
                    </span>
                    <h2 class="text-2xl font-black text-gray-950 mt-1 leading-snug">
                        {{ $ticket->workshop->title }}
                    </h2>
                    <p class="text-xs text-gray-600 mt-1">Pemateri: <span class="font-bold text-gray-900">{{ $ticket->workshop->instructor_name }}</span></p>
                </div>

                <div class="grid grid-cols-2 gap-4 text-xs border-t border-gray-200 pt-3">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-gray-400 block">Nama Peserta</span>
                        <span class="font-bold text-gray-900 text-sm block">{{ $ticket->registration->full_name }}</span>
                        <span class="text-gray-500 text-[11px]">{{ $ticket->registration->institution ?? 'Umum' }}</span>
                    </div>

                    <div>
                        <span class="text-[10px] uppercase font-bold text-gray-400 block">Nomor Registrasi</span>
                        <span class="font-mono font-bold text-gray-900 block">{{ $ticket->registration->registration_code }}</span>
                        <span class="text-gray-500 text-[11px]">{{ $ticket->registration->phone }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 text-xs border-t border-gray-200 pt-3">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-gray-400 block">Waktu Pelaksanaan</span>
                        <span class="font-bold text-gray-900 block">{{ $ticket->workshop->formatted_date }}</span>
                        <span class="text-gray-500 text-[11px]">{{ $ticket->workshop->start_time }} – {{ $ticket->workshop->end_time }} WIB</span>
                    </div>

                    <div>
                        <span class="text-[10px] uppercase font-bold text-gray-400 block">Lokasi Pelaksanaan</span>
                        <span class="font-bold text-gray-900 block">{{ $ticket->workshop->location }}</span>
                        <span class="text-gray-500 text-[11px] capitalize">{{ $ticket->workshop->location_type }} format</span>
                    </div>
                </div>
            </div>

            <!-- Right QR Stub (4 cols) -->
            <div class="col-span-4 flex flex-col items-center justify-center text-center p-4 bg-gray-50 rounded-xl border border-gray-200">
                <span class="text-[10px] font-bold uppercase text-gray-500 mb-2">SCAN DI MEJA REGISTRASI</span>
                <div class="rounded-xl border-2 border-gray-900 bg-white p-2">
                    <img src="{{ $ticket->qr_code_base64 }}" alt="QR Code" class="h-40 w-40 object-contain">
                </div>
                <span class="mt-2 text-[10px] font-mono font-semibold text-gray-700">{{ $ticket->ticket_code }}</span>
                <span class="text-[10px] font-bold text-emerald-700 bg-emerald-100 rounded px-2 py-0.5 mt-1">STATUS: VALID</span>
            </div>
        </div>

        <!-- Footer Notice -->
        <div class="bg-gray-50 p-6 text-xs text-gray-500 flex items-center justify-between">
            <div>
                <p class="font-semibold text-gray-800">Petunjuk Penting:</p>
                <p class="text-[11px] mt-0.5">Harap hadir 15 menit sebelum acara dimulai dan membawa kartu identitas diri / kartu pelajar.</p>
            </div>
            <div class="text-right text-[11px] font-mono text-gray-400">
                <span>SMK PESAT IT XPRO • REKAYASA PERANGKAT LUNAK</span>
            </div>
        </div>
    </div>
</body>
</html>
