@extends('frontend.v_layouts.app')

@section('title', 'Cari Villa - VillaKu')

@push('styles')
<style>
    .hero-wrapper {
        margin-left: calc(-50vw + 50%);
        margin-right: calc(-50vw + 50%);
        width: 100vw;
    }

    .hero-img {
        transform-origin: center center;
    }
</style>
@endpush

@push('scripts')
<script>
    var slides = document.querySelectorAll('.hero-slide');
    var dots = document.querySelectorAll('.hero-dot');
    var current = 0;
    var interval = 3000; // Mengatur interval per gambar

    function goToSlide(n) {
        slides[current].style.opacity = '0';
        dots[current].classList.remove('bg-white');
        dots[current].classList.add('bg-opacity-50');

        current = n;

        slides[current].style.opacity = '1';
        dots[current].classList.add('bg-white');
        dots[current].classList.remove('bg-opacity-50');
    }

    function nextSlide() {
        var next = (current + 1) % slides.length;
        goToSlide(next);
    }

    // Auto slide
    var timer = setInterval(nextSlide, interval);

    // Pause on hover
    document.querySelector('.hero-wrapper').addEventListener('mouseenter', function() {
        clearInterval(timer);
    });
    document.querySelector('.hero-wrapper').addEventListener('mouseleave', function() {
        timer = setInterval(nextSlide, interval);
    });

    // Zoom on scroll
    window.addEventListener('scroll', function() {
        var scrollY = window.scrollY;
        var maxScroll = 400;
        var slides = document.querySelectorAll('.hero-slide');
        var scale = 1 + (scrollY / maxScroll) * 0.08;

        if (scrollY <= maxScroll) {
            slides.forEach(function(slide) {
                slide.style.backgroundSize = (100 + (scrollY / maxScroll) * 8) + '% auto';
            });
        }
    });

    var tamuValVilla = parseInt(document.getElementById('inputTamuVilla').value) || 1;
    var kamarValVilla = parseInt(document.getElementById('inputKamarVilla').value) || 1;

    function toggleGuestPickerVilla() {
        var picker = document.getElementById('guestPickerVilla');
        picker.style.display = picker.style.display === 'none' ? 'block' : 'none';
    }

    function changeCountVilla(type, delta) {
        if (type === 'tamu') {
            tamuValVilla = Math.max(1, tamuValVilla + delta);
            document.getElementById('tamuCountVilla').textContent = tamuValVilla;
            document.getElementById('inputTamuVilla').value = tamuValVilla;
        } else {
            kamarValVilla = Math.max(1, kamarValVilla + delta);
            document.getElementById('kamarCountVilla').textContent = kamarValVilla;
            document.getElementById('inputKamarVilla').value = kamarValVilla;
        }
        document.getElementById('guestLabelVilla').textContent =
            tamuValVilla + ' Tamu, ' + kamarValVilla + ' Kamar';
    }

    document.addEventListener('click', function(e) {
        var picker = document.getElementById('guestPickerVilla');
        var wrapper = picker?.closest('.position-relative');
        if (picker && !wrapper?.contains(e.target)) {
            picker.style.display = 'none';
        }
    });
</script>
@endpush


@section('content')

{{-- ===================== HEADER (HERO) ===================== --}}
<div class="hero-wrapper position-relative overflow-hidden" style="height: 85vh;">

    {{-- Slides --}}
    @php
    $heroImages = [
    asset('frontend/img/carousel-1.jpg'),
    asset('frontend/img/carousel-2.jpg'),
    asset('frontend/img/header.jpg'),
    ];
    @endphp

    @foreach($heroImages as $i => $img)
    <div class="hero-slide position-absolute w-100 h-100"
        style="background: url('{{ $img }}') center center / cover no-repeat;
                   transition: opacity 1s ease-in-out;
                   opacity: {{ $i === 0 ? '1' : '0' }};
                   top: 0; left: 0;">
    </div>
    @endforeach

    {{-- Dot indicator --}}
    <div class="position-absolute bottom-0 start-50 translate-middle-x mb-4 d-flex gap-2" style="z-index:10;">
        @foreach($heroImages as $i => $img)
        <div class="hero-dot rounded-circle {{ $i === 0 ? 'bg-white' : 'bg-white bg-opacity-50' }}"
            style="width:10px; height:10px; cursor:pointer; transition:.3s;"
            onclick="goToSlide({{ $i }})">
        </div>
        @endforeach
    </div>

</div>


{{-- SEARCH BAR --}}
<div class="container-fluid bg-primary mb-5 wow fadeIn" data-wow-delay="0.1s" style="padding: 35px;">
    <div class="container">
        <form action="{{ route('villa.index') }}" method="GET">
            <div class="row g-2">

                {{-- Lokasi --}}
                <div class="col-md-3">
                    <input type="text" name="kota" class="form-control border-0 py-3"
                        placeholder="Cari kota atau nama villa..."
                        value="{{ request('kota') }}">
                </div>

                {{-- Check-in --}}
                <div class="col-md-3">
                    <input type="date" name="checkin" class="form-control border-0 py-3"
                        value="{{ request('checkin') }}">
                </div>

                {{-- Check-out --}}
                <div class="col-md-2">
                    <input type="date" name="checkout" class="form-control border-0 py-3"
                        value="{{ request('checkout') }}">
                </div>

                {{-- Tamu & Kamar (gabungan) --}}
                <div class="col-md-2 position-relative">
                    <div class="bg-white rounded px-3 py-2 h-100"
                        style="cursor:pointer; min-height:48px;"
                        onclick="toggleGuestPickerVilla()">
                        <small class="text-muted d-block" style="font-size:11px; text-transform:uppercase; letter-spacing:.5px;">Tamu & Kamar</small>
                        <div class="fw-semibold" id="guestLabelVilla" style="font-size:14px;">
                            {{ request('tamu', 1) }} Tamu, {{ request('kamar', 1) }} Kamar
                        </div>
                    </div>

                    <input type="hidden" name="tamu" id="inputTamuVilla" value="{{ request('tamu', 1) }}">
                    <input type="hidden" name="kamar" id="inputKamarVilla" value="{{ request('kamar', 1) }}">

                    {{-- Dropdown Picker --}}
                    <div id="guestPickerVilla" class="bg-white rounded shadow p-3 position-absolute"
                        style="display:none; top:110%; left:0; width:260px; z-index:999; border:1px solid #eee;">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <div class="fw-semibold" style="font-size:14px;">Tamu</div>
                                <!-- <small class="text-muted">Dewasa</small> -->
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm rounded-circle"
                                    style="width:30px;height:30px;padding:0;"
                                    onclick="changeCountVilla('tamu', -1)">−</button>
                                <span id="tamuCountVilla" class="fw-bold">{{ request('tamu', 1) }}</span>
                                <button type="button" class="btn btn-outline-secondary btn-sm rounded-circle"
                                    style="width:30px;height:30px;padding:0;"
                                    onclick="changeCountVilla('tamu', 1)">+</button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <div class="fw-semibold" style="font-size:14px;">Kamar</div>
                                <small class="text-muted">Jumlah kamar</small>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm rounded-circle"
                                    style="width:30px;height:30px;padding:0;"
                                    onclick="changeCountVilla('kamar', -1)">−</button>
                                <span id="kamarCountVilla" class="fw-bold">{{ request('kamar', 1) }}</span>
                                <button type="button" class="btn btn-outline-secondary btn-sm rounded-circle"
                                    style="width:30px;height:30px;padding:0;"
                                    onclick="changeCountVilla('kamar', 1)">+</button>
                            </div>
                        </div>

                        <button type="button" class="btn btn-primary btn-sm w-100"
                            onclick="toggleGuestPickerVilla()">
                            Selesai
                        </button>
                    </div>
                </div>

                {{-- Tombol Cari --}}
                <div class="col-md-2 d-flex align-items-center">
                    <button type="submit" class="btn btn-dark border-0 w-100 py-3">
                        <i class="fa fa-search me-2"></i> Cari
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>


{{-- JUDUL SECTION --}}
<div class="container-xxl pt-5 pb-0">
    <div class="container">
        <h1 class="mb-1">Daftar Villa</h1>
        <p class="text-muted">{{ $villas->total() }} Villa Ditemukan</p>
    </div>
</div>

{{-- MAIN CONTENT --}}
<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-4">

            {{-- ============ SIDEBAR FILTER (kiri) ============ --}}
            <div class="col-lg-3">
                <form action="{{ route('villa.index') }}" method="GET" id="filterForm">

                    @if(request('kota'))
                    <input type="hidden" name="kota" value="{{ request('kota') }}">
                    @endif

                    <div class="bg-light rounded p-4 mb-4 wow fadeInUp" data-wow-delay="0.1s">

                        {{-- Header Filter --}}
                        <h5 class="fw-bold mb-4">
                            <i class="fa fa-filter text-primary me-2"></i> Filter
                            @if(request()->hasAny(['harga_min','harga_max','rating','tamu','fasilitas','lokasi','sort']))
                            <a href="{{ route('villa.index') }}"
                                class="btn btn-sm btn-outline-secondary float-end"
                                style="font-size:11px; padding:2px 8px;">Reset</a>
                            @endif
                        </h5>

                        {{-- Rentang Harga --}}
                        <div class="mb-4 pb-4 border-bottom">
                            <h6 class="fw-semibold mb-3">Rentang Harga</h6>
                            <div class="row g-2 mb-2">
                                <div class="col-6">
                                    <label class="form-label small text-muted">Min</label>
                                    <input type="number" name="harga_min"
                                        class="form-control form-control-sm"
                                        placeholder="0"
                                        value="{{ request('harga_min') }}">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small text-muted">Max</label>
                                    <input type="number" name="harga_max"
                                        class="form-control form-control-sm"
                                        placeholder="10000000"
                                        value="{{ request('harga_max') }}">
                                </div>
                            </div>
                            <small class="text-muted d-block mb-3">
                                Rp {{ number_format($hargaMin, 0, ',', '.') }}
                                — Rp {{ number_format($hargaMax, 0, ',', '.') }}
                            </small>
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                Terapkan Harga
                            </button>
                        </div>

                        {{-- Rating --}}
                        <div class="mb-4 pb-4 border-bottom">
                            <h6 class="fw-semibold mb-3">Rating</h6>
                            @foreach([5.0 => '5.0', 4.5 => '4.5', 4.0 => '4.0', 3.5 => '3.5', 3.0 => '3.0'] as $val => $label)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio"
                                    name="rating" id="rating{{ $val }}" value="{{ $val }}"
                                    {{ request('rating') == $val ? 'checked' : '' }}
                                    onchange="this.form.submit()">
                                <label class="form-check-label d-flex align-items-center gap-1" for="rating{{ $val }}">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa fa-star {{ $i <= $val ? 'text-warning' : 'text-muted' }}"
                                        style="font-size:11px;"></i>
                                        @endfor
                                        <span class="ms-1 small">{{ $label }}</span>
                                </label>
                            </div>
                            @endforeach
                        </div>

                        {{-- Fasilitas --}}
                        <div class="mb-4 pb-4 border-bottom">
                            <h6 class="fw-semibold mb-3">Fasilitas</h6>
                            @php
                            $fasilitasOptions = [
                            'Kolam Renang' => 'fa-swimming-pool',
                            'WiFi Gratis' => 'fa-wifi',
                            'Dapur Lengkap' => 'fa-utensils',
                            'Parkir Luas' => 'fa-parking',
                            'BBQ Area' => 'fa-fire',
                            'AC Setiap Kamar' => 'fa-snowflake',
                            'Sarapan' => 'fa-coffee',
                            'Laundry' => 'fa-tshirt',
                            ];
                            $fasilitasTerpilih = request()->has('fasilitas') ? (array) request('fasilitas') : [];
                            @endphp
                            @foreach($fasilitasOptions as $nama => $icon)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox"
                                    name="fasilitas[]"
                                    id="fas_{{ Str::slug($nama) }}"
                                    value="{{ $nama }}"
                                    {{ in_array($nama, $fasilitasTerpilih) ? 'checked' : '' }}
                                    onchange="this.form.submit()">
                                <label class="form-check-label small" for="fas_{{ Str::slug($nama) }}">
                                    <i class="fas {{ $icon }} text-primary me-1" style="width:14px;"></i>
                                    {{ $nama }}
                                </label>
                            </div>
                            @endforeach
                        </div>

                        {{-- Lokasi / Kota --}}
                        <div class="mb-2">
                            <h6 class="fw-semibold mb-3">Lokasi</h6>
                            @foreach($kotaList as $kota)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio"
                                    name="kota" id="kota_{{ Str::slug($kota) }}" value="{{ $kota }}"
                                    {{ request('kota') == $kota ? 'checked' : '' }}
                                    onchange="this.form.submit()">
                                <label class="form-check-label small" for="kota_{{ Str::slug($kota) }}">
                                    <i class="fa fa-map-marker-alt text-primary me-1"></i>
                                    {{ $kota }}
                                </label>
                            </div>
                            @endforeach
                        </div>

                    </div>
                </form>
            </div>
            {{-- end sidebar --}}

            {{-- ============ VILLA GRID (kanan) ============ --}}
            <div class="col-lg-9">

                {{-- Header hasil pencarian --}}
                <div class="d-flex justify-content-between align-items-center mb-4 wow slideInRight" data-wow-delay="0.1s">
                    <div>
                        @if(request('kota'))
                        @endif
                    </div>
                    {{-- Sort --}}
                    <div>
                        <select class="form-select form-select-sm" style="width:auto;"
                            onchange="window.location='{{ route('villa.index') }}?' + new URLSearchParams({...Object.fromEntries(new URLSearchParams(window.location.search)), sort: this.value})">
                            <option value="terbaru" {{ $sort == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                            <option value="harga_asc" {{ $sort == 'harga_asc' ? 'selected' : '' }}>Harga Terendah</option>
                            <option value="harga_desc" {{ $sort == 'harga_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                            <option value="rating" {{ $sort == 'rating' ? 'selected' : '' }}>Rating Terbaik</option>
                        </select>
                    </div>
                </div>

                {{-- Grid Villa --}}
                <div class="row g-4">
                    @forelse($villas as $index => $villa)
                    <div class="col-md-6 col-xl-4 wow fadeInUp" data-wow-delay="{{ ($index % 3) * 0.1 }}s">
                        @include('frontend.v_components.villa-card', ['villa' => $villa])
                    </div>
                    @empty
                    <div class="col-12 text-center py-5">
                        <img src="{{ asset('frontend/img/icon-search.png') }}" alt=""
                            style="width:80px; opacity:.3;">
                        <h5 class="mt-4 text-muted">Villa tidak ditemukan</h5>
                        <p class="text-muted">Coba ubah filter atau kata kunci pencarian Anda.</p>
                        <a href="{{ route('villa.index') }}" class="btn btn-outline-primary mt-2">
                            Reset Pencarian
                        </a>
                    </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if($villas->hasPages())
                <div class="mt-5 d-flex justify-content-center wow fadeInUp" data-wow-delay="0.1s">
                    {{ $villas->links() }}
                </div>
                @endif

            </div>
            {{-- end col-lg-9 --}}

        </div>
    </div>
</div>

@endsection