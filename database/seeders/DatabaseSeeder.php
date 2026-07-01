<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@sportcenter.com',
            'password' => Hash::make('password'),
            'role'     => 'super_admin',
        ]);

        Setting::create([
            'sport_center_name' => 'Sport Center Booking System',
            'address'           => 'Jl. Olahraga No. 1, Jakarta',
            'phone'             => '021-12345678',
            'email'             => 'info@sportcenter.com',
        ]);
    }
}
