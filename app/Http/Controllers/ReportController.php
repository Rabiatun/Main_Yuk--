<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['customer', 'field'])
            ->where('payment_status', 'paid');

        if ($request->filled('start_date')) {
            $query->where('booking_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('booking_date', '<=', $request->end_date);
        }

        if ($request->filled('field_id')) {
            $query->where('field_id', $request->field_id);
        }

        $bookings     = $query->orderByDesc('booking_date')->paginate(15)->withQueryString();
        $totalRevenue = $query->sum('total_price');

        $fields = \App\Models\Field::orderBy('name')->get();

        return view('reports.index', compact('bookings', 'totalRevenue', 'fields'));
    }
}
