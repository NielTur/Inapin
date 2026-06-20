@extends('owner.v_layouts.app')

@section('title', 'Profil Owner - VillaKu')

@section('content')
<div class="container-fluid py-4">

    {{-- Page header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Profil Saya</h4>
            <nav style="font-size:13px;">
                <a href="{{ route('owner.dashboard') }}" class="text-muted text-decoration-none">Dashboard</a>
                <span class="text-muted mx-1">/</span>
                <span class="text-dark">Profil</span>
            </nav>
        </div>
    </div>

    {{-- Alert --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 small mb-4">
        <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-3 small mb-4">
        <i class="fa fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row g-4">

        {{-- ── KIRI: Sidebar Avatar ───────────────────────────── --}}
        <div class="col-lg-3">
            <div class="bg-white rounded-4 shadow-sm p-4 text-center" style="border:1px solid #f0f0f0;">

                {{-- Avatar --}}
                <div class="mb-3">
                    @if($owner->foto)
                    <img id="previewGambar"
                        src="{{ asset('storage/' . $owner->foto) }}"
                        class="rounded-circle object-fit-cover border"
                        style="width:100px; height:100px;">
                    @else
                    <div id="previewGambar"
                        class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto"
                        style="width:100px; height:100px; font-size:36px; font-weight:700;">
                        {{ strtoupper(substr($owner->nama, 0, 1)) }}
                    </div>
                    @endif
                    <div class="mt-2">
                        <label for="inputFoto" class="btn btn-outline-primary btn-sm rounded-3" style="cursor:pointer;">
                            <i class="fa fa-camera me-1"></i> Ubah Foto
                        </label>
                    </div>
                </div>

                <h6 class="fw-bold mb-0">{{ $owner->nama }}</h6>
                <p class="text-muted small mb-0">{{ $owner->email }}</p>

                {{-- Badge Owner --}}
                <span class="badge mt-2 mb-3"
                    style="background:#edf7f3; color:var(--primary); font-size:11px; border-radius:20px; padding:5px 12px;">
                    <i class="fa fa-user-tie me-1"></i> Owner
                </span>

                <hr>

                {{-- Statistik singkat --}}
                <div class="row g-2 text-center">
                    <div class="col-6">
                        <div class="bg-light rounded-3 p-2">
                            <div class="fw-bold text-primary" style="font-size:18px;">
                                {{ $owner->villa()->count() }}
                            </div>
                            <div class="text-muted" style="font-size:11px;">Villa</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light rounded-3 p-2">
                            <div class="fw-bold text-primary" style="font-size:18px;">
                                {{ $owner->villa()->withCount('pemesanan')->get()->sum('pemesanan_count') }}
                            </div>
                            <div class="text-muted" style="font-size:11px;">Pesanan</div>
                        </div>
                    </div>
                </div>

                <div class="mt-3 d-grid gap-2">
                    <a href="{{ route('owner.villa.index') }}" class="btn btn-outline-primary btn-sm rounded-3">
                        <i class="fa fa-home me-2"></i>Villa Saya
                    </a>
                    <form action="{{ route('owner.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100 rounded-3">
                            <i class="fa fa-sign-out-alt me-2"></i> Keluar
                        </button>
                    </form>
                </div>

            </div>
        </div>

        {{-- ── KANAN: Form Edit Profil ─────────────────────────── --}}
        <div class="col-lg-9">
            <form action="{{ route('owner.akun.profil') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- File input foto (hidden) --}}
                <input type="file" id="inputFoto" name="foto"
                    class="d-none" accept="image/*"
                    onchange="previewFoto(this)">

                {{-- ── Informasi Pribadi ──────────────────────── --}}
                <div class="bg-white rounded-4 shadow-sm p-4 mb-4" style="border:1px solid #f0f0f0;">
                    <h6 class="fw-bold mb-4 d-flex align-items-center gap-2">
                        <span style="background:#edf7f3; color:var(--primary); width:28px; height:28px;
                                     border-radius:8px; display:flex; align-items:center; justify-content:center;">
                            <i class="fa fa-user" style="font-size:11px;"></i>
                        </span>
                        Informasi Pribadi
                    </h6>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Nama Lengkap</label>
                            <input type="text" name="nama"
                                class="form-control rounded-3 @error('nama') is-invalid @enderror"
                                value="{{ old('nama', $owner->nama) }}" required>
                            @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Email</label>
                            <input type="email" class="form-control rounded-3 bg-light"
                                value="{{ $owner->email }}" readonly>
                            <div class="form-text small">Email tidak dapat diubah.</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">No. Handphone</label>
                            <input type="text" name="phone"
                                class="form-control rounded-3 @error('phone') is-invalid @enderror"
                                value="{{ old('phone', $owner->phone) }}" required>
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir"
                                class="form-control rounded-3 @error('tanggal_lahir') is-invalid @enderror"
                                value="{{ old('tanggal_lahir', optional($owner->tanggal_lahir)->format('Y-m-d')) }}"
                                required>
                            @error('tanggal_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- NIK — khusus owner --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">
                                NIK
                                <span class="text-muted fw-normal">(16 digit)</span>
                            </label>
                            <input type="text" name="nik"
                                class="form-control rounded-3 @error('nik') is-invalid @enderror"
                                maxlength="16"
                                placeholder="Nomor Induk Kependudukan"
                                value="{{ old('nik', $owner->nik) }}">
                            @error('nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Alamat — khusus owner --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Alamat</label>
                            <textarea name="alamat" rows="2"
                                class="form-control rounded-3 @error('alamat') is-invalid @enderror"
                                placeholder="Alamat lengkap">{{ old('alamat', $owner->alamat) }}</textarea>
                            @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                {{-- ── Ganti Password ─────────────────────────── --}}
                <div class="bg-white rounded-4 shadow-sm p-4 mb-4" style="border:1px solid #f0f0f0;">
                    <h6 class="fw-bold mb-4 d-flex align-items-center gap-2">
                        <span style="background:#edf7f3; color:var(--primary); width:28px; height:28px;
                                     border-radius:8px; display:flex; align-items:center; justify-content:center;">
                            <i class="fa fa-lock" style="font-size:11px;"></i>
                        </span>
                        Ganti Password
                    </h6>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Password Lama</label>
                            <input type="password" name="password_lama"
                                class="form-control rounded-3"
                                placeholder="">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Password Baru</label>
                            <input type="password" name="password_baru"
                                class="form-control rounded-3 @error('password_baru') is-invalid @enderror"
                                placeholder="Minimal 8 karakter">
                            @error('password_baru')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Konfirmasi Password</label>
                            <input type="password" name="password_baru_confirmation"
                                class="form-control rounded-3"
                                placeholder="Ulangi password baru">
                        </div>
                    </div>
                </div>

                {{-- Tombol simpan --}}
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold rounded-3">
                        <i class="fa fa-save me-2"></i> Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewFoto(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var preview = document.getElementById('previewGambar');
                if (preview.tagName === 'DIV') {
                    var img = document.createElement('img');
                    img.id = 'previewGambar';
                    img.className = 'rounded-circle object-fit-cover border';
                    img.style = 'width:100px; height:100px;';
                    preview.parentNode.replaceChild(img, preview);
                    preview = img;
                }
                preview.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush