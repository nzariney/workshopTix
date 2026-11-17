@extends('layouts.admin')

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-gray-950">Tambah Workshop IT Baru</h1>
            <p class="text-xs text-gray-500 mt-1">Isi formulir lengkap untuk mempublikasikan workshop IT ke katalog sistem.</p>
        </div>
        <a href="{{ route('admin.workshops.index') }}" class="rounded-xl border border-gray-300 px-3.5 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-100 transition">
            Kembali
        </a>
    </div>

    <form method="POST" action="{{ route('admin.workshops.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="rounded-2xl border border-gray-200 bg-white p-6 sm:p-8 shadow-sm space-y-5">
            <h2 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3">Informasi Utama</h2>

            <div>
                <label for="title" class="block text-xs font-bold uppercase tracking-wider text-gray-700">Judul Workshop</label>
                <input id="title" type="text" name="title" value="{{ old('title') }}" required
                    class="mt-1.5 block w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600"
                    placeholder="Contoh: Mastering Docker, Kubernetes & CI/CD Pipeline">
                @error('title')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="category" class="block text-xs font-bold uppercase tracking-wider text-gray-700">Kategori IT</label>
                    <input id="category" type="text" name="category" value="{{ old('category', 'Web Development') }}" required list="category-suggestions"
                        class="mt-1.5 block w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
                    <datalist id="category-suggestions">
                        <option value="Web Development">
                        <option value="Cloud & DevOps">
                        <option value="Cyber Security">
                        <option value="AI & Data Science">
                        <option value="Mobile Development">
                        <option value="UI/UX Design">
                    </datalist>
                </div>

                <div>
                    <label for="status" class="block text-xs font-bold uppercase tracking-wider text-gray-700">Status Awal</label>
                    <select id="status" name="status" class="mt-1.5 block w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
                        <option value="available" selected>Available (Tersedia untuk pendaftaran)</option>
                        <option value="full">Full (Kuota Penuh)</option>
                        <option value="closed">Closed (Ditutup)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="instructor_name" class="block text-xs font-bold uppercase tracking-wider text-gray-700">Nama Pemateri</label>
                    <input id="instructor_name" type="text" name="instructor_name" value="{{ old('instructor_name') }}" required
                        class="mt-1.5 block w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600"
                        placeholder="Contoh: Hendra Pratama, S.Kom.">
                </div>

                <div>
                    <label for="instructor_title" class="block text-xs font-bold uppercase tracking-wider text-gray-700">Gelar / Jabatan Pemateri</label>
                    <input id="instructor_title" type="text" name="instructor_title" value="{{ old('instructor_title') }}"
                        class="mt-1.5 block w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600"
                        placeholder="Contoh: Senior DevOps Engineer di GoTo">
                </div>
            </div>

            <div>
                <label for="description" class="block text-xs font-bold uppercase tracking-wider text-gray-700">Deskripsi Lengkap</label>
                <textarea id="description" name="description" rows="4" required
                    class="mt-1.5 block w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600"
                    placeholder="Uraikan tujuan dan gambaran materi workshop...">{{ old('description') }}</textarea>
            </div>

            <div>
                <label for="learning_points" class="block text-xs font-bold uppercase tracking-wider text-gray-700">Poin Pembelajaran (Pisahkan Tiap Baris Baru)</label>
                <textarea id="learning_points" name="learning_points" rows="3"
                    class="mt-1.5 block w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600"
                    placeholder="Konfigurasi Docker & Containerization&#10;Implementasi Pipeline GitHub Actions&#10;Deployment ke Cloud Cluster">{{ old('learning_points') }}</textarea>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 sm:p-8 shadow-sm space-y-5">
            <h2 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3">Jadwal, Kuota & Biaya</h2>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="date" class="block text-xs font-bold uppercase tracking-wider text-gray-700">Tanggal</label>
                    <input id="date" type="date" name="date" value="{{ old('date', date('Y-m-d', strtotime('+7 days'))) }}" required
                        class="mt-1.5 block w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
                </div>

                <div>
                    <label for="start_time" class="block text-xs font-bold uppercase tracking-wider text-gray-700">Jam Mulai</label>
                    <input id="start_time" type="text" name="start_time" value="{{ old('start_time', '08:30') }}" required
                        class="mt-1.5 block w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
                </div>

                <div>
                    <label for="end_time" class="block text-xs font-bold uppercase tracking-wider text-gray-700">Jam Selesai</label>
                    <input id="end_time" type="text" name="end_time" value="{{ old('end_time', '15:30') }}" required
                        class="mt-1.5 block w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="location_type" class="block text-xs font-bold uppercase tracking-wider text-gray-700">Format Workshop</label>
                    <select id="location_type" name="location_type" class="mt-1.5 block w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
                        <option value="offline" selected>Offline (Tatap Muka di Lab / Kampus)</option>
                        <option value="online">Online (Streaming / Meeting Room)</option>
                        <option value="hybrid">Hybrid (Tatap Muka & Online)</option>
                    </select>
                </div>

                <div>
                    <label for="location" class="block text-xs font-bold uppercase tracking-wider text-gray-700">Ruangan / Venue Lokasi</label>
                    <input id="location" type="text" name="location" value="{{ old('location', 'Lab Software Engineering Lt. 3, SMK Pesat IT XPRO') }}" required
                        class="mt-1.5 block w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="price" class="block text-xs font-bold uppercase tracking-wider text-gray-700">Biaya Pendaftaran (Rp)</label>
                    <input id="price" type="number" name="price" value="{{ old('price', '150000') }}" min="0" required
                        class="mt-1.5 block w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
                    <span class="text-[11px] text-gray-400 mt-1 block">Isi 0 jika workshop ini gratis tanpa dipungut biaya.</span>
                </div>

                <div>
                    <label for="quota" class="block text-xs font-bold uppercase tracking-wider text-gray-700">Kapasitas / Kuota Peserta</label>
                    <input id="quota" type="number" name="quota" value="{{ old('quota', '30') }}" min="1" required
                        class="mt-1.5 block w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="image_url" class="block text-xs font-bold uppercase tracking-wider text-gray-700">URL Gambar Banner</label>
                    <input id="image_url" type="url" name="image_url" value="{{ old('image_url') }}"
                        class="mt-1.5 block w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:border-red-600 focus:outline-none focus:ring-1 focus:ring-red-600"
                        placeholder="https://images.unsplash.com/photo-...">
                </div>

                <div>
                    <label for="image" class="block text-xs font-bold uppercase tracking-wider text-gray-700">Atau Unggah File Foto</label>
                    <input id="image" type="file" name="image" accept="image/*"
                        class="mt-1.5 block w-full text-xs text-gray-600 file:mr-4 file:rounded-xl file:border-0 file:bg-red-50 file:px-4 file:py-2.5 file:text-xs file:font-bold file:text-red-700 hover:file:bg-red-100">
                </div>
            </div>

            <div class="flex items-center gap-2 pt-2">
                <input id="is_featured" type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                    class="rounded border-gray-300 text-red-600 focus:ring-red-600">
                <label for="is_featured" class="text-xs font-semibold text-gray-700">Tampilkan sebagai Workshop Unggulan di Halaman Utama</label>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.workshops.index') }}" class="rounded-xl border border-gray-300 px-5 py-2.5 text-xs font-semibold text-gray-700 hover:bg-gray-100 transition">
                Batal
            </a>
            <button type="submit" class="rounded-xl bg-red-600 px-6 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-red-700 transition">
                Simpan & Publikasikan
            </button>
        </div>
    </form>
</div>
@endsection
