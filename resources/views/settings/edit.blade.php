@extends('layouts.app')

@section('title', 'Pengaturan Sport Center')

@section('content')
<div class="py-6 max-w-2xl">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-6">Identitas Sport Center</h3>

        <form method="POST" action="{{ route('settings.update') }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Sport Center</label>
                <input type="text" name="sport_center_name"
                       value="{{ old('sport_center_name', $setting->sport_center_name) }}"
                       class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat</label>
                <textarea name="address" rows="3"
                          class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">{{ old('address', $setting->address) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor Telepon</label>
                <input type="text" name="phone"
                       value="{{ old('phone', $setting->phone) }}"
                       class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                <input type="email" name="email"
                       value="{{ old('email', $setting->email) }}"
                       class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div class="pt-2">
                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2.5 rounded-lg transition-colors duration-200">
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
