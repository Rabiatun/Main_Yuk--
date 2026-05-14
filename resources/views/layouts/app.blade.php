<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — {{ optional(\App\Models\Setting::first())->sport_center_name ?? 'Sport Center' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class' }
    </script>
    <script>
        // Terapkan tema dari cookie sebelum halaman render (cegah flicker)
        (function () {
            if (document.cookie.includes('theme=dark')) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100 min-h-screen flex">

    {{-- Sidebar --}}
    <aside id="sidebar" class="w-64 bg-green-800 dark:bg-gray-800 text-white flex flex-col min-h-screen fixed top-0 left-0 z-30 transition-transform duration-300">
        {{-- Logo --}}
        <div class="px-6 py-5 border-b border-green-700 dark:border-gray-700">
            <h1 class="text-lg font-bold leading-tight">
                ⚽ {{ optional(\App\Models\Setting::first())->sport_center_name ?? 'Sport Center' }}
            </h1>
            <p class="text-xs text-green-300 dark:text-gray-400 mt-1">Booking System</p>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-green-600 dark:bg-gray-600' : 'hover:bg-green-700 dark:hover:bg-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>

            <a href="{{ route('bookings.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('bookings.*') ? 'bg-green-600 dark:bg-gray-600' : 'hover:bg-green-700 dark:hover:bg-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Booking
            </a>

            <a href="{{ route('fields.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('fields.*') ? 'bg-green-600 dark:bg-gray-600' : 'hover:bg-green-700 dark:hover:bg-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                Lapangan
            </a>

            <a href="{{ route('customers.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('customers.*') ? 'bg-green-600 dark:bg-gray-600' : 'hover:bg-green-700 dark:hover:bg-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Pelanggan
            </a>

            <a href="{{ route('reports.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('reports.*') ? 'bg-green-600 dark:bg-gray-600' : 'hover:bg-green-700 dark:hover:bg-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Laporan
            </a>

            @if(auth()->user()->isAdmin())
            <div class="pt-3 mt-3 border-t border-green-700 dark:border-gray-700">
                <p class="px-3 text-xs text-green-400 dark:text-gray-500 uppercase tracking-wider mb-2">Admin</p>
                <a href="{{ route('users.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('users.*') ? 'bg-green-600 dark:bg-gray-600' : 'hover:bg-green-700 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Manajemen User
                </a>
                <a href="{{ route('settings.edit') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('settings.*') ? 'bg-green-600 dark:bg-gray-600' : 'hover:bg-green-700 dark:hover:bg-gray-700' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Pengaturan
                </a>
            </div>
            @endif
        </nav>

        {{-- User info --}}
        <div class="px-4 py-4 border-t border-green-700 dark:border-gray-700">
            <p class="text-sm font-medium truncate">{{ auth()->user()->name }}</p>
            <p class="text-xs text-green-300 dark:text-gray-400 capitalize">{{ auth()->user()->role }}</p>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrfJudul Presentasi
                Sport Center Booking System Implementasi Konsep Dasar Laravel — Route, Controller, Request, Response, Cookie & Session
                
                Slide 1: Judul
                Sport Center Booking System
                Dibangun dengan Laravel 12 + Tailwind CSS
                Nama Kamu | Nama Universitas/Kelas
                Slide 2: Agenda
                Pengenalan Proyek
                Setup & Konfigurasi
                Arsitektur: Route & Controller
                Interaksi User: Request & Response
                Manajemen State: Cookie & Session
                Live DemoJudul Presentasi
                Sport Center Booking System Implementasi Konsep Dasar Laravel — Route, Controller, Request, Response, Cookie & Session
                
                Slide 1: Judul
                Sport Center Booking System
                Dibangun dengan Laravel 12 + Tailwind CSS
                Nama Kamu | Nama Universitas/Kelas
                Slide 2: Agenda
                Pengenalan Proyek
                Setup & Konfigurasi
                Arsitektur: Route & Controller
                Interaksi User: Request & Response
                Manajemen State: Cookie & Session
                Live Demo
                Tanya Jawab
                Slide 3: Latar Belakang
                "Proyek ini adalah sistem pemesanan lapangan olahraga berbasis web. Pengelola sport center sering kesulitan mencatat booking secara manual — bentrok jadwal, pembayaran tidak tercatat, dan tidak ada rekap pendapatan."
                
                Solusi: Aplikasi web yang bisa mengelola booking lapangan Futsal, Badminton, dan Voli secara real-time, lengkap dengan manajemen pelanggan, user, dan laporan pendapatan.
                
                Slide 4: Kenapa Laravel?
                MVC — Memisahkan logika (Controller), data (Model), dan tampilan (View/Blade)
                Routing terpusat — Semua URL diatur di routes/web.php
                Blade Templating — Reuse layout dengan @extends dan @yield
                Keamanan bawaan — CSRF protection, password hashing, middleware auth
                Eloquent ORM — Query database tanpa tulis SQL manual
                Tanya Jawab
                Slide 3: Latar Belakang
                "Proyek ini adalah sistem pemesanan lapangan olahraga berbasis web. Pengelola sport center sering kesulitan mencatat booking secara manual — bentrok jadwal, pembayaran tidak tercatat, dan tidak ada rekap pendapatan."
                
                Solusi: Aplikasi web yang bisa mengelola booking lapangan Futsal, Badminton, dan Voli secara real-time, lengkap dengan manajemen pelanggan, user, dan laporan pendapatan.
                
                Slide 4: Kenapa Laravel?
                MVC — Memisahkan logika (Controller), data (Model), dan tampilan (View/Blade)
                Routing terpusat — Semua URL diatur di routes/web.php
                Blade Templating — Reuse layout dengan @extends dan @yield
                Keamanan bawaan — CSRF protection, password hashing, middleware auth
                Eloquent ORM — Query database tanpa tulis SQL manual
                <button type="submit" class="text-xs text-green-300 dark:text-gray-400 hover:text-white">Logout →</button>
            </form>
        </div>
    </aside>

    {{-- Main content --}}
    <div class="flex-1 ml-64 flex flex-col min-h-screen">
        {{-- Topbar --}}
        <header class="bg-white dark:bg-gray-800 shadow-sm px-6 py-4 flex items-center justify-between sticky top-0 z-20">
            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">@yield('title', 'Dashboard')</h2>
            <div class="flex items-center gap-3">
                {{-- Theme toggle --}}
                <button type="button" id="theme-toggle" onclick="toggleTheme()"
                        class="relative inline-flex items-center w-12 h-6 rounded-full transition-colors duration-300 focus:outline-none"
                        title="Toggle dark mode">
                    <span id="icon-sun" class="absolute left-1 text-xs transition-opacity duration-300">☀️</span>
                    <span id="icon-moon" class="absolute right-1 text-xs transition-opacity duration-300">🌙</span>
                    <span id="toggle-knob" class="inline-block w-4 h-4 bg-white rounded-full shadow transform transition-transform duration-300"></span>
                </button>
            </div>
        </header>

        {{-- Flash messages --}}
        <div class="px-6 pt-4">
            @if(session('success'))
                <div class="bg-green-100 dark:bg-green-900 border border-green-400 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg mb-4 flex items-center justify-between">
                    <span>{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-green-600 dark:text-green-300 font-bold ml-4">×</button>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 dark:bg-red-900 border border-red-400 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg mb-4 flex items-center justify-between">
                    <span>{{ session('error') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-red-600 dark:text-red-300 font-bold ml-4">×</button>
                </div>
            @endif
            @if($errors->any())
                <div class="bg-red-100 dark:bg-red-900 border border-red-400 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg mb-4">
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        {{-- Page content --}}
        <main class="flex-1 px-6 pb-8">
            @yield('content')
        </main>

        <footer class="px-6 py-4 text-center text-xs text-gray-400 dark:text-gray-600 border-t border-gray-200 dark:border-gray-700">
            &copy; {{ date('Y') }} {{ optional(\App\Models\Setting::first())->sport_center_name ?? 'Sport Center' }}. All rights reserved.
        </footer>
    </div>

    <script>
        const toggle = document.getElementById('theme-toggle');
        const knob   = document.getElementById('toggle-knob');
        const sun    = document.getElementById('icon-sun');
        const moon   = document.getElementById('icon-moon');

        function applyToggleUI(isDark) {
            toggle.style.backgroundColor = isDark ? '#22c55e' : '#d1d5db';
            knob.style.transform = isDark ? 'translateX(1.5rem)' : 'translateX(0.25rem)';
            sun.style.opacity  = isDark ? '1' : '0';
            moon.style.opacity = isDark ? '0' : '1';
        }

        // Set initial state dari cookie
        applyToggleUI(document.cookie.includes('theme=dark'));

        function toggleTheme() {
            const isDark = document.documentElement.classList.toggle('dark');
            const expires = new Date(Date.now() + 365 * 24 * 60 * 60 * 1000).toUTCString();
            document.cookie = `theme=${isDark ? 'dark' : 'light'}; expires=${expires}; path=/`;
            applyToggleUI(isDark);
        }
    </script>
</body>
</html>
