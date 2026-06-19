<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Daftar Owner - VillaKu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('frontend/img/favicon.ico') }}" rel="icon">
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('frontend/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/css/style.css') }}" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #005f73 0%, #0a9396 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Heebo', sans-serif;
            padding: 2rem 1rem;
        }

        .auth-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .20);
            width: 100%;
            max-width: 540px;
            padding: 2.5rem;
        }

        .owner-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #edf7f3;
            color: var(--primary);
            border-radius: 20px;
            padding: 5px 16px;
            font-size: 12px;
            font-weight: 600;
        }

        .form-control {
            border-radius: 10px !important;
            padding: .6rem 1rem;
            font-size: 14px;
        }

        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(10, 147, 150, .15);
        }

        .btn {
            border-radius: 10px !important;
        }

        .step-badge {
            width: 26px;
            height: 26px;
            background: var(--primary);
            color: #fff;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .section-title {
            font-size: 13px;
            font-weight: 700;
            color: #333;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 1rem;
            padding-bottom: .5rem;
            border-bottom: 1px solid #f0f0f0;
        }
    </style>
</head>

<body>

    <div class="auth-card">

        <div class="text-center mb-4">
            <a href="{{ route('beranda') }}" class="text-decoration-none d-inline-block mb-2">
                <span class="fw-bold" style="font-size:22px; color:var(--primary);">
                    <i class="fa fa-home me-1"></i>VillaKu
                </span>
            </a>
            <div class="mb-2">
                <span class="owner-badge">
                    <i class="fa fa-user-tie" style="font-size:11px;"></i> Daftar Owner
                </span>
            </div>
            <h5 class="fw-bold mb-1">Buat Akun Owner</h5>
            <p class="text-muted small mb-0">
                Sudah punya akun?
                <a href="{{ route('owner.login') }}" class="text-primary fw-semibold text-decoration-none">Masuk di sini</a>
            </p>
        </div>

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 small py-2 mb-3">
            <i class="fa fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if ($errors->any())
        <div class="alert alert-danger rounded-3 small py-2 mb-3">
            <i class="fa fa-exclamation-circle me-2"></i>
            Mohon periksa kembali isian formulir di bawah.
        </div>
        @endif

        <form action="{{ route('owner.register') }}" method="POST">
            @csrf

            {{-- ── Seksi 1: Info Akun ──────────────────────────── --}}
            <div class="section-title">
                <span class="step-badge">1</span> Informasi Akun
            </div>
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <label class="form-label fw-semibold small mb-1">Nama Lengkap</label>
                    <input type="text" name="nama"
                        class="form-control @error('nama') is-invalid @enderror"
                        placeholder="Nama sesuai KTP"
                        value="{{ old('nama') }}" required>
                    @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold small mb-1">Email</label>
                    <input type="email" name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        placeholder="email@contoh.com"
                        value="{{ old('email') }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold small mb-1">Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="inputPassword"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Min. 8 karakter" required>
                        <button class="btn btn-outline-secondary" type="button"
                            onclick="togglePass('inputPassword', 'ikonMata1')">
                            <i class="fa fa-eye" id="ikonMata1"></i>
                        </button>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold small mb-1">Konfirmasi Password</label>
                    <div class="input-group">
                        <input type="password" name="password_confirmation" id="inputPassword2"
                            class="form-control"
                            placeholder="Ulangi password" required>
                        <button class="btn btn-outline-secondary" type="button"
                            onclick="togglePass('inputPassword2', 'ikonMata2')">
                            <i class="fa fa-eye" id="ikonMata2"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- ── Seksi 2: Info Pribadi ───────────────────────── --}}
            <div class="section-title">
                <span class="step-badge">2</span> Informasi Pribadi
            </div>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small mb-1">No. Handphone</label>
                    <input type="text" name="phone"
                        class="form-control @error('phone') is-invalid @enderror"
                        placeholder="08xxxxxxxxxx"
                        value="{{ old('phone') }}" required>
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold small mb-1">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir"
                        class="form-control @error('tanggal_lahir') is-invalid @enderror"
                        value="{{ old('tanggal_lahir') }}" required>
                    @error('tanggal_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold small mb-1">
                        NIK
                        <span class="text-muted fw-normal">(16 digit)</span>
                    </label>
                    <input type="text" name="nik"
                        class="form-control @error('nik') is-invalid @enderror"
                        placeholder="Nomor Induk Kependudukan"
                        maxlength="16"
                        value="{{ old('nik') }}">
                    @error('nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold small mb-1">
                        Alamat
                        <span class="text-muted fw-normal">(opsional)</span>
                    </label>
                    <textarea name="alamat" rows="2"
                        class="form-control @error('alamat') is-invalid @enderror"
                        placeholder="Alamat lengkap Anda">{{ old('alamat') }}</textarea>
                    @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 fw-bold py-2">
                <i class="fa fa-user-plus me-2"></i> Daftar Sekarang
            </button>
        </form>

        <hr class="my-4">

        <div class="d-flex justify-content-between" style="font-size:13px;">
            <a href="{{ route('beranda') }}" class="text-muted text-decoration-none">
                <i class="fa fa-arrow-left me-1"></i> Beranda
            </a>
            <a href="{{ route('login') }}" class="text-muted text-decoration-none">
                Login sebagai Tamu →
            </a>
        </div>

    </div>

    <script src="{{ asset('frontend/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        function togglePass(inputId, ikonId) {
            var input = document.getElementById(inputId);
            var ikon = document.getElementById(ikonId);
            input.type = input.type === 'password' ? 'text' : 'password';
            ikon.classList.toggle('fa-eye');
            ikon.classList.toggle('fa-eye-slash');
        }
    </script>
</body>

</html>