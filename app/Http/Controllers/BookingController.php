<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Field;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['customer', 'field'])
            ->orderByDesc('booking_date')
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $fields    = Field::where('is_active', true)->orderBy('name')->get();
        return view('bookings.create', compact('customers', 'fields'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id'  => 'required|exists:customers,id',
            'field_id'     => 'required|exists:fields,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time'   => 'required',
            'end_time'     => 'required|after:start_time',
            'notes'        => 'nullable|string',
        ]);

        // Cek ketersediaan lapangan (tidak bentrok)
        $conflict = Booking::where('field_id', $data['field_id'])
            ->where('booking_date', $data['booking_date'])
            ->where('status', '!=', 'cancelled')
            ->where(function ($q) use ($data) {
                $q->whereBetween('start_time', [$data['start_time'], $data['end_time']])
                  ->orWhereBetween('end_time', [$data['start_time'], $data['end_time']])
                  ->orWhere(function ($q2) use ($data) {
                      $q2->where('start_time', '<=', $data['start_time'])
                         ->where('end_time', '>=', $data['end_time']);
                  });
            })->exists();

        if ($conflict) {
            return back()->withInput()->with('error', 'Lapangan sudah dibooking pada jam tersebut. Pilih jam lain.');
        }

        $field          = Field::findOrFail($data['field_id']);
        $start          = strtotime($data['start_time']);
        $end            = strtotime($data['end_time']);
        $durationHours  = ($end - $start) / 3600;
        $totalPrice     = $durationHours * $field->price_per_hour;

        Booking::create(array_merge($data, [
            'duration_hours' => $durationHours,
            'total_price'    => $totalPrice,
            'status'         => 'confirmed',
            'payment_status' => 'unpaid',
        ]));

        return redirect()->route('bookings.index')->with('success', 'Booking berhasil dibuat.');
    }

    public function show(Booking $booking)
    {
        $booking->load(['customer', 'field']);
        return view('bookings.show', compact('booking'));
    }

    public function updatePayment(Request $request, Booking $booking)
    {
        $request->validate(['payment_status' => 'required|in:unpaid,paid']);
        $booking->update(['payment_status' => $request->payment_status]);
        return back()->with('success', 'Status pembayaran diperbarui.');
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate(['status' => 'required|in:pending,confirmed,cancelled']);
        $booking->update(['status' => $request->status]);
        return back()->with('success', 'Status booking diperbarui.');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('bookings.index')->with('success', 'Booking berhasil dihapus.');
    }

    public function checkAvailability(Request $request)
    {
        $request->validate([
            'field_id'     => 'required|exists:fields,id',
            'booking_date' => 'required|date',
        ]);

        $bookings = Booking::where('field_id', $request->field_id)
            ->where('booking_date', $request->booking_date)
            ->where('status', '!=', 'cancelled')
            ->get(['start_time', 'end_time']);

        return response()->json($bookings);
    }
}
