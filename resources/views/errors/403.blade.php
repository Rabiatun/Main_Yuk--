@extends('layouts.app')

@section('title', 'Akses Dibatasi')

@section('content')
<div class="flex flex-col items-center justify-center py-24 text-center">
    <div class="text-8xl mb-6">🔒</div>
    <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mb-3">Akses Dibatasi</h1>
    <p class="text-gray-500 dark:text-gray-400 mb-8 max-w-md">
        Anda tidak memiliki izin untuk mengakses halaman ini. Halaman ini hanya dapat diakses oleh <span class="font-semibold text-green-600 dark:text-green-400">Super Admin</span>.
    </p>
    <a href="{{ route('dashboard') }}"
       class="bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-6 py-3 rounded-lg transition-colors">
        ← Kembali ke Dashboard
    </a>
</div>
@endsection
