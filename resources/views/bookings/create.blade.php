@extends('layouts.app')

@section('title', 'Buat Booking')

@section('content')
<div class="py-6 max-w-2xl">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
        <form method="POST" action="{{ route('bookings.store') }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pelanggan <span class="text-red-500">*</span></label>
                    <select name="customer_id" required
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">-- Pilih Pelanggan --</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Lapangan <span class="text-red-500">*</span></label>
                    <select name="field_id" id="field_id" required
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">-- Pilih Lapangan --</option>
                        @foreach($fields as $field)
                            <option value="{{ $field->id }}"
                                    data-price="{{ $field->price_per_hour }}"
                                    {{ old('field_id') == $field->id ? 'selected' : '' }}>
                                {{ $field->name }} ({{ $field->type }}) — Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}/jam
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Booking <span class="text-red-500">*</span></label>
                <input type="date" name="booking_date" id="booking_date"
                       value="{{ old('booking_date') }}"
                       min="{{ date('Y-m-d') }}" required
                       class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            {{-- Slot yang sudah terisi --}}
            <div id="availability-info" class="hidden bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-300 dark:border-yellow-700 rounded-lg p-3 text-sm text-yellow-800 dark:text-yellow-300">
                <p class="font-medium mb-1">Jam yang sudah dibooking:</p>
                <ul id="booked-slots" class="list-disc list-inside space-y-0.5"></ul>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jam Mulai <span class="text-red-500">*</span></label>
                    <input type="time" name="start_time" id="start_time"
                           value="{{ old('start_time') }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jam Selesai <span class="text-red-500">*</span></label>
                    <input type="time" name="end_time" id="end_time"
                           value="{{ old('end_time') }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
            </div>

            {{-- Estimasi harga --}}
            <div id="price-estimate" class="hidden bg-green-50 dark:bg-green-900/30 border border-green-300 dark:border-green-700 rounded-lg p-3 text-sm text-green-800 dark:text-green-300">
                Estimasi total: <span id="price-value" class="font-bold"></span>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catatan</label>
                <textarea name="notes" rows="2"
                          class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">{{ old('notes') }}</textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2.5 rounded-lg transition-colors">
                    Buat Booking
                </button>
                <a href="{{ route('bookings.index') }}"
                   class="bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-semibold px-6 py-2.5 rounded-lg transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    const fieldSelect   = document.getElementById('field_id');
    const dateInput     = document.getElementById('booking_date');
    const startInput    = document.getElementById('start_time');
    const endInput      = document.getElementById('end_time');
    const availInfo     = document.getElementById('availability-info');
    const bookedSlots   = document.getElementById('booked-slots');
    const priceEstimate = document.getElementById('price-estimate');
    const priceValue    = document.getElementById('price-value');

    function checkAvailability() {
        const fieldId = fieldSelect.value;
        const date    = dateInput.value;
        if (!fieldId || !date) return;

        fetch(`{{ route('bookings.availability') }}?field_id=${fieldId}&booking_date=${date}`)
            .then(r => r.json())
            .then(data => {
                bookedSlots.innerHTML = '';
                if (data.length > 0) {
                    availInfo.classList.remove('hidden');
                    data.forEach(b => {
                        const li = document.createElement('li');
                        li.textContent = b.start_time.substring(0,5) + ' - ' + b.end_time.substring(0,5);
                        bookedSlots.appendChild(li);
                    });
                } else {
                    availInfo.classList.add('hidden');
                }
            });
    }

    function calcPrice() {
        const start = startInput.value;
        const end   = endInput.value;
        const opt   = fieldSelect.options[fieldSelect.selectedIndex];
        const price = parseFloat(opt?.dataset?.price || 0);

        if (start && end && price > 0) {
            const diff = (new Date('1970-01-01T' + end) - new Date('1970-01-01T' + start)) / 3600000;
            if (diff > 0) {
                priceEstimate.classList.remove('hidden');
                priceValue.textContent = 'Rp ' + (diff * price).toLocaleString('id-ID');
                return;
            }
        }
        priceEstimate.classList.add('hidden');
    }

    fieldSelect.addEventListener('change', () => { checkAvailability(); calcPrice(); });
    dateInput.addEventListener('change', checkAvailability);
    startInput.addEventListener('change', calcPrice);
    endInput.addEventListener('change', calcPrice);
</script>
@endsection
