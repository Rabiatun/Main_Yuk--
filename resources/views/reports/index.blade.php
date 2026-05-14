@extends('layouts.app')

@section('title', 'Laporan Pendapatan')

@section('content')
<div class="py-6">
    {{-- Filter --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5 mb-6">
        <form method="GET" action="{{ route('reports.index') }}" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}"
                       class="px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                       class="px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Lapangan</label>
                <select name="field_id"
                        class="px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">Semua Lapangan</option>
                    @foreach($fields as $field)
                        <option value="{{ $field->id }}" {{ request('field_id') == $field->id ? 'selected' : '' }}>
                            {{ $field->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                    Filter
                </button>
                <a href="{{ route('reports.index') }}"
                   class="bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Total --}}
    <div class="bg-green-600 dark:bg-green-700 text-white rounded-xl shadow p-5 mb-6 flex items-center justify-between">
        <div>
            <p class="text-sm text-green-100">Total Pendapatan (Lunas)</p>
            <p class="text-3xl font-bold mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
        <svg class="w-12 h-12 text-green-400 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </div>

    {{-- Tabel --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left">#</th>
                        <th class="px-6 py-3 text-left">Tanggal</th>
                        <th class="px-6 py-3 text-left">Pelanggan</th>
                        <th class="px-6 py-3 text-left">Lapangan</th>
                        <th class="px-6 py-3 text-left">Jam</th>
                        <th class="px-6 py-3 text-left">Durasi</th>
                        <th class="px-6 py-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($bookings as $booking)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-3 text-gray-400">{{ $loop->iteration }}</td>
                        <td class="px-6 py-3">{{ $booking->booking_date->format('d/m/Y') }}</td>
                        <td class="px-6 py-3 font-medium">{{ $booking->customer->name }}</td>
                        <td class="px-6 py-3">{{ $booking->field->name }}</td>
                        <td class="px-6 py-3 whitespace-nowrap">{{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }}</td>
                        <td class="px-6 py-3">{{ $booking->duration_hours }} jam</td>
                        <td class="px-6 py-3 text-right font-semibold text-green-600 dark:text-green-400">
                            Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-400">Tidak ada data pendapatan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
            {{ $bookings->links() }}
        </div>
    </div>
</div>
@endsection
