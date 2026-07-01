<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class BookingController extends Controller
{
    // RAW SQL — mengambil daftar booking dengan JOIN
    public function index()
    {
        $perPage = 10;
        $page    = request()->get('page', 1);
        $offset  = ($page - 1) * $perPage;

        $items = DB::select("
            SELECT b.*, c.name AS customer_name, f.name AS field_name
            FROM bookings b
            JOIN customers c ON b.customer_id = c.id
            JOIN fields f ON b.field_id = f.id
            ORDER BY b.booking_date DESC
            LIMIT ? OFFSET ?
        ", [$perPage, $offset]);

        $total    = DB::select("SELECT COUNT(*) AS total FROM bookings")[0]->total;
        $bookings = new LengthAwarePaginator($items, $total, $perPage, $page, [
            'path' => request()->url(),
        ]);

        return view('bookings.index', compact('bookings'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $fields    = Field::where('is_active', true)->orderBy('name')->get();
        return view('bookings.create', compact('customers', 'fields'));
    }

    // ELOQUENT — store dengan conflict check
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

        $field         = Field::findOrFail($data['field_id']);
        $durationHours = (strtotime($data['end_time']) - strtotime($data['start_time'])) / 3600;
        $totalPrice    = $durationHours * $field->price_per_hour;

        Booking::create(array_merge($data, [
            'duration_hours' => $durationHours,
            'total_price'    => $totalPrice,
            'status'         => 'confirmed',
            'payment_status' => 'unpaid',
        ]));

        return redirect()->route('bookings.index')->with('success', 'Booking berhasil dibuat.');
    }

    // QUERY BUILDER — ambil detail booking dengan join
    public function show($id)
    {
        $booking = DB::table('bookings')
            ->join('customers', 'bookings.customer_id', '=', 'customers.id')
            ->join('fields', 'bookings.field_id', '=', 'fields.id')
            ->select(
                'bookings.*',
                'customers.name AS customer_name',
                'fields.name AS field_name'
            )
            ->where('bookings.id', $id)
            ->first();

        if (!$booking) abort(404);

        return view('bookings.show', compact('booking'));
    }

    // QUERY BUILDER — update status pembayaran
    public function updatePayment(Request $request, $id)
    {
        $request->validate(['payment_status' => 'required|in:unpaid,paid']);

        DB::table('bookings')->where('id', $id)->update([
            'payment_status' => $request->payment_status,
            'updated_at'     => now(),
        ]);

        return back()->with('success', 'Status pembayaran diperbarui.');
    }

    // QUERY BUILDER — update status booking
    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:pending,confirmed,cancelled']);

        DB::table('bookings')->where('id', $id)->update([
            'status'     => $request->status,
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Status booking diperbarui.');
    }

    // RAW SQL — hapus booking
    public function destroy($id)
    {
        DB::delete("DELETE FROM bookings WHERE id = ?", [$id]);
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
