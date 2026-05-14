@extends('layouts.app')

@section('title', 'Tambah Lapangan')

@section('content')
<div class="py-6 max-w-lg">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
        <form method="POST" action="{{ route('fields.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Lapangan <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       placeholder="cth: Lapangan Futsal A"
                       class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jenis Lapangan <span class="text-red-500">*</span></label>
                <select name="type" required
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">-- Pilih Jenis --</option>
                    <option value="Futsal" {{ old('type') === 'Futsal' ? 'selected' : '' }}>Futsal</option>
                    <option value="Badminton" {{ old('type') === 'Badminton' ? 'selected' : '' }}>Badminton</option>
                    <option value="Voli" {{ old('type') === 'Voli' ? 'selected' : '' }}>Voli</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Harga per Jam (Rp) <span class="text-red-500">*</span></label>
                <input type="number" name="price_per_hour" value="{{ old('price_per_hour') }}" required min="0"
                       placeholder="cth: 100000"
                       class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                <textarea name="description" rows="3"
                          class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">{{ old('description') }}</textarea>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" id="is_active" name="is_active" value="1"
                       {{ old('is_active', '1') ? 'checked' : '' }}
                       class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                <label for="is_active" class="text-sm text-gray-700 dark:text-gray-300">Lapangan Aktif</label>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2.5 rounded-lg transition-colors">
                    Simpan
                </button>
                <a href="{{ route('fields.index') }}"
                   class="bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-semibold px-6 py-2.5 rounded-lg transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
