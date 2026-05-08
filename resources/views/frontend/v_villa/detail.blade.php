@extends('frontend.v_layouts.app')

@section('title', $villa->nama_villa . ' - VillaKu')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #peta-villa {
        height: 350px;
        width: 100%;
        border-radius: 8px;
        border: 1px solid #dee2e6;
    }

    .foto-thumb {
        cursor: pointer;
        border: 2px solid transparent;
        transition: .2s;
    }

    .foto-thumb:hover,
    .foto-thumb.active {
        border-color: var(--primary);
    }

    .foto-main {
        width: 100%;
        height: 400px;
        object-fit: cover;
        border-radius: 8px;
    }

    .sosmed-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        text-decoration: none;
        transition: .2s;
    }

    .sosmed-btn:hover {
        opacity: .8;
        transform: scale(1.1);
    }
</style>
@endpush

@section('content')

{{-- PAGE HEADER --}}
<div class="container-fluid bg-light py-4 mb-5">
    <div class="container">
        <h1 class="fw-bold mb-2">{{ $villa->nama_villa }}</h1>
        <p class="text-muted mb-2">
            <i class="fa fa-map-marker-alt text-primary me-2"></i>{{ $villa->alamat }}
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb text-uppercase mb-0">
                <li class="breadcrumb-item"><a href="{{ route('beranda') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('villa.index') }}">Cari Villa</a></li>
                <li class="breadcrumb-item text-body active">{{ $villa->nama_villa }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container-xxl pb-5">
    <div class="container">
        <div class="row g-5">

            {{-- ============ KIRI: Detail Villa ============ --}}
            <div class="col-lg-8">

                {{-- Foto Utama --}}
                <div class="mb-4 wow fadeInUp" data-wow-delay="0.1s">
                    @php
                    $fotos = $villa->dokumenVilla->where('status', 'disetujui');
                    $fotoUtama = $fotos->first();
                    @endphp
                    <img id="fotoUtama" class="foto-main img-fluid mb-3"
                        src="{{ $fotoUtama ? asset('storage/' . $fotoUtama->file_path) : asset('frontend/img/property-1.jpg') }}"
                        alt="{{ $villa->nama_villa }}">

                    @if($fotos->count() > 1)
                    <div class="d-flex gap-2 flex-wrap">
                        @foreach($fotos as $i => $foto)
                        <img class="foto-thumb rounded {{ $i == 0 ? 'active' : '' }}"
                            src="{{ asset('storage/' . $foto->file_path) }}"
                            alt="Foto {{ $i + 1 }}"
                            style="width:80px; height:60px; object-fit:cover;"
                            onclick="gantifoto(this, '{{ asset('storage/' . $foto->file_path) }}')">
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- Info Singkat Villa --}}
                <div class="d-flex flex-wrap gap-3 mb-4 wow fadeInUp" data-wow-delay="0.1s">
                    <span class="badge bg-primary px-3 py-2 fs-6">
                        <i class="fa fa-map-marker-alt me-2"></i>{{ $villa->kota }}
                    </span>
                    <span class="badge bg-light text-dark px-3 py-2 fs-6">
                        <i class="fa fa-users me-2 text-primary"></i>{{ $villa->kapasitas }} Tamu
                    </span>
                    <span class="badge bg-light text-dark px-3 py-2 fs-6">
                        <i class="fa fa-bed me-2 text-primary"></i>{{ $villa->jumlah_kamar ?? 1 }} Kamar
                    </span>
                    <span class="badge bg-light text-dark px-3 py-2 fs-6">
                        <i class="fa fa-bath me-2 text-primary"></i>{{ $villa->jumlah_kamar_mandi ?? 1 }} Kamar Mandi
                    </span>
                    @if($villa->ulasan)
                    <span class="badge bg-warning text-dark px-3 py-2 fs-6">
                        <i class="fa fa-star me-2"></i>{{ $villa->ulasan }} / 5.0
                    </span>
                    @endif
                    <span class="badge bg-success px-3 py-2 fs-6">
                        <i class="fa fa-check-circle me-2"></i>Tersedia
                    </span>
                </div>

                {{-- Tentang Villa --}}
                <div class="mb-5 wow fadeInUp" data-wow-delay="0.2s">
                    <h4 class="fw-bold mb-3">Tentang Villa Ini</h4>
                    <p class="text-muted lh-lg">{{ $villa->deskripsi ?? 'Deskripsi villa belum tersedia.' }}</p>

                    {{-- Sosmed & WA --}}
                    @if($villa->instagram || $villa->facebook || $villa->tiktok || $villa->whatsapp)
                    <div class="mt-4 pt-3 border-top">
                        <p class="fw-semibold mb-3">Ikuti & Hubungi Villa Ini</p>
                        <div class="d-flex align-items-center gap-3 flex-wrap">

                            @if($villa->instagram)
                            <a href="https://instagram.com/{{ ltrim($villa->instagram, '@') }}"
                                target="_blank"
                                class="sosmed-btn text-white"
                                style="background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888);"
                                title="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                            @endif

                            @if($villa->facebook)
                            <a href="https://facebook.com/{{ $villa->facebook }}"
                                target="_blank"
                                class="sosmed-btn text-white"
                                style="background: #1877f2;"
                                title="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            @endif

                            @if($villa->tiktok)
                            <a href="https://tiktok.com/@{{ ltrim($villa->tiktok, '@') }}"
                                target="_blank"
                                class="sosmed-btn text-white"
                                style="background: #010101;"
                                title="TikTok">
                                <i class="fab fa-tiktok"></i>
                            </a>
                            @endif

                            @if($villa->whatsapp)
                            <div class="d-flex align-items-center gap-2 bg-light rounded px-3 py-2">
                                <i class="fab fa-whatsapp text-success fa-lg"></i>
                                <span class="fw-semibold">{{ $villa->whatsapp }}</span>
                            </div>
                            @endif

                        </div>
                    </div>
                    @endif
                </div>

                {{-- Info Kamar --}}
                <div class="mb-5 wow fadeInUp" data-wow-delay="0.2s">
                    <h4 class="fw-bold mb-4">Informasi Kamar</h4>
                    <div class="row g-3">
                        <div class="col-6 col-md-4">
                            <div class="d-flex align-items-center gap-3 bg-light rounded p-3">
                                <i class="fas fa-bed text-primary fa-lg"></i>
                                <div>
                                    <div class="fw-bold">{{ $villa->jumlah_kamar ?? 1 }}</div>
                                    <small class="text-muted">Kamar Tidur</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="d-flex align-items-center gap-3 bg-light rounded p-3">
                                <i class="fas fa-bath text-primary fa-lg"></i>
                                <div>
                                    <div class="fw-bold">{{ $villa->jumlah_kamar_mandi ?? 1 }}</div>
                                    <small class="text-muted">Kamar Mandi</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="d-flex align-items-center gap-3 bg-light rounded p-3">
                                <i class="fas fa-users text-primary fa-lg"></i>
                                <div>
                                    <div class="fw-bold">{{ $villa->kapasitas }}</div>
                                    <small class="text-muted">Maks. Tamu</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== SECTION RATING — Tambahkan setelah Fasilitas, sebelum Peta ===== --}}
                @php
                $ulasanVilla = \App\Models\Ulasan::where('id_villa', $villa->id_villa)
                ->with('customer')
                ->latest()
                ->get();

                $ulasanSaya = null;
                $bolehRating = false;

                if (Auth::check()) {
                $ulasanSaya = \App\Models\Ulasan::where('id_villa', $villa->id_villa)
                ->where('id_customer', Auth::id())
                ->first();

                $bolehRating = \App\Models\Pemesanan::where('id_customer', Auth::id())
                ->where('id_villa', $villa->id_villa)
                ->whereIn('status', ['dikonfirmasi', 'selesai'])
                ->whereHas('detailPemesanan', function ($q) {
                $q->where('tanggal_checkout', '<', now()->toDateString());
                    })
                    ->exists();
                    }
                    @endphp

                    <div class="mb-5 wow fadeInUp" data-wow-delay="0.3s">
                        <h4 class="fw-bold mb-4">
                            Ulasan Tamu
                            @if($villa->ulasan)
                            <span class="badge bg-warning text-dark ms-2" style="font-size:14px;">
                                <i class="fa fa-star me-1"></i>{{ $villa->ulasan }} / 5.0
                            </span>
                            @endif
                        </h4>

                        {{-- Form Rating (hanya kalau sudah login & boleh rating) --}}
                        @auth
                        @if($bolehRating)
                        <div class="bg-light rounded p-4 mb-4">
                            <h6 class="fw-bold mb-3">
                                {{ $ulasanSaya ? 'Edit Rating Anda' : 'Berikan Rating' }}
                            </h6>
                            <form action="{{ route('ulasan.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id_villa" value="{{ $villa->id_villa }}">

                                {{-- Bintang interaktif --}}
                                <div class="d-flex gap-2 mb-3" id="starContainer">
                                    @for($i = 1; $i <= 5; $i++)
                                        <input type="radio" name="rating" id="star{{ $i }}"
                                        value="{{ $i }}" class="d-none"
                                        {{ $ulasanSaya && $ulasanSaya->rating == $i ? 'checked' : '' }}>
                                        <label for="star{{ $i }}"
                                            class="fa fa-star fa-2x"
                                            style="cursor:pointer; color: {{ $ulasanSaya && $ulasanSaya->rating >= $i ? '#ffc107' : '#dee2e6' }};"
                                            onmouseover="hoverStar({{ $i }})"
                                            onmouseout="resetStar()"
                                            onclick="selectStar({{ $i }})">
                                        </label>
                                        @endfor
                                </div>

                                {{-- Komentar --}}
                                <div class="mb-3">
                                    <textarea name="komentar" class="form-control" rows="3"
                                        placeholder="Ceritakan pengalaman menginap Anda... (opsional)"
                                        maxlength="500">{{ $ulasanSaya?->komentar }}</textarea>
                                    <small class="text-muted">Maks. 500 karakter</small>
                                </div>

                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fa fa-paper-plane me-2"></i>
                                    {{ $ulasanSaya ? 'Update Rating' : 'Kirim Rating' }}
                                </button>
                            </form>
                        </div>
                        @elseif(!$ulasanSaya)
                        <div class="alert alert-info mb-4" style="border-radius:10px;">
                            <i class="fa fa-info-circle me-2"></i>
                            Anda bisa memberikan rating setelah checkout dari villa ini.
                        </div>
                        @endif
                        @else
                        <div class="alert alert-light border mb-4" style="border-radius:10px;">
                            <i class="fa fa-star me-2 text-warning"></i>
                            <a href="{{ route('login') }}" class="text-primary fw-semibold">Masuk</a>
                            untuk memberikan rating villa ini.
                        </div>
                        @endauth

                        {{-- Daftar Ulasan --}}
                        @if($ulasanVilla->count() > 0)
                        <div class="row g-3">
                            @foreach($ulasanVilla as $u)
                            <div class="col-12">
                                <div class="bg-light rounded p-3">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div class="d-flex align-items-center gap-2">
                                            {{-- Avatar --}}
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0"
                                                style="width:36px; height:36px; font-size:14px;">
                                                {{ strtoupper(substr($u->customer->nama ?? 'T', 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold" style="font-size:14px;">
                                                    {{ $u->customer->nama ?? 'Tamu' }}
                                                </div>
                                                <small class="text-muted">{{ $u->created_at->format('d M Y') }}</small>
                                            </div>
                                        </div>
                                        {{-- Bintang --}}
                                        <div class="d-flex gap-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fa fa-star {{ $i <= $u->rating ? 'text-warning' : 'text-muted' }}"
                                                style="font-size:12px;"></i>
                                                @endfor
                                        </div>
                                    </div>
                                    @if($u->komentar)
                                    <p class="text-muted small mb-0">{{ $u->komentar }}</p>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-4">
                            <i class="fa fa-comment-slash fa-2x text-muted mb-2"></i>
                            <p class="text-muted small mb-0">Belum ada ulasan untuk villa ini.</p>
                        </div>
                        @endif
                    </div>

                    {{-- Fasilitas --}}
                    @if($villa->fasilitasVilla->count() > 0)
                    <div class="mb-5 wow fadeInUp" data-wow-delay="0.2s">
                        <h4 class="fw-bold mb-4">Fasilitas Villa</h4>
                        <div class="row g-3">
                            @php
                            $ikonFasilitas = [
                            'Kolam Renang' => 'fa-swimming-pool',
                            'WiFi Gratis' => 'fa-wifi',
                            'Dapur Lengkap' => 'fa-utensils',
                            'Parkir Luas' => 'fa-parking',
                            'BBQ Area' => 'fa-fire',
                            'AC Setiap Kamar' => 'fa-snowflake',
                            'Sarapan' => 'fa-coffee',
                            'Laundry' => 'fa-tshirt',
                            ];
                            @endphp
                            @foreach($villa->fasilitasVilla as $f)
                            <div class="col-6 col-md-4">
                                <div class="d-flex align-items-center gap-3 bg-light rounded p-3">
                                    <i class="fas {{ $ikonFasilitas[$f->fasilitas] ?? 'fa-check' }} text-primary fa-lg"></i>
                                    <span class="fw-medium">{{ $f->fasilitas }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Peta --}}
                    <div class="mb-5 wow fadeInUp" data-wow-delay="0.3s">
                        <h4 class="fw-bold mb-4">Lokasi Villa</h4>
                        <p class="text-muted mb-3">
                            <i class="fa fa-map-marker-alt text-primary me-2"></i>{{ $villa->alamat }}
                        </p>
                        {{-- Tambah ini --}}
                        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($villa->alamat . ', ' . $villa->kota) }}"
                            target="_blank"
                            class="btn btn-outline-primary btn-sm mb-3">
                            <i class="fa fa-external-link-alt me-2"></i> Buka di Google Maps
                        </a>
                        <div id="peta-villa"></div>
                    </div>

            </div>

            {{-- ============ KANAN: Booking Card ============ --}}
            <div class="col-lg-4">
                <div class="bg-light rounded p-4 mb-4 wow fadeInUp sticky-top" data-wow-delay="0.1s" style="top: 80px;">

                    <div class="mb-4">
                        <h3 class="text-primary fw-bold mb-1">
                            Rp {{ number_format($villa->harga, 0, ',', '.') }}
                        </h3>
                        <span class="text-muted">per malam</span>
                    </div>

                    <input type="hidden" id="hargaVilla" value="{{ (int) $villa->harga }}">

                    @if($villa->ulasan)
                    <div class="d-flex align-items-center gap-2 mb-4">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fa fa-star {{ $i <= $villa->ulasan ? 'text-warning' : 'text-muted' }}"></i>
                            @endfor
                            <span class="fw-semibold">{{ $villa->ulasan }}</span>
                            <span class="text-muted small">/ 5.0</span>
                    </div>
                    @endif

                    <hr>

                    <form action="{{ route('booking.form', $villa->id_villa) }}" method="GET">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Check-in</label>
                            <input type="date" name="checkin" class="form-control"
                                min="{{ date('Y-m-d') }}"
                                value="{{ request('checkin') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Check-out</label>
                            <input type="date" name="checkout" class="form-control"
                                min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                value="{{ request('checkout') }}" required>

                            <div class="bg-white rounded p-3 mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted small">Harga per malam</span>
                                    <span class="small fw-semibold">Rp {{ number_format($villa->harga, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted small">Total estimasi</span>
                                    <span class="small fw-bold text-primary" id="totalHarga">—</span>
                                </div>
                            </div>

                            @auth
                            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold">
                                <i class="fa fa-calendar-check me-2"></i> Pesan Sekarang
                            </button>
                            @else
                            <a href="{{ route('login') }}" class="btn btn-primary w-100 py-3 fw-bold">
                                <i class="fa fa-sign-in-alt me-2"></i> Masuk untuk Memesan
                            </a>
                            <p class="text-center text-muted small mt-2">
                                Belum punya akun? <a href="{{ route('register') }}">Daftar gratis</a>
                            </p>
                            @endauth
                    </form>

                    <hr>

                    <div class="text-center">
                        <p class="text-muted small mb-1">Dikelola oleh</p>
                        <p class="fw-semibold mb-0">{{ $villa->owner->nama ?? 'VillaKu Partner' }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection


@push('scripts')
<script>
    var selectedRating = {
        {
            $ulasanSaya ? $ulasanSaya - > rating : 0
        }
    };

    function hoverStar(n) {
        var stars = document.querySelectorAll('#starContainer label');
        stars.forEach(function(s, i) {
            s.style.color = i < n ? '#ffc107' : '#dee2e6';
        });
    }

    function resetStar() {
        var stars = document.querySelectorAll('#starContainer label');
        stars.forEach(function(s, i) {
            s.style.color = i < selectedRating ? '#ffc107' : '#dee2e6';
        });
    }

    function selectStar(n) {
        selectedRating = n;
        document.getElementById('star' + n).checked = true;
    }
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var map = L.map('peta-villa').setView([-6.2088, 106.8456], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);
        L.marker([-6.2088, 106.8456])
            .addTo(map)
            .bindPopup('<b>{{ addslashes($villa->nama_villa) }}</b><br>{{ addslashes($villa->alamat) }}')
            .openPopup();
        setTimeout(function() {
            map.invalidateSize();
        }, 300);
    });

    function gantifoto(el, src) {
        document.getElementById('fotoUtama').src = src;
        document.querySelectorAll('.foto-thumb').forEach(t => t.classList.remove('active'));
        el.classList.add('active');
    }

    function hitungTotal() {
        var checkin = document.querySelector('input[name="checkin"]').value;
        var checkout = document.querySelector('input[name="checkout"]').value;
        var harga = parseInt(document.getElementById('hargaVilla').value);
        if (checkin && checkout) {
            var malam = Math.floor((new Date(checkout) - new Date(checkin)) / 86400000);
            if (malam > 0) {
                document.getElementById('totalHarga').textContent =
                    'Rp ' + (malam * harga).toLocaleString('id-ID') + ' (' + malam + ' malam)';
            }
        }
    }

    document.querySelector('input[name="checkin"]').addEventListener('change', hitungTotal);
    document.querySelector('input[name="checkout"]').addEventListener('change', hitungTotal);
</script>
@endpush