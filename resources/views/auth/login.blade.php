<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — QR Attendance</title>
    <meta name="description" content="Admin login for QR Attendance Management System">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #0f1117;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        body::before {
            content: '';
            position: absolute;
            top: -200px; left: -200px;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(108,99,255,0.3) 0%, transparent 70%);
            border-radius: 50%;
        }
        body::after {
            content: '';
            position: absolute;
            bottom: -200px; right: -200px;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(167,139,250,0.2) 0%, transparent 70%);
            border-radius: 50%;
        }
        .login-container {
            position: relative; z-index: 10;
            width: 100%; max-width: 420px;
            padding: 0 20px;
        }
        .login-card {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 24px;
            padding: 44px 40px;
            backdrop-filter: blur(20px);
        }
        .brand-icon {
            width: 60px; height: 60px;
            background: linear-gradient(135deg, #6c63ff, #a78bfa);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem;
            color: #fff;
            margin: 0 auto 20px;
        }
        .login-title { color: #fff; font-weight: 800; font-size: 1.6rem; text-align: center; margin-bottom: 4px; }
        .login-subtitle { color: rgba(255,255,255,0.4); font-size: 0.85rem; text-align: center; margin-bottom: 32px; }
        .form-label { color: rgba(255,255,255,0.7); font-weight: 600; font-size: 0.82rem; }
        .form-control {
            background: rgba(255,255,255,0.05);
            border: 1.5px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            color: #fff;
            padding: 12px 16px;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }
        .form-control:focus {
            background: rgba(255,255,255,0.07);
            border-color: #6c63ff;
            box-shadow: 0 0 0 3px rgba(108,99,255,0.2);
            color: #fff;
        }
        .form-control::placeholder { color: rgba(255,255,255,0.2); }
        .input-icon { position: relative; }
        .input-icon i {
            position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
            color: rgba(255,255,255,0.3); font-size: 0.95rem;
        }
        .input-icon .form-control { padding-left: 42px; }
        .btn-login {
            background: linear-gradient(135deg, #6c63ff, #a78bfa);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 13px;
            font-weight: 700;
            font-size: 0.95rem;
            width: 100%;
            transition: all 0.2s ease;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(108,99,255,0.4);
            color: #fff;
        }
        .form-check-input:checked { background-color: #6c63ff; border-color: #6c63ff; }
        .form-check-label { color: rgba(255,255,255,0.5); font-size: 0.82rem; }
        .invalid-feedback { font-size: 0.8rem; }
        .alert-danger {
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.2);
            color: #fca5a5;
            border-radius: 12px;
            font-size: 0.85rem;
        }
        .footer-note { color: rgba(255,255,255,0.2); font-size: 0.75rem; text-align: center; margin-top: 24px; }
    </style>
</head>
<body>
<div class="login-container">
    <div class="login-card">
        <div class="brand-icon">
            <i class="bi bi-qr-code-scan"></i>
        </div>
        <h1 class="login-title">QR Attendance</h1>
        <p class="login-subtitle">Sign in to admin panel</p>

        @if ($errors->any())
            <div class="alert alert-danger mb-3">
                <i class="bi bi-exclamation-circle me-2"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <div class="input-icon">
                    <i class="bi bi-envelope-fill"></i>
                    <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           placeholder="admin@example.com" value="{{ old('email') }}" required autofocus>
                </div>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <div class="input-icon">
                    <i class="bi bi-lock-fill"></i>
                    <input id="password" type="password" name="password" class="form-control"
                           placeholder="••••••••" required>
                </div>
            </div>
            <div class="mb-4 d-flex align-items-center">
                <input class="form-check-input me-2" type="checkbox" name="remember" id="remember" style="background-color: transparent;">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>
            <button type="submit" class="btn btn-login">
                <i class="bi bi-box-arrow-in-right me-2"></i> Sign In
            </button>
        </form>
    </div>
    <p class="footer-note">QR Attendance Management System &copy; {{ date('Y') }}</p>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
