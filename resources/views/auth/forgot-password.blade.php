<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Sandi — Sport Center</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>

    <div class="auth-card">

        <div class="auth-header">
            <div class="icon">🔑</div>
            <h1>Lupa Sandi</h1>
            <p>Masukkan email akun Anda</p>
        </div>

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('password.verify') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email Terdaftar</label>
                <input type="email" id="email" name="email"
                       value="{{ old('email') }}"
                       placeholder="email@contoh.com" required autofocus>
            </div>

            <button type="submit" class="btn-primary">Verifikasi Email</button>
        </form>

        <div class="auth-footer">
            <a href="{{ route('login') }}">← Kembali ke Login</a>
        </div>

    </div>

</body>
</html>
