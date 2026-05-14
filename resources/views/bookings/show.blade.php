@extends('layouts.app')

@section('title', 'Detail Booking #' . $booking->id)

@section('content')
<div class="py-6 max-w-2xl">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 space-y-6">

        {{-- Info utama --}}
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500 dark:text-gray-400">Pelanggan</p>
                <p class="font-semibold text-gray-800 dark:text-white">{{ $booking->customer->name }}</p>
                <p class="text-gray-500 text-xs">{{ $booking->customer->phone }}</p>
            </div>
            <div>
                <p class="text-gray-500 dark:text-gray-400">Lapangan</p>
                <p class="font-semibold text-gray-800 dark:text-white">{{ $booking->field->name }}</p>
                <p class="text-gray-500 text-xs">{{ $booking->field->type }}</p>
            </div>
            <div>
                <p class="text-gray-500 dark:text-gray-400">Tanggal</p>
                <p class="font-semibold text-gray-800 dark:text-white">{{ $booking->booking_date->format('d F Y') }}</p>
            </div>
            <div>
                <p class="text-gray-500 dark:text-gray-400">Jam</p>
                <p class="font-semibold text-gray-800 dark:text-white">
                    {{ substr($booking->start_time, 0, 5) }} – {{ substr($booking->end_time, 0, 5) }}
                    ({{ $booking->duration_hours }} jam)
                </p>
            </div>
            <div>
                <p class="text-gray-500 dark:text-gray-400">Total Harga</p>
                <p class="font-bold text-xl text-green-600 dark:text-green-400">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
            </div>
            @if($booking->notes)
            <div>
                <p class="text-gray-500 dark:text-gray-400">Catatan</p>
                <p class="text-gray-800 dark:text-white">{{ $booking->notes }}</p>
            </div>
            @endif
        </div>

        <hr class="border-gray-200 dark:border-gray-700">

        {{-- Update status --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status Booking</p>
                <form method="POST" action="{{ route('bookings.status', $booking) }}" class="flex gap-2">
                    @csrf @method('PATCH')
                    <select name="status"
                            class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                        Update
                    </button>
                </form>
            </div>

            <div>
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status Pembayaran</p>
                <form method="POST" action="{{ route('bookings.payment', $booking) }}" class="flex gap-2">
                    @csrf @method('PATCH')
                    <select name="payment_status"
                            class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="unpaid" {{ $booking->payment_status === 'unpaid' ? 'selected' : '' }}>Belum Bayar</option>
                        <option value="paid" {{ $booking->payment_status === 'paid' ? 'selected' : '' }}>Lunas</option>
                    </select>
                    <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                        Update
                    </button>
                </form>
            </div>
        </div>

        <div class="flex gap-3 pt-2">
            <a href="{{ route('bookings.index') }}"
               class="bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-semibold px-6 py-2.5 rounded-lg transition-colors text-sm">
                ← Kembali
            </a>
            <form method="POST" action="{{ route('bookings.destroy', $booking) }}"
                  onsubmit="return confirm('Hapus booking ini?')">
                @csrf @method('DELETE')
                <button type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2.5 rounded-lg transition-colors text-sm">
                    Hapus Booking
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
