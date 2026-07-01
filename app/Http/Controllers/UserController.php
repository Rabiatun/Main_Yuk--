<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Pagination\LengthAwarePaginator;

class UserController extends Controller
{
    // RAW SQL — mengambil daftar user
    public function index()
    {
        $perPage = 10;
        $page    = request()->get('page', 1);
        $offset  = ($page - 1) * $perPage;

        $items = DB::select("
            SELECT * FROM users ORDER BY name ASC LIMIT ? OFFSET ?
        ", [$perPage, $offset]);

        $total = DB::select("SELECT COUNT(*) AS total FROM users")[0]->total;
        $users = new LengthAwarePaginator($items, $total, $perPage, $page, [
            'path' => request()->url(),
        ]);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    // ELOQUENT — tambah user baru dengan hash password
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role'     => 'required|in:super_admin,staff',
        ]);

        $data['password'] = Hash::make($data['password']);
        User::create($data);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    // QUERY BUILDER — ambil data untuk form edit
    public function edit($id)
    {
        $user = DB::table('users')->where('id', $id)->first();
        if (!$user) abort(404);

        return view('users.edit', compact('user'));
    }

    // QUERY BUILDER — update data user
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role'  => 'required|in:super_admin,staff',
        ]);

        $update = [
            'name'       => $data['name'],
            'email'      => $data['email'],
            'role'       => $data['role'],
            'updated_at' => now(),
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6|confirmed']);
            $update['password'] = Hash::make($request->password);
        }

        DB::table('users')->where('id', $id)->update($update);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    // RAW SQL — hapus user
    public function destroy($id)
    {
        if ((int) $id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        DB::delete("DELETE FROM users WHERE id = ?", [$id]);
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
