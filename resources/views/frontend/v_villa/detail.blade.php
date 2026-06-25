@extends('frontend.v_layouts.app')

@section('title', $villa->nama_villa . ' - VillaKu')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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

    /* Flatpickr: Tanggal yang sudah di-booking (merah/disabled) */
    .flatpickr-day.booked-date {
        background: #fee2e2 !important;
        color: #dc3545 !important;
        text-decoration: line-through;
        cursor: not-allowed;
    }
    .flatpickr-day.booked-date:hover {
        background: #fca5a5 !important;
        color: #dc3545 !important;
    }
</style>
@endpush

@section('content')

{{-- PAGE HEADER --}}
<div class="container-fluid bg-light py-4 mb-5">
    <div class="container">
        <h1 class="fw-bold mb-2">{{ $villa->nama_villa }}</h1>
        <p class="text-muted mb-2">
            <i class="fa fa-map-marker-alt text-primary me-2"></i>
            @php
            $alamatLengkap = collect([$villa->alamat, $villa->kelurahan, $villa->kecamatan, $villa->kota, $villa->provinsi])
            ->filter()->implode(', ');
            @endphp
            {{ $alamatLengkap }}
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

                    @if($villa->instagram || $villa->facebook || $villa->tiktok || $villa->whatsapp)
                    <div class="mt-4 pt-3 border-top">
                        <p class="fw-semibold mb-3">Ikuti & Hubungi Villa Ini</p>
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            @if($villa->instagram)
                            <a href="https://instagram.com/{{ ltrim($villa->instagram, '@') }}"
                                target="_blank" class="sosmed-btn text-white"
                                style="background:linear-gradient(45deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888);">
                                <i class="fab fa-instagram"></i>
                            </a>
                            @endif
                            @if($villa->facebook)
                            <a href="https://facebook.com/{{ $villa->facebook }}"
                                target="_blank" class="sosmed-btn text-white"
                                style="background:#1877f2;">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            @endif
                            @if($villa->tiktok)
                            <a href="https://tiktok.com/@{{ ltrim($villa->tiktok, '@') }}"
                                target="_blank" class="sosmed-btn text-white"
                                style="background:#010101;">
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

                {{-- Section Rating --}}
                @php
                $ulasanVilla = \App\Models\Ulasan::where('id_villa', $villa->id_villa)
                ->with('customer')->latest()->get();
                $ulasanSaya = null;
                $bolehRating = false;
                if (Auth::check()) {
                $ulasanSaya = \App\Models\Ulasan::where('id_villa', $villa->id_villa)
                ->where('id_customer', Auth::id())->first();
                $bolehRating = \App\Models\Pemesanan::where('id_customer', Auth::id())
                ->where('id_villa', $villa->id_villa)
                ->where('status', 'checked_out')
                ->whereHas('detailPemesanan', fn($q) => $q->where('tanggal_checkout', '<', now()->toDateString()))
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

                        @auth
                        @if($bolehRating)
                        <div class="bg-light rounded p-4 mb-4">
                            <h6 class="fw-bold mb-3">{{ $ulasanSaya ? 'Edit Rating Anda' : 'Berikan Rating' }}</h6>
                            <form action="{{ route('ulasan.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id_villa" value="{{ $villa->id_villa }}">
                                <div class="d-flex gap-2 mb-3" id="starContainer">
                                    @for($i = 1; $i <= 5; $i++)
                                        <input type="radio" name="rating" id="star{{ $i }}"
                                        value="{{ $i }}" class="d-none"
                                        {{ $ulasanSaya && $ulasanSaya->rating == $i ? 'checked' : '' }}>
                                        <label for="star{{ $i }}" class="fa fa-star fa-2x"
                                            style="cursor:pointer; color:{{ $ulasanSaya && $ulasanSaya->rating >= $i ? '#ffc107' : '#dee2e6' }};"
                                            onmouseover="hoverStar({{ $i }})"
                                            onmouseout="resetStar()"
                                            onclick="selectStar({{ $i }})">
                                        </label>
                                        @endfor
                                </div>
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

                        @if($ulasanVilla->count() > 0)
                        <div class="row g-3">
                            @foreach($ulasanVilla as $u)
                            <div class="col-12">
                                <div class="bg-light rounded p-3">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0"
                                                style="width:36px; height:36px; font-size:14px;">
                                                {{ strtoupper(substr($u->customer->nama ?? 'T', 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold" style="font-size:14px;">{{ $u->customer->nama ?? 'Tamu' }}</div>
                                                <small class="text-muted">{{ $u->created_at->format('d M Y') }}</small>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fa fa-star {{ $i <= $u->rating ? 'text-warning' : 'text-muted' }}" style="font-size:12px;"></i>
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
                        {{-- Di bagian Peta --}}
                        <p class="text-muted mb-3">
                            <i class="fa fa-map-marker-alt text-primary me-2"></i>{{ $alamatLengkap }}
                        </p>
                        <a href="https://www.google.com/maps/place/{{ urlencode($villa->alamat . ', ' . $villa->kota) }}"
                            target="_blank" class="btn btn-outline-primary btn-sm mb-3">
                            <i class="fa fa-external-link-alt me-2"></i> Buka di Google Maps
                        </a>
                        <div id="peta-villa"></div>
                    </div>

            </div>
            {{-- end col-lg-8 --}}

            {{-- ============ KANAN: Booking Card ============ --}}
            <div class="col-lg-4">

                {{-- Hidden inputs untuk JS --}}
                <input type="hidden" id="namaVilla" value="{{ $villa->nama_villa }}">
                <input type="hidden" id="alamatVilla" value="{{ $alamatLengkap }}">
                <input type="hidden" id="hargaVilla" value="{{ (int) $villa->harga }}">
                <input type="hidden" id="kotaVilla" value="{{ $villa->kota }}">
                <input type="hidden" id="kelurahan" value="{{ $villa->kelurahan ?? '' }}">
                <input type="hidden" id="kecamatan" value="{{ $villa->kecamatan ?? '' }}">
                <input type="hidden" id="provinsi" value="{{ $villa->provinsi ?? '' }}">
                <input type="hidden" id="villaLat" value="{{ $villa->latitude ?? '' }}">
                <input type="hidden" id="villaLng" value="{{ $villa->longitude ?? '' }}">

                <div class="bg-light rounded p-4 mb-4 wow fadeInUp sticky-top" data-wow-delay="0.1s" style="top:80px;">

                    @if($isFullyBooked)
                    {{-- ===== STATUS: VILLA HABIS! (Flow 2 — tanggal sudah terbooking) ===== --}}
                    <div class="text-center py-3">
                        <div class="mb-3" style="font-size: 52px; line-height:1;">🚫</div>
                        <h4 class="fw-bold text-danger mb-2">Villa Habis!</h4>
                        <p class="text-muted mb-1" style="font-size: 0.9rem;">
                            Villa ini sudah terbooking untuk tanggal:
                        </p>
                        <p class="fw-semibold mb-4" style="font-size: 0.9rem;">
                            {{ \Carbon\Carbon::parse($searchCheckin)->translatedFormat('d M Y') }}
                            &nbsp;→&nbsp;
                            {{ \Carbon\Carbon::parse($searchCheckout)->translatedFormat('d M Y') }}
                        </p>
                        <p class="text-muted small mb-4">
                            Silahkan pilih tanggal lain atau cari villa tersedia di sekitar area ini.
                        </p>
                        <div class="d-grid gap-2">
                            <a href="{{ route('villa.detail', $villa->id_villa) }}"
                               class="btn btn-outline-primary py-2 fw-semibold">
                                <i class="fa fa-calendar-alt me-2"></i> Ubah Tanggal
                            </a>
                            <a href="{{ route('villa.index', array_filter(['kota' => $villa->kota, 'checkin' => $searchCheckin, 'checkout' => $searchCheckout])) }}"
                               class="btn btn-primary py-2 fw-semibold">
                                <i class="fa fa-search me-2"></i> Lihat Villa Lain
                            </a>
                        </div>
                    </div>

                    @else
                    {{-- ===== FLOW 1 / NORMAL: Booking Form ===== --}}
                    <div class="mb-4">
                        <h3 class="text-primary fw-bold mb-1">
                            Rp {{ number_format($villa->harga, 0, ',', '.') }}
                        </h3>
                        <span class="text-muted">per malam</span>
                    </div>

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
                            <input type="text" name="checkin" id="fpCheckin" class="form-control"
                                value="{{ request('checkin') }}" placeholder="Pilih tanggal" required readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Check-out</label>
                            <input type="text" name="checkout" id="fpCheckout" class="form-control"
                                value="{{ request('checkout') }}" placeholder="Pilih tanggal" required readonly>
                        </div>

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
                    @endif

                    <hr>

                    <div class="text-center">
                        <p class="text-muted small mb-1">Dikelola oleh</p>
                        <p class="fw-semibold mb-0">{{ $villa->owner->nama ?? 'VillaKu Partner' }}</p>
                    </div>

                </div>

            </div>
            {{-- end col-lg-4 --}}

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // ===== DATA VILLA =====
        var namaVilla = document.getElementById('namaVilla').value;
        var alamatVilla = document.getElementById('alamatVilla').value;
        var kotaVilla = document.getElementById('kotaVilla').value;
        var villaLat = parseFloat(document.getElementById('villaLat').value) || 0;
        var villaLng = parseFloat(document.getElementById('villaLng').value) || 0;

        // ===== BOOKED DATES (dari server) =====
        var bookedDates = @json($bookedDates ?? []);

        // ===== FLATPICKR: CHECK-IN & CHECK-OUT =====
        var fpCheckout = null;
        var fpCheckin = flatpickr('#fpCheckin', {
            locale: 'id',
            dateFormat: 'Y-m-d',
            minDate: 'today',
            disable: bookedDates,
            onDayCreate: function(dObj, dStr, fp, dayElem) {
                var dateStr = dayElem.dateObj.toISOString().split('T')[0];
                if (bookedDates.indexOf(dateStr) !== -1) {
                    dayElem.classList.add('booked-date');
                    dayElem.title = 'Sudah dipesan';
                }
            },
            onChange: function(selectedDates) {
                if (selectedDates.length > 0) {
                    var nextDay = new Date(selectedDates[0]);
                    nextDay.setDate(nextDay.getDate() + 1);
                    fpCheckout.set('minDate', nextDay);
                }
                hitungTotal();
            }
        });

        fpCheckout = flatpickr('#fpCheckout', {
            locale: 'id',
            dateFormat: 'Y-m-d',
            minDate: 'today',
            disable: bookedDates,
            onDayCreate: function(dObj, dStr, fp, dayElem) {
                var dateStr = dayElem.dateObj.toISOString().split('T')[0];
                if (bookedDates.indexOf(dateStr) !== -1) {
                    dayElem.classList.add('booked-date');
                    dayElem.title = 'Sudah dipesan';
                }
            },
            onChange: function() {
                hitungTotal();
            }
        });

        // ===== INIT MAP =====
        var map = L.map('peta-villa');
        var marker = null;

        L.tileLayer('https://mt{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
            subdomains: ['0', '1', '2', '3'],
            attribution: '&copy; <a href="https://www.google.com/maps">Google Maps</a>',
            maxZoom: 20,
        }).addTo(map);

        // ===== LOADING INDICATOR =====
        var petaEl = document.getElementById('peta-villa');
        var loadingDiv = document.createElement('div');
        loadingDiv.id = 'peta-loading';
        loadingDiv.style.cssText =
            'position:absolute;top:0;left:0;width:100%;height:100%;' +
            'background:rgba(255,255,255,0.75);display:flex;align-items:center;' +
            'justify-content:center;z-index:999;border-radius:8px;';
        loadingDiv.innerHTML =
            '<div style="text-align:center">' +
            '<div class="spinner-border text-primary" role="status"></div>' +
            '<p class="mt-2 small text-muted mb-0">Memuat lokasi...</p>' +
            '</div>';
        petaEl.style.position = 'relative';
        petaEl.appendChild(loadingDiv);

        function removeLoading() {
            var el = document.getElementById('peta-loading');
            if (el) el.remove();
        }

        function setMarker(lat, lng, zoom) {
            map.setView([lat, lng], zoom);
            if (marker) map.removeLayer(marker);
            marker = L.marker([lat, lng]).addTo(map)
                .bindPopup(
                    '<b>' + namaVilla + '</b><br>' +
                    '<small>' + alamatVilla + '</small>'
                )
                .openPopup();
            setTimeout(function() {
                map.invalidateSize();
            }, 300);
            removeLoading();
        }

        // ===== PRIORITY 1: Pakai koordinat dari DB kalau ada =====
        if (villaLat && villaLng) {
            setMarker(villaLat, villaLng, 17);
            return; // Selesai, tidak perlu geocoding
        }

        // ===== PRIORITY 2: Fallback — kode lama lu yang works =====
        // Step 1: Cari bounding box kota dulu
        fetch('https://nominatim.openstreetmap.org/search?format=json' +
                '&q=' + encodeURIComponent(kotaVilla + ', Indonesia') +
                '&limit=1&countrycodes=id', {
                    headers: {
                        'Accept-Language': 'id,en'
                    }
                })
            .then(function(res) {
                return res.json();
            })
            .then(function(kotaData) {

                if (!kotaData || kotaData.length === 0) {
                    throw new Error('kota tidak ditemukan');
                }

                // Step 2: Cari alamat dibatasi dalam area kota
                var bb = kotaData[0].boundingbox;
                var viewbox = bb[2] + ',' + bb[1] + ',' + bb[3] + ',' + bb[0];

                return fetch(
                    'https://nominatim.openstreetmap.org/search?format=json' +
                    '&q=' + encodeURIComponent(alamatVilla) +
                    '&limit=1&countrycodes=id' +
                    '&viewbox=' + viewbox +
                    '&bounded=1', {
                        headers: {
                            'Accept-Language': 'id,en'
                        }
                    }
                );
            })
            .then(function(res) {
                return res.json();
            })
            .then(function(data) {

                if (data && data.length > 0) {
                    // Alamat ketemu dalam area kota → zoom 17
                    setMarker(parseFloat(data[0].lat), parseFloat(data[0].lon), 17);
                } else {
                    // Alamat tidak ketemu → fallback ke pusat kota → zoom 14
                    return fetch(
                            'https://nominatim.openstreetmap.org/search?format=json' +
                            '&q=' + encodeURIComponent(kotaVilla + ', Indonesia') +
                            '&limit=1&countrycodes=id', {
                                headers: {
                                    'Accept-Language': 'id,en'
                                }
                            }
                        )
                        .then(function(res) {
                            return res.json();
                        })
                        .then(function(d) {
                            if (d && d.length > 0) {
                                setMarker(parseFloat(d[0].lat), parseFloat(d[0].lon), 14);
                            } else {
                                // Kota pun tidak ketemu → default Indonesia
                                setMarker(-6.2088, 106.8456, 5);
                            }
                        });
                }
            })
            .catch(function() {
                setMarker(-6.2088, 106.8456, 5);
                removeLoading();
            });

        // ===== FOTO VILLA =====
        window.gantifoto = function(el, src) {
            document.getElementById('fotoUtama').src = src;
            document.querySelectorAll('.foto-thumb').forEach(function(t) {
                t.classList.remove('active');
            });
            el.classList.add('active');
        };

        // ===== HITUNG TOTAL BOOKING =====
        function hitungTotal() {
            var checkin = document.getElementById('fpCheckin').value;
            var checkout = document.getElementById('fpCheckout').value;
            var harga = parseInt(document.getElementById('hargaVilla').value);
            if (checkin && checkout) {
                var malam = Math.floor((new Date(checkout) - new Date(checkin)) / 86400000);
                if (malam > 0) {
                    document.getElementById('totalHarga').textContent =
                        'Rp ' + (malam * harga).toLocaleString('id-ID') + ' (' + malam + ' malam)';
                }
            }
        }

        // ===== RATING BINTANG =====
        var selectedRating = 0;
        window.hoverStar = function(n) {
            document.querySelectorAll('#starContainer label').forEach(function(s, i) {
                s.style.color = i < n ? '#ffc107' : '#dee2e6';
            });
        };
        window.resetStar = function() {
            document.querySelectorAll('#starContainer label').forEach(function(s, i) {
                s.style.color = i < selectedRating ? '#ffc107' : '#dee2e6';
            });
        };
        window.selectStar = function(n) {
            selectedRating = n;
            document.getElementById('star' + n).checked = true;
        };
    });
</script>
@endpush