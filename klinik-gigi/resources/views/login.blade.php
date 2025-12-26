<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Klinik Gigi Sehat</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --glass: rgba(255, 255, 255, 0.8);
            --glass-border: rgba(255, 255, 255, 0.2);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: url('{{ asset("images/hero.png") }}') center/cover no-repeat fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
            position: relative;
        }

        /* Overlay for contrast */
        body::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(135deg, rgba(0,0,0,0.4) 0%, rgba(37, 99, 235, 0.2) 100%);
            z-index: 1;
        }

        .login-wrapper {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 450px;
        }

        .login-card {
            background: var(--glass);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            transition: transform 0.3s ease;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-container img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border: 4px solid white;
            object-fit: cover;
        }

        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .login-header h1 {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .login-header p {
            color: #64748b;
            font-size: 14px;
        }

        .form-label {
            font-weight: 600;
            font-size: 14px;
            color: #334155;
            margin-bottom: 8px;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            background: rgba(255, 255, 255, 0.5);
            transition: all 0.2s;
        }

        .form-control:focus {
            background: white;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .btn-primary {
            background: var(--primary);
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.2s;
            margin-top: 8px;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
        }

        /* Feature cards at the bottom (optional, but keeping it clean) */
        .features-container {
            margin-top: 40px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            z-index: 2;
            width: 100%;
            max-width: 900px;
            position: absolute;
            bottom: 40px;
        }

        @media (max-height: 800px) {
            .features-container {
                position: static;
                margin-top: 30px;
            }
            body {
                flex-direction: column;
                justify-content: flex-start;
                padding-top: 50px;
            }
        }

        @media (max-width: 768px) {
            .features-container {
                display: none;
            }
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 15px;
            color: white;
            text-align: center;
        }

        .feature-card h6 {
            font-weight: 700;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .feature-card p {
            font-size: 12px;
            margin: 0;
            opacity: 0.9;
        }
    </style>
</head>
<body>

    <div class="login-wrapper">
        <div class="login-card">
            <div class="logo-container">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Klinik">
            </div>

            <div class="login-header">
                <h1>Selamat Datang</h1>
                <p>Silakan masuk ke sistem Klinik Gigi Sehat</p>
            </div>

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="nama@email.com" required>
                </div>
                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Masuk ke Dashboard</button>
            </form>
        </div>
    </div>

    <div class="features-container">
        <div class="feature-card">
            <h6>Pemeriksaan Rutin</h6>
            <p>Perawatan berkala untuk senyum sehat.</p>
        </div>
        <div class="feature-card">
            <h6>Teknologi Modern</h6>
            <p>Peralatan mutakhir untuk kenyamanan Anda.</p>
        </div>
        <div class="feature-card">
            <h6>Dokter Ahli</h6>
            <p>Ditangani oleh tim medis berpengalaman.</p>
        </div>
    </div>

</body>
</html>