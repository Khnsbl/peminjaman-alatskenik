<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SiPeminjam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #0f1117;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Background glow decorations */
        body::before {
            content: '';
            position: fixed;
            top: -120px; left: -120px;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(99,102,241,0.15) 0%, transparent 70%);
            pointer-events: none;
        }
        body::after {
            content: '';
            position: fixed;
            bottom: -120px; right: -120px;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(124,58,237,0.12) 0%, transparent 70%);
            pointer-events: none;
        }

        .login-wrapper {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            position: relative;
            z-index: 1;
        }

        /* Brand */
        .login-brand {
            text-align: center;
            margin-bottom: 28px;
        }

        .brand-icon {
            width: 56px; height: 56px;
            background: linear-gradient(135deg, #7c3aed, #6366f1);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
            box-shadow: 0 8px 24px rgba(99,102,241,0.35);
        }

        .brand-icon i { color: #fff; font-size: 1.5rem; }

        .brand-title {
            font-size: 1.4rem;
            font-weight: 800;
            color: #f0f2f8;
            letter-spacing: -0.02em;
        }

        .brand-sub {
            font-size: 0.75rem;
            color: #4b5263;
            margin-top: 3px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        /* Card */
        .login-card {
            background: #1a1d27;
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 24px 64px rgba(0,0,0,0.4);
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #f0f2f8;
            margin-bottom: 4px;
        }

        .card-sub {
            font-size: 0.78rem;
            color: #4b5263;
            margin-bottom: 26px;
        }

        /* Divider */
        .divider {
            height: 1px;
            background: rgba(255,255,255,0.05);
            margin: 22px 0;
        }

        /* Field */
        .field { margin-bottom: 16px; }

        .field-label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 8px;
        }

        .field-input-wrap { position: relative; }

        .field-icon {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: #4b5263;
            font-size: 0.9rem;
            pointer-events: none;
            transition: color 0.15s;
        }

        .field-input {
            width: 100%;
            background: #12141c;
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 11px;
            padding: 12px 14px 12px 40px;
            color: #f0f2f8;
            font-size: 14px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .field-input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.12);
        }

        .field-input:focus + .field-icon,
        .field-input-wrap:focus-within .field-icon {
            color: #6366f1;
        }

        .field-input::placeholder { color: #353848; }

        /* Fix autofill */
        .field-input:-webkit-autofill,
        .field-input:-webkit-autofill:hover,
        .field-input:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0 1000px #12141c inset !important;
            -webkit-text-fill-color: #f0f2f8 !important;
            border-color: rgba(255,255,255,0.06) !important;
            transition: background-color 5000s ease-in-out 0s;
        }

        .field-error {
            font-size: 11px;
            color: #f87171;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Remember row */
        .remember-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }

        .remember-row input[type="checkbox"] {
            width: 15px; height: 15px;
            accent-color: #6366f1;
            cursor: pointer;
        }

        .remember-row label {
            font-size: 13px;
            color: #6b7280;
            cursor: pointer;
            user-select: none;
        }

        /* Button */
        .btn-login {
            width: 100%;
            padding: 13px;
            border-radius: 11px;
            background: #6366f1;
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            letter-spacing: 0.01em;
            box-shadow: 0 4px 16px rgba(99,102,241,0.3);
        }

        .btn-login:hover {
            background: #4f46e5;
            box-shadow: 0 6px 20px rgba(99,102,241,0.45);
            transform: translateY(-1px);
        }

        .btn-login:active { transform: translateY(0); }

        /* Forgot */
        .forgot-link {
            display: block;
            text-align: center;
            font-size: 12px;
            color: #4b5263;
            text-decoration: none;
            margin-top: 16px;
            transition: color 0.15s;
        }

        .forgot-link:hover { color: #9ca3b0; }

        /* Alerts */
        .alert-error {
            background: rgba(248,113,113,0.08);
            border: 1px solid rgba(248,113,113,0.2);
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 12px;
            color: #f87171;
            margin-bottom: 20px;
        }

        .alert-status {
            background: rgba(16,185,129,0.08);
            border: 1px solid rgba(16,185,129,0.2);
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 12px;
            color: #34d399;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="login-wrapper">

    <div class="login-brand">
        <div class="brand-icon">
            <i class="bi bi-lightning-charge-fill"></i>
        </div>
        <div class="brand-title">SiPeminjam</div>
        <div class="brand-sub">Sistem Peminjaman Alat</div>
    </div>

    <div class="login-card">
        <div class="card-title">Selamat datang 👋</div>
        <div class="card-sub">Masuk ke akun kamu untuk melanjutkan</div>

        @if (session('status'))
            <div class="alert-status">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert-error">
                @foreach ($errors->all() as $err)
                    <div>✗ {{ $err }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email --}}
            <div class="field">
                <label class="field-label" for="email">Email</label>
                <div class="field-input-wrap">
                    <input id="email" type="email" name="email"
                           class="field-input"
                           value="{{ old('email') }}"
                           placeholder="nama@email.com"
                           required autofocus autocomplete="username">
                    <i class="bi bi-envelope field-icon"></i>
                </div>
                @error('email')
                    <div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="field">
                <label class="field-label" for="password">Password</label>
                <div class="field-input-wrap">
                    <input id="password" type="password" name="password"
                           class="field-input"
                           placeholder="••••••••"
                           required autocomplete="current-password">
                    <i class="bi bi-lock field-icon"></i>
                </div>
                @error('password')
                    <div class="field-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>

            <div class="divider"></div>

            {{-- Remember Me --}}
            <div class="remember-row">
                <input id="remember_me" type="checkbox" name="remember">
                <label for="remember_me">Ingat saya</label>
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn-login">
                <i class="bi bi-box-arrow-in-right"></i>
                Masuk
            </button>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="forgot-link">
                    Lupa password?
                </a>
            @endif

        </form>
    </div>

</div>

</body>
</html>