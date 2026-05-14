<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Sport Center</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>

    <div class="auth-card">

        <div class="auth-header">
            <div class="icon">⚽</div>
            <h1>Sport Center</h1>
            <p>Masuk ke akun Anda</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email"
                       value="{{ old('email', request()->cookie('remembered_email')) }}"
                       placeholder="email@contoh.com" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <input type="password" id="password" name="password"
                           placeholder="••••••••" required>
                    <button type="button" onclick="togglePass('password', this)">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="form-row">
                <label class="remember">
                    <input type="checkbox" name="remember" value="1">
                    Ingat saya
                </label>
                <a href="{{ route('password.request') }}">Lupa sandi?</a>
            </div>

            <button type="submit" class="btn-primary">Masuk</button>
        </form>

    </div>

    <script>
        function togglePass(id, btn) {
            const input = document.getElementById(id);
            input.type = input.type === 'password' ? 'text' : 'password';
            btn.style.color = input.type === 'text' ? '#16a34a' : '';
        }
    </script>
</body>
</html>
