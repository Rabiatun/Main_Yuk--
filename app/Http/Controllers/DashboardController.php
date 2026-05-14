<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Field;
use App\Models\Setting;

class DashboardController extends Controller
{
    public function index()
    {
        $setting        = Setting::first();
        $totalBookings  = Booking::count();
        $totalCustomers = Customer::count();
        $totalFields    = Field::count();
        $totalRevenue   = Booking::where('payment_status', 'paid')->sum('total_price');

        $recentBookings = Booking::with(['customer', 'field'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'setting', 'totalBookings', 'totalCustomers',
            'totalFields', 'totalRevenue', 'recentBookings'
        ));
    }
}
