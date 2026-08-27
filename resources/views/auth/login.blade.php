{{-- VIEW HALAMAN LOGIN: Form autentikasi masuk pengguna untuk seluruh 6 role --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - SIKS SMKN 2 Guguak</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo_smk.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />

    <style>
        :root {
            --primary: #1B4D3E;
            --primary-dark: #13382c;
            --primary-light: #2d6a56;
            --accent-gold: #D4AF37;
            --bg-main: #F8FAFC;
            --text-dark: #0F172A;
            --text-muted: #64748b;
            --border-color: #E2E8F0;
            --danger: #EF4444;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f2b23 0%, #1B4D3E 50%, #13382c 100%);
            margin: 0;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            color: var(--text-dark);
        }

        .login-card {
            background-color: #ffffff;
            width: 100%;
            max-width: 420px;
            border-radius: 1rem;
            padding: 2.5rem 2rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.35);
            border-top: 5px solid var(--accent-gold);
            position: relative;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-logo {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: var(--accent-gold);
            border-radius: 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            font-weight: 800;
            margin-bottom: 1rem;
            box-shadow: 0 4px 12px rgba(27, 77, 62, 0.3);
            border: 2px solid var(--accent-gold);
        }

        .login-header h1 {
            margin: 0;
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--primary-dark);
            letter-spacing: -0.025em;
        }

        .login-header h2 {
            margin: 0.25rem 0 0 0;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--accent-gold);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .login-header p {
            margin-top: 0.5rem;
            font-size: 0.8125rem;
            color: var(--text-muted);
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.375rem;
            color: var(--text-dark);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1.5px solid var(--border-color);
            border-radius: 0.5rem;
            font-size: 0.9375rem;
            font-family: inherit;
            transition: all 0.2s ease;
            outline: none;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(27, 77, 62, 0.15);
        }

        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: #ffffff;
            padding: 0.875rem;
            border: none;
            border-radius: 0.5rem;
            font-size: 0.9375rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(27, 77, 62, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary) 100%);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(27, 77, 62, 0.35);
        }

        .alert-error {
            background-color: #fef2f2;
            border-left: 4px solid var(--danger);
            color: #991b1b;
            padding: 0.875rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .login-footer {
            margin-top: 2rem;
            text-align: center;
            font-size: 0.75rem;
            color: var(--text-muted);
            border-top: 1px solid var(--border-color);
            padding-top: 1rem;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="login-header">
            <img src="{{ asset('images/logo_smk.png') }}" alt="Logo SMKN 2 Guguak" style="width:80px; height:80px; object-fit:contain; margin-bottom:0.75rem; filter:drop-shadow(0 4px 6px rgba(0,0,0,0.12));">
            <h1>SIKS SMKN 2 GUGUAK</h1>
            <h2>Layanan Konseling Siswa</h2>
            <p>Silakan masuk dengan kredensial akun Anda</p>
        </div>

        @if($errors->any())
            <div class="alert-error">
                <span>⚠️</span>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label" for="username">Username</label>
                <input type="text" name="username" id="username" class="form-control" value="{{ old('username') }}" required autofocus placeholder="Masukkan username Anda">
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <div style="position: relative;">
                    <input type="password" name="password" id="password" class="form-control" style="padding-right: 2.75rem;" required placeholder="Masukkan kata sandi Anda">
                    <button type="button" onclick="togglePasswordVisibility('password', this)" style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); background: none; border: none; padding: 0; cursor: pointer; color: #64748b; display: flex; align-items: center; justify-content: center; width: 1.5rem; height: 1.5rem;" title="Tampilkan / Sembunyikan Password">
                        <svg class="eye-open" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <svg class="eye-closed" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                Masuk ke Sistem &rarr;
            </button>
        </form>

        <div class="login-footer">
            &copy; {{ date('Y') }} SMKN 2 Guguak &bull; Hak Cipta Dilindungi
        </div>
    </div>

    <script>
        function togglePasswordVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            if (!input) return;
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            
            const eyeOpen = btn.querySelector('.eye-open');
            const eyeClosed = btn.querySelector('.eye-closed');
            if (eyeOpen && eyeClosed) {
                eyeOpen.style.display = isPassword ? 'none' : 'block';
                eyeClosed.style.display = isPassword ? 'block' : 'none';
            }
        }
    </script>
</body>
</html>
