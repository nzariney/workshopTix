@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-md px-4 py-12 sm:py-16">
    <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
        <div class="text-center mb-8">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-red-600 text-white shadow-md shadow-red-600/20 mb-3">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                </svg>
            </div>
            <h1 class="text-2xl font-black tracking-tight text-gray-950">Masuk Akun</h1>
            <p class="text-xs text-gray-500 mt-1">Gunakan akun Anda untuk mendaftar workshop dan melihat tiket digital.</p>
        </div>

        <!-- Quick Demo Switcher Card -->
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50/70 p-3.5 text-xs text-gray-800">
            <span class="font-bold text-red-700 block mb-1.5">Akses Cepat Uji Coba:</span>
            <div class="grid grid-cols-2 gap-2">
                <form method="POST" action="{{ route('quick.login') }}">
                    @csrf
                    <input type="hidden" name="role" value="admin">
                    <button type="submit" class="w-full rounded-lg bg-red-600 py-1.5 text-[11px] font-bold text-white hover:bg-red-700 transition">
                        Masuk Panitia
                    </button>
                </form>
                <form method="POST" action="{{ route('quick.login') }}">
                    @csrf
                    <input type="hidden" name="role" value="participant">
                    <button type="submit" class="w-full rounded-lg bg-gray-900 py-1.5 text-[11px] font-bold text-white hover:bg-gray-800 transition">
                        Masuk Peserta
                    </button>
                </form>
            </div>
        </div>

        <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-gray-700">Alamat Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="mt-1.5 block w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600"
                    placeholder="nama@email.com">
                @error('email')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <div class="flex items-center justify-between">
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-gray-700">Kata Sandi</label>
                </div>
                <input id="password" type="password" name="password" required
                    class="mt-1.5 block w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600"
                    placeholder="••••••••">
                @error('password')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 text-gray-600 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-red-600 focus:ring-red-600">
                    <span>Ingat saya di perangkat ini</span>
                </label>
            </div>

            <button type="submit" class="w-full rounded-xl bg-red-600 py-3 text-sm font-bold text-white shadow-md shadow-red-600/20 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2 transition">
                Masuk Sekarang
            </button>
        </form>

        <div class="mt-6 border-t border-gray-100 pt-6 text-center text-xs text-gray-500">
            Belum memiliki akun?
            <a href="{{ route('register') }}" class="font-bold text-red-600 hover:text-red-700">
                Daftar Akun Baru
            </a>
        </div>
    </div>
</div>
@endsection
