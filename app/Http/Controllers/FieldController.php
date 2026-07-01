<?php

namespace App\Http\Controllers;

use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class FieldController extends Controller
{
    // RAW SQL — mengambil daftar lapangan
    public function index()
    {
        $perPage = 10;
        $page    = request()->get('page', 1);
        $offset  = ($page - 1) * $perPage;

        $items  = DB::select("
            SELECT * FROM fields ORDER BY name ASC LIMIT ? OFFSET ?
        ", [$perPage, $offset]);

        $total  = DB::select("SELECT COUNT(*) AS total FROM fields")[0]->total;
        $fields = new LengthAwarePaginator($items, $total, $perPage, $page, [
            'path' => request()->url(),
        ]);

        return view('fields.index', compact('fields'));
    }

    public function create()
    {
        return view('fields.create');
    }

    // ELOQUENT — tambah lapangan baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'type'           => 'required|in:Futsal,Badminton,Voli',
            'price_per_hour' => 'required|numeric|min:0',
            'description'    => 'nullable|string',
            'is_active'      => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        Field::create($data);

        return redirect()->route('fields.index')->with('success', 'Lapangan berhasil ditambahkan.');
    }

    // QUERY BUILDER — ambil data untuk form edit
    public function edit($id)
    {
        $field = DB::table('fields')->where('id', $id)->first();
        if (!$field) abort(404);

        return view('fields.edit', compact('field'));
    }

    // QUERY BUILDER — update data lapangan
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'type'           => 'required|in:Futsal,Badminton,Voli',
            'price_per_hour' => 'required|numeric|min:0',
            'description'    => 'nullable|string',
        ]);

        DB::table('fields')->where('id', $id)->update([
            'name'           => $data['name'],
            'type'           => $data['type'],
            'price_per_hour' => $data['price_per_hour'],
            'description'    => $data['description'] ?? null,
            'is_active'      => $request->boolean('is_active') ? 1 : 0,
            'updated_at'     => now(),
        ]);

        return redirect()->route('fields.index')->with('success', 'Lapangan berhasil diperbarui.');
    }

    // RAW SQL — hapus lapangan
    public function destroy($id)
    {
        DB::delete("DELETE FROM fields WHERE id = ?", [$id]);
        return redirect()->route('fields.index')->with('success', 'Lapangan berhasil dihapus.');
    }
}
