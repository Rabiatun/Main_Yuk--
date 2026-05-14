<?php

namespace App\Http\Controllers;

use App\Models\Field;
use Illuminate\Http\Request;

class FieldController extends Controller
{
    public function index()
    {
        $fields = Field::orderBy('name')->paginate(10);
        return view('fields.index', compact('fields'));
    }

    public function create()
    {
        return view('fields.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'type'          => 'required|in:Futsal,Badminton,Voli',
            'price_per_hour'=> 'required|numeric|min:0',
            'description'   => 'nullable|string',
            'is_active'     => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        Field::create($data);

        return redirect()->route('fields.index')->with('success', 'Lapangan berhasil ditambahkan.');
    }

    public function edit(Field $field)
    {
        return view('fields.edit', compact('field'));
    }

    public function update(Request $request, Field $field)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'type'          => 'required|in:Futsal,Badminton,Voli',
            'price_per_hour'=> 'required|numeric|min:0',
            'description'   => 'nullable|string',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $field->update($data);

        return redirect()->route('fields.index')->with('success', 'Lapangan berhasil diperbarui.');
    }

    public function destroy(Field $field)
    {
        $field->delete();
        return redirect()->route('fields.index')->with('success', 'Lapangan berhasil dihapus.');
    }
}
