<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        $setting = Setting::firstOrCreate([], [
            'sport_center_name' => 'Sport Center',
            'address'           => '',
            'phone'             => '',
            'email'             => '',
        ]);

        return view('settings.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'sport_center_name' => 'required|string|max:255',
            'address'           => 'nullable|string',
            'phone'             => 'nullable|string|max:20',
            'email'             => 'nullable|email|max:255',
        ]);

        Setting::first()->update($data);

        return redirect()->route('settings.edit')->with('success', 'Pengaturan berhasil disimpan.');
    }
}
