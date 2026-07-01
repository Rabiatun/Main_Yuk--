@extends('layouts.app')

@section('title', 'Data Pelanggan')

@section('content')
<div class="py-6">
    <div class="flex items-center justify-between mb-5">
        <p class="text-sm text-gray-500 dark:text-gray-400">Total {{ $customers->total() }} pelanggan</p>
        <a href="{{ route('customers.create') }}"
           class="bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
            + Tambah Pelanggan
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3 text-left">#</th>
                    <th class="px-6 py-3 text-left">Nama</th>
                    <th class="px-6 py-3 text-left">Telepon</th>
                    <th class="px-6 py-3 text-left">Email</th>
                    <th class="px-6 py-3 text-left">Alamat</th>
                    <th class="px-6 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($customers as $customer)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-3 text-gray-400">{{ $loop->iteration }}</td>
                    <td class="px-6 py-3 font-medium">{{ $customer->name }}</td>
                    <td class="px-6 py-3">{{ $customer->phone ?? '-' }}</td>
                    <td class="px-6 py-3">{{ $customer->email ?? '-' }}</td>
                    <td class="px-6 py-3 max-w-xs truncate">{{ $customer->address ?? '-' }}</td>
                    <td class="px-6 py-3 flex items-center gap-2">
                        <a href="{{ route('customers.edit', $customer) }}"
                           class="text-blue-600 dark:text-blue-400 hover:underline text-xs">Edit</a>
                        <form method="POST" action="{{ route('customers.destroy', $customer) }}"
                              onsubmit="confirmDelete(this, 'Pelanggan ini akan dihapus permanen.'); return false;">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 dark:text-red-400 hover:underline text-xs">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-400">Belum ada pelanggan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
            {{ $customers->links() }}
        </div>
    </div>
</div>
@endsection
