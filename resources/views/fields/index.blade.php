@extends('layouts.app')

@section('title', 'Manajemen Lapangan')

@section('content')
<div class="py-6">
    <div class="flex items-center justify-between mb-5">
        <p class="text-sm text-gray-500 dark:text-gray-400">Total {{ $fields->total() }} lapangan</p>
        <a href="{{ route('fields.create') }}"
           class="bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
            + Tambah Lapangan
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3 text-left">#</th>
                    <th class="px-6 py-3 text-left">Nama Lapangan</th>
                    <th class="px-6 py-3 text-left">Jenis</th>
                    <th class="px-6 py-3 text-left">Harga/Jam</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($fields as $field)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-3 text-gray-400">{{ $loop->iteration }}</td>
                    <td class="px-6 py-3 font-medium">{{ $field->name }}</td>
                    <td class="px-6 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                            {{ $field->type === 'Futsal' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : '' }}
                            {{ $field->type === 'Badminton' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : '' }}
                            {{ $field->type === 'Voli' ? 'bg-orange-100 text-orange-700 dark:bg-orange-900 dark:text-orange-300' : '' }}">
                            {{ $field->type }}
                        </span>
                    </td>
                    <td class="px-6 py-3">Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}</td>
                    <td class="px-6 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                            {{ $field->is_active ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' }}">
                            {{ $field->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-6 py-3 flex items-center gap-2">
                        <a href="{{ route('fields.edit', $field) }}"
                           class="text-blue-600 dark:text-blue-400 hover:underline text-xs">Edit</a>
                        <form method="POST" action="{{ route('fields.destroy', $field) }}"
                              onsubmit="return confirm('Hapus lapangan ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 dark:text-red-400 hover:underline text-xs">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-400">Belum ada lapangan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
            {{ $fields->links() }}
        </div>
    </div>
</div>
@endsection
