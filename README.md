# Sport Center Booking System

Aplikasi web manajemen pemesanan lapangan olahraga berbasis Laravel 12. Dibangun untuk mengelola booking lapangan Futsal, Badminton, dan Voli secara real-time, lengkap dengan manajemen pelanggan, user, laporan pendapatan, dark mode, dan sistem role berbasis akses.

---

## Tech Stack

| Teknologi | Versi | Kegunaan |
|---|---|---|
| Laravel | 12 | PHP framework utama |
| PHP | 8.x | Bahasa pemrograman backend |
| MySQL | - | Database utama (port 3307) |
| Tailwind CSS | CDN | Styling & dark mode |
| Blade | - | Templating engine |
| JavaScript | Vanilla | Dark mode toggle & modal |
| Composer | - | Package manager PHP |

---

## Instalasi & Setup

```bash
# 1. Clone project
git clone <repo-url>
cd sport-center-booking

# 2. Install dependencies
composer install

# 3. Copy file environment
cp .env.example .env

# 4. Generate app key
php artisan key:generate

# 5. Sesuaikan konfigurasi database di .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3307
DB_DATABASE=db_futsal
DB_USERNAME=root
DB_PASSWORD=

# 6. Jalankan migration & seeder
php artisan migrate --seed

# 7. Jalankan server
php artisan serve
```

## Default Login

| Email | Password | Role |
|---|---|---|
| admin@sportcenter.com | password | Super Admin |

---

## Struktur Folder Penting

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── BookingController.php
│   │   ├── CustomerController.php
│   │   ├── DashboardController.php
│   │   ├── FieldController.php
│   │   ├── PasswordController.php
│   │   ├── ReportController.php
│   │   ├── SettingController.php
│   │   └── UserController.php
│   ├── Middleware/
│   │   └── AdminMiddleware.php
│   └── Requests/
│       ├── AdminRequest.php
│       └── LoginRequest.php
├── Models/
│   ├── Booking.php
│   ├── Customer.php
│   ├── Field.php
│   ├── Setting.php
│   └── User.php
database/
├── migrations/
├── seeders/
resources/
├── views/
│   ├── auth/
│   ├── bookings/
│   ├── customers/
│   ├── errors/
│   ├── fields/
│   ├── layouts/
│   ├── reports/
│   ├── settings/
│   └── users/
routes/
└── web.php
```

---

## Konsep Laravel yang Digunakan

### 1. Route

Semua URL diatur terpusat di `routes/web.php`. Menggunakan route resource untuk CRUD otomatis, route group untuk middleware, dan route custom untuk aksi khusus.

```php
// Route publik
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route dengan middleware auth
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('bookings', BookingController::class)->except(['edit', 'update']);
    Route::resource('fields', FieldController::class)->except(['show']);
    Route::resource('customers', CustomerController::class)->except(['show']);

    // Route custom untuk aksi spesifik
    Route::patch('/bookings/{booking}/payment', [BookingController::class, 'updatePayment'])->name('bookings.payment');
    Route::patch('/bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.status');
    Route::get('/bookings/check-availability', [BookingController::class, 'checkAvailability'])->name('bookings.availability');

    // Route admin only
    Route::middleware('admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    });

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});

// Route reset password
Route::get('/forgot-password', [PasswordController::class, 'requestForm'])->name('password.request');
Route::post('/forgot-password', [PasswordController::class, 'verifyEmail'])->name('password.verify');
Route::get('/reset-password', [PasswordController::class, 'resetForm'])->name('password.reset.form');
Route::post('/reset-password', [PasswordController::class, 'reset'])->name('password.reset');
```

---

### 2. View (Blade Templating)

Menggunakan Blade sebagai templating engine dengan layout utama `layouts/app.blade.php` yang diextend oleh semua halaman.

**Direktif Blade yang digunakan:**

```blade
{{-- Extend layout --}}
@extends('layouts.app')

{{-- Mendefinisikan section --}}
@section('title', 'Data Booking')
@section('content')
    ...
@endsection

{{-- Menampilkan section di layout --}}
@yield('title', 'Dashboard')
@yield('content')

{{-- Kondisional --}}
@if(auth()->user()->isSuperAdmin())
    ...
@endif

{{-- Loop --}}
@forelse($bookings as $booking)
    ...
@empty
    <p>Tidak ada data.</p>
@endforelse

{{-- CSRF token untuk form --}}
@csrf

{{-- Method spoofing untuk PUT/PATCH/DELETE --}}
@method('DELETE')
@method('PUT')

{{-- Menampilkan variabel --}}
{{ $booking->customer->name }}
{{ optional(\App\Models\Setting::first())->sport_center_name ?? 'Sport Center' }}
```

**Daftar View:**

| View | Fungsi |
|---|---|
| `layouts/app.blade.php` | Layout global (sidebar, topbar, dark mode, flash message) |
| `dashboard.blade.php` | Halaman utama statistik |
| `auth/login.blade.php` | Halaman login |
| `auth/forgot-password.blade.php` | Form lupa password |
| `auth/reset-password.blade.php` | Form reset password |
| `bookings/index.blade.php` | Daftar booking |
| `bookings/create.blade.php` | Form buat booking |
| `bookings/show.blade.php` | Detail booking |
| `fields/index.blade.php` | Daftar lapangan |
| `fields/create.blade.php` | Form tambah lapangan |
| `fields/edit.blade.php` | Form edit lapangan |
| `customers/index.blade.php` | Daftar pelanggan |
| `customers/create.blade.php` | Form tambah pelanggan |
| `customers/edit.blade.php` | Form edit pelanggan |
| `users/index.blade.php` | Daftar user |
| `users/create.blade.php` | Form tambah user |
| `users/edit.blade.php` | Form edit user |
| `reports/index.blade.php` | Laporan pendapatan |
| `settings/edit.blade.php` | Pengaturan aplikasi |
| `errors/403.blade.php` | Halaman akses dibatasi |

---

### 3. Model (Eloquent ORM)

Model merepresentasikan tabel database dan relasi antar tabel.

```php
// app/Models/User.php
class User extends Authenticatable
{
    protected $fillable = ['name', 'email', 'password', 'role'];
    protected $hidden   = ['password', 'remember_token'];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }
}
```

**Daftar Model:**

| Model | Tabel | Keterangan |
|---|---|---|
| `User` | `users` | Role `super_admin` dan `staff` |
| `Booking` | `bookings` | Data pemesanan lapangan |
| `Customer` | `customers` | Data pelanggan |
| `Field` | `fields` | Data lapangan olahraga |
| `Setting` | `settings` | Konfigurasi nama sport center |

---

### 4. Login & Autentikasi

Login menggunakan `Auth::attempt()` bawaan Laravel dengan session regenerate untuk keamanan.

```php
// app/Http/Controllers/AuthController.php
public function login(LoginRequest $request)
{
    $credentials = $request->validated();
    $remember    = $request->boolean('remember');

    if (Auth::attempt($credentials, $remember)) {
        $request->session()->regenerate(); // Cegah session fixation
        return redirect()->route('dashboard');
    }

    return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
}

public function logout(Request $request)
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
}
```

---

### 5. Session

Session digunakan untuk menyimpan state sementara antar request.

```php
// Flash message — hanya tersedia 1 request berikutnya
$request->session()->flash('success', 'Selamat datang, ' . Auth::user()->name . '!');

// Simpan data di session
Session::put('reset_email', $request->email);

// Cek apakah key ada di session
Session::has('reset_email');

// Ambil data dari session
$email = Session::get('reset_email');

// Hapus data dari session
Session::forget('reset_email');

// Tampilkan flash message di view
@if(session('success'))
    <div class="bg-green-100 ...">{{ session('success') }}</div>
@endif
```

Konfigurasi session di `.env`:
```
SESSION_DRIVER=database
SESSION_LIFETIME=120
```

---

### 6. Cookie

Cookie digunakan untuk menyimpan preferensi user yang perlu bertahan lebih lama dari session.

```php
// Buat cookie baru
Cookie::make('theme', $theme, 60 * 24 * 365);           // expire 1 tahun
Cookie::make('remembered_email', $email, 60 * 24 * 30); // expire 30 hari

// Hapus cookie
Cookie::forget('remembered_email');

// Ambil nilai cookie
$theme = Cookie::get('theme', 'light'); // default 'light'

// Attach cookie ke response
return redirect()->route('dashboard')->withCookie(
    Cookie::make('remembered_email', $request->email, 60 * 24 * 30)
);
```

Membaca cookie di JavaScript (dark mode tanpa flicker):
```javascript
(function () {
    if (document.cookie.includes('theme=dark')) {
        document.documentElement.classList.add('dark');
    }
})();
```

---

### 7. Fallback

Nilai cadangan jika data tidak tersedia agar aplikasi tidak error.

```php
// Fallback nilai null dengan operator ??
optional(\App\Models\Setting::first())->sport_center_name ?? 'Sport Center'

// Fallback buat data jika belum ada
Setting::firstOrCreate([], [
    'sport_center_name' => 'Sport Center',
    'address'           => '',
    'phone'             => '',
    'email'             => '',
]);

// Fallback cookie
$theme = Cookie::get('theme', 'light');

// Fallback session reset password
if (!Session::has('reset_email')) {
    return redirect()->route('password.request')
        ->with('error', 'Sesi tidak valid. Silakan ulangi dari awal.');
}
```

---

### 8. CRUD

Operasi Create, Read, Update, Delete tersedia di semua modul utama. Setiap controller menggabungkan 3 pendekatan query.

**Pembagian pendekatan per method:**

| Method | Pendekatan | Contoh |
|---|---|---|
| `index()` | Raw SQL | Daftar data dengan pagination |
| `store()` | Eloquent | Insert data baru |
| `edit()` | Query Builder | Ambil data untuk form edit |
| `update()` | Query Builder | Update data |
| `destroy()` | Raw SQL | Hapus data |

---

### 9. Controller

Controller menjadi penghubung antara Route, Model, dan View.

```php
// Contoh BookingController — menggabungkan 3 pendekatan
class BookingController extends Controller
{
    // RAW SQL
    public function index() { ... }

    // ELOQUENT
    public function store(Request $request) { ... }

    // QUERY BUILDER
    public function show($id) { ... }
    public function updatePayment(Request $request, $id) { ... }

    // RAW SQL
    public function destroy($id) { ... }
}
```

---

### 10. Middleware

Middleware menyaring request sebelum masuk ke controller.

```php
// app/Http/Middleware/AdminMiddleware.php
class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->isSuperAdmin()) {
            return response(view('errors.403'), 403);
        }
        return $next($request);
    }
}
```

Middleware yang aktif:
- `auth` — bawaan Laravel, cek apakah user sudah login
- `admin` — custom, cek apakah role adalah `super_admin`

---

### 11. Raw SQL

Menggunakan `DB` facade dengan query string dan parameter binding `?` untuk mencegah SQL injection.

```php
use Illuminate\Support\Facades\DB;

// SELECT dengan pagination
$items = DB::select("
    SELECT b.*, c.name AS customer_name, f.name AS field_name
    FROM bookings b
    JOIN customers c ON b.customer_id = c.id
    JOIN fields f ON b.field_id = f.id
    ORDER BY b.booking_date DESC
    LIMIT ? OFFSET ?
", [$perPage, $offset]);

// COUNT total data
$total = DB::select("SELECT COUNT(*) AS total FROM bookings")[0]->total;

// DELETE
DB::delete("DELETE FROM bookings WHERE id = ?", [$id]);
DB::delete("DELETE FROM customers WHERE id = ?", [$id]);
DB::delete("DELETE FROM fields WHERE id = ?", [$id]);
DB::delete("DELETE FROM users WHERE id = ?", [$id]);
```

---

### 12. Query Builder

Menggunakan `DB::table()` dengan method chaining. Lebih fleksibel dari Raw SQL, lebih ringan dari Eloquent.

```php
use Illuminate\Support\Facades\DB;

// SELECT dengan join
$booking = DB::table('bookings')
    ->join('customers', 'bookings.customer_id', '=', 'customers.id')
    ->join('fields', 'bookings.field_id', '=', 'fields.id')
    ->select('bookings.*', 'customers.name AS customer_name', 'fields.name AS field_name')
    ->where('bookings.id', $id)
    ->first();

// SELECT single row
$field = DB::table('fields')->where('id', $id)->first();

// UPDATE
DB::table('bookings')->where('id', $id)->update([
    'payment_status' => $request->payment_status,
    'updated_at'     => now(),
]);

DB::table('users')->where('id', $id)->update([
    'name'       => $data['name'],
    'email'      => $data['email'],
    'role'       => $data['role'],
    'updated_at' => now(),
]);

// SELECT dengan pagination bawaan
$customers = DB::table('customers')->orderBy('name')->paginate(10);
```

---

### 13. Migrations

Migration adalah versi kontrol untuk struktur database.

```bash
# Jalankan semua migration
php artisan migrate

# Jalankan migration + seeder
php artisan migrate --seed

# Reset dan jalankan ulang semua
php artisan migrate:fresh --seed
```

**Daftar Migration:**

| File | Tabel yang Dibuat |
|---|---|
| `0001_01_01_000000_create_users_table.php` | `users`, `password_reset_tokens`, `sessions` |
| `0001_01_01_000001_create_cache_table.php` | `cache` |
| `0001_01_01_000002_create_jobs_table.php` | `jobs` |
| `2024_01_01_000001_create_settings_table.php` | `settings` |
| `2024_01_01_000002_create_customers_table.php` | `customers` |
| `2024_01_01_000003_create_fields_table.php` | `fields` |
| `2024_01_01_000004_create_bookings_table.php` | `bookings` |
| `2024_01_01_000005_add_role_to_users_table.php` | Tambah kolom `role` ke `users` |
| `2024_01_02_000001_update_role_admin_to_super_admin.php` | Update nilai role `admin` → `super_admin` |

Contoh struktur migration:
```php
Schema::create('bookings', function (Blueprint $table) {
    $table->id();
    $table->foreignId('customer_id')->constrained();
    $table->foreignId('field_id')->constrained();
    $table->date('booking_date');
    $table->time('start_time');
    $table->time('end_time');
    $table->decimal('duration_hours', 4, 2);
    $table->decimal('total_price', 12, 2);
    $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('confirmed');
    $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');
    $table->text('notes')->nullable();
    $table->timestamps();
});
```

---

### 14. Konfigurasi Database

Diatur di `.env` dan `config/database.php`.

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3307
DB_DATABASE=db_futsal
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_STORE=database
QUEUE_CONNECTION=database
```

`config/database.php` membaca nilai dari `.env` via helper `env()`:
```php
'mysql' => [
    'driver'   => 'mysql',
    'host'     => env('DB_HOST', '127.0.0.1'),
    'port'     => env('DB_PORT', '3306'),
    'database' => env('DB_DATABASE', 'laravel'),
    'username' => env('DB_USERNAME', 'root'),
    'password' => env('DB_PASSWORD', ''),
],
```

---

### 15. Validation

Validasi input user sebelum data diproses atau disimpan ke database.

**Form Request:**
```php
// app/Http/Requests/LoginRequest.php
class LoginRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'email'    => 'required|email',
            'password' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ];
    }
}
```

**Validasi di Controller:**
```php
// Booking — validasi lengkap + cek bentrok jadwal
$data = $request->validate([
    'customer_id'  => 'required|exists:customers,id',
    'field_id'     => 'required|exists:fields,id',
    'booking_date' => 'required|date|after_or_equal:today',
    'start_time'   => 'required',
    'end_time'     => 'required|after:start_time',
    'notes'        => 'nullable|string',
]);

// User — validasi password dengan konfirmasi
$data = $request->validate([
    'name'     => 'required|string|max:255',
    'email'    => 'required|email|unique:users',
    'password' => 'required|min:6|confirmed',
    'role'     => 'required|in:super_admin,staff',
]);

// Field — validasi enum
$data = $request->validate([
    'type'           => 'required|in:Futsal,Badminton,Voli',
    'price_per_hour' => 'required|numeric|min:0',
]);
```

Menampilkan error validasi di view:
```blade
@if($errors->any())
    <div class="bg-red-100 ...">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
```

---

## Security

- CSRF protection — `@csrf` di semua form, meta token di `<head>`
- Password hashing — `Hash::make()` saat simpan/update password
- Session regenerate — setelah login dan logout
- Middleware auth — semua route protected kecuali login
- Middleware admin — area admin hanya untuk `super_admin`
- Self-delete protection — user tidak bisa hapus akun sendiri
- SQL injection prevention — semua query pakai parameter binding `?`

## Fitur Tambahan

- Dark mode toggle (cookie, tanpa flicker saat load)
- Modal konfirmasi hapus data (JavaScript vanilla, bisa tutup dengan ESC)
- Pagination di semua halaman list
- Flash message success/error otomatis
- Halaman 403 custom untuk akses ditolak
- Staff tetap lihat menu admin di sidebar tapi kena 403 kalau diklik
