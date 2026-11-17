@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-lg px-4 py-12 sm:py-16">
    <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
        <div class="text-center mb-8">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-red-600 text-white shadow-md shadow-red-600/20 mb-3">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                </svg>
            </div>
            <h1 class="text-2xl font-black tracking-tight text-gray-950">Daftar Akun Peserta</h1>
            <p class="text-xs text-gray-500 mt-1">Buat akun untuk mendaftar workshop dan mendapatkan tiket digital.</p>
        </div>

        <form method="POST" action="{{ route('register.post') }}" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-gray-700">Nama Lengkap</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                    class="mt-1.5 block w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600"
                    placeholder="Contoh: Muhammad Farhan">
                @error('name')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-gray-700">Alamat Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    class="mt-1.5 block w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600"
                    placeholder="farhan@gmail.com">
                @error('email')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-gray-700">Nomor WhatsApp</label>
                    <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required
                        class="mt-1.5 block w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600"
                        placeholder="08123456789">
                    @error('phone')
                        <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="institution" class="block text-xs font-bold uppercase tracking-wider text-gray-700">Sekolah / Instansi</label>
                    <input id="institution" type="text" name="institution" value="{{ old('institution') }}"
                        class="mt-1.5 block w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600"
                        placeholder="SMK / Perusahaan">
                    @error('institution')
                        <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-gray-700">Kata Sandi</label>
                    <input id="password" type="password" name="password" required
                        class="mt-1.5 block w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600"
                        placeholder="Min. 8 karakter">
                    @error('password')
                        <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-gray-700">Ulangi Sandi</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        class="mt-1.5 block w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600"
                        placeholder="Konfirmasi sandi">
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full rounded-xl bg-red-600 py-3 text-sm font-bold text-white shadow-md shadow-red-600/20 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2 transition">
                    Selesaikan Registrasi
                </button>
            </div>
        </form>

        <div class="mt-6 border-t border-gray-100 pt-6 text-center text-xs text-gray-500">
            Sudah memiliki akun?
            <a href="{{ route('login') }}" class="font-bold text-red-600 hover:text-red-700">
                Masuk di Sini
            </a>
        </div>
    </div>
</div>
@endsection
