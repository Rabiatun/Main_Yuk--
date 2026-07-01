<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class CustomerController extends Controller
{
    // RAW SQL — mengambil daftar pelanggan
    public function index()
    {
        $perPage = 10;
        $page    = request()->get('page', 1);
        $offset  = ($page - 1) * $perPage;

        $items = DB::select("
            SELECT * FROM customers ORDER BY name ASC LIMIT ? OFFSET ?
        ", [$perPage, $offset]);

        $total     = DB::select("SELECT COUNT(*) AS total FROM customers")[0]->total;
        $customers = new LengthAwarePaginator($items, $total, $perPage, $page, [
            'path' => request()->url(),
        ]);

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    // ELOQUENT — tambah pelanggan baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:20',
            'email'   => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        Customer::create($data);
        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    // QUERY BUILDER — ambil data untuk form edit
    public function edit($id)
    {
        $customer = DB::table('customers')->where('id', $id)->first();
        if (!$customer) abort(404);

        return view('customers.edit', compact('customer'));
    }

    // QUERY BUILDER — update data pelanggan
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:20',
            'email'   => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        DB::table('customers')->where('id', $id)->update([
            'name'       => $data['name'],
            'phone'      => $data['phone'] ?? null,
            'email'      => $data['email'] ?? null,
            'address'    => $data['address'] ?? null,
            'updated_at' => now(),
        ]);

        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil diperbarui.');
    }

    // RAW SQL — hapus pelanggan
    public function destroy($id)
    {
        DB::delete("DELETE FROM customers WHERE id = ?", [$id]);
        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil dihapus.');
    }
}
