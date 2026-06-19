@extends('frontend.v_layouts.app')

@section('title', 'VillaKu - Temukan Villa Impianmu')

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

    .search-banner,
    .search-banner .container,
    .search-banner .row,
    .search-banner .col-md-2 {
        overflow: visible !important;
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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

    var tamuVal = parseInt(document.getElementById('inputTamu').value) || 1;
    var kamarVal = parseInt(document.getElementById('inputKamar').value) || 1;

    function toggleGuestPicker() {
        var picker = document.getElementById('guestPicker');
        var chevron = document.getElementById('chevronGuest');
        var isOpen = picker.style.display !== 'none';
        picker.style.display = isOpen ? 'none' : 'block';
        chevron.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
    }

    function changeCount(type, delta) {
        if (type === 'tamu') {
            tamuVal = Math.max(1, tamuVal + delta);
            document.getElementById('tamuCount').textContent = tamuVal;
            document.getElementById('inputTamu').value = tamuVal;
            document.getElementById('guestLabel').textContent = tamuVal + ' Tamu';
        } else {
            kamarVal = Math.max(1, kamarVal + delta);
            document.getElementById('kamarCount').textContent = kamarVal;
            document.getElementById('inputKamar').value = kamarVal;
            document.getElementById('kamarLabel').textContent = kamarVal + ' Kamar';
        }
    }

    // Tutup picker kalau klik di luar
    document.addEventListener('click', function(e) {
        var picker = document.getElementById('guestPicker');
        var wrapper = picker?.closest('.position-relative');
        if (picker && !wrapper?.contains(e.target)) {
            picker.style.display = 'none';
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
<script>
    flatpickr(".daterange", {
        mode: "range",
        minDate: "today",
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "d M Y",
        locale: "id",
        onChange: function(selectedDates, dateStr, instance) {
            if (selectedDates.length === 2) {
                var start = flatpickr.formatDate(selectedDates[0], "Y-m-d");
                var end = flatpickr.formatDate(selectedDates[1], "Y-m-d");
                document.getElementById('inputCheckinReal').value = start;
                document.getElementById('inputCheckoutReal').value = end;
            } else {
                document.getElementById('inputCheckinReal').value = '';
                document.getElementById('inputCheckoutReal').value = '';
            }
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

{{-- ===================== SEARCH BAR ===================== --}}
<div class="container-fluid bg-primary mb-5 wow fadeIn" data-wow-delay="0.1s" style="padding: 35px;">
    <div class="container">
        <form action="{{ route('villa.index') }}" method="GET">
            <div class="row g-2">

                {{-- Lokasi --}}
                <div class="col-md-2">
                    <div class="bg-white rounded px-3 py-2 h-100">
                        <small class="text-muted d-block" style="font-size:11px; text-transform:uppercase; letter-spacing:.5px;">Lokasi</small>
                        <input type="text" name="kota" class="form-control border-0 p-0 fw-semibold"
                            placeholder="Kota atau nama villa..."
                            value="{{ request('kota') }}"
                            style="font-size:14px; box-shadow:none;">
                    </div>
                </div>

                {{-- Tanggal Menginap --}}
                <div class="col-md-4">
                    <div class="bg-white rounded px-3 py-2 h-100 position-relative">
                        <small class="text-muted d-block" style="font-size:11px; text-transform:uppercase; letter-spacing:.5px;">Tanggal Menginap</small>
                        <div class="d-flex align-items-center">
                            <i class="fa fa-calendar-alt text-muted me-2" style="font-size:14px;"></i>
                            <input type="text" class="daterange form-control border-0 p-0 fw-semibold bg-white"
                                placeholder="Pilih tanggal..."
                                value="{{ request('checkin') && request('checkout') ? request('checkin').' to '.request('checkout') : '' }}"
                                style="font-size:14px; box-shadow:none; cursor:pointer;" readonly>
                        </div>
                        <input type="hidden" name="checkin" id="inputCheckinReal" value="{{ request('checkin') }}">
                        <input type="hidden" name="checkout" id="inputCheckoutReal" value="{{ request('checkout') }}">
                    </div>
                </div>

                {{-- Tamu & Kamar --}}
                <div class="col-md-2 position-relative" style="overflow:visible;">
                    <div class="bg-white rounded px-3 py-2 h-100 d-flex align-items-center justify-content-between"
                        style="cursor:pointer; min-height:46px;"
                        onclick="toggleGuestPicker()">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa fa-user-friends text-muted" style="font-size:15px;"></i>
                            <div>
                                <div class="fw-semibold" id="guestLabel" style="font-size:14px; line-height:1.2;">
                                    {{ request('tamu', 1) }} Tamu
                                </div>
                                <div id="kamarLabel" class="text-muted" style="font-size:11px; line-height:1.2;">
                                    {{ request('kamar', 1) }} Kamar
                                </div>
                            </div>
                        </div>
                        <i class="fa fa-chevron-down text-muted ms-2" id="chevronGuest"
                            style="font-size:11px; transition:transform .2s;"></i>
                    </div>

                    {{-- Hidden inputs --}}
                    <input type="hidden" name="tamu" id="inputTamu" value="{{ request('tamu', 1) }}">
                    <input type="hidden" name="kamar" id="inputKamar" value="{{ request('kamar', 1) }}">

                    {{-- Dropdown Picker --}}
                    <div id="guestPicker" class="bg-white rounded shadow p-3 position-absolute"
                        style="display:none; top:110%; left:0; width:260px; z-index:99999; border:1px solid #eee;">

                        {{-- Tamu --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <div class="fw-semibold" style="font-size:14px;">Tamu</div>
                                <small class="text-muted">Kapasitas Tamu</small>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm rounded-circle"
                                    style="width:30px;height:30px;padding:0;" onclick="changeCount('tamu', -1)">−</button>
                                <span id="tamuCount" class="fw-bold">{{ request('tamu', 1) }}</span>
                                <button type="button" class="btn btn-outline-secondary btn-sm rounded-circle"
                                    style="width:30px;height:30px;padding:0;" onclick="changeCount('tamu', 1)">+</button>
                            </div>
                        </div>

                        {{-- Kamar --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <div class="fw-semibold" style="font-size:14px;">Kamar</div>
                                <small class="text-muted">Jumlah Kamar</small>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm rounded-circle"
                                    style="width:30px;height:30px;padding:0;" onclick="changeCount('kamar', -1)">−</button>
                                <span id="kamarCount" class="fw-bold">{{ request('kamar', 1) }}</span>
                                <button type="button" class="btn btn-outline-secondary btn-sm rounded-circle"
                                    style="width:30px;height:30px;padding:0;" onclick="changeCount('kamar', 1)">+</button>
                            </div>
                        </div>

                        <button type="button" class="btn btn-primary btn-sm w-100" onclick="toggleGuestPicker()">
                            Selesai
                        </button>
                    </div>
                </div>

                {{-- Tombol Cari --}}
                <div class="col-md-2 d-flex align-items-center">
                    <button type="submit" class="btn btn-dark border-0 w-100 py-3 fw-semibold">
                        <i class="fa fa-search me-2"></i> Cari Villa
                    </button>
                </div>

            </div>

            {{-- Quick pick kota populer --}}
            <div class="mt-2 d-flex gap-2 flex-wrap">
                @foreach($kotaList as $kota)
                <a href="{{ route('villa.index', ['kota' => $kota]) }}"
                    class="badge rounded-pill text-decoration-none px-3 py-2"
                    style="background:rgba(255,255,255,0.2); color:#fff; font-size:12px;">
                    {{ $kota }}
                </a>
                @endforeach
            </div>

        </form>
    </div>
</div>

{{-- ===================== VILLA TERBARU ===================== --}}
<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-0 gx-5 align-items-end">
            <div class="col-lg-6">
                <div class="text-start mx-auto mb-5 wow slideInLeft" data-wow-delay="0.1s">
                    <h1 class="mb-3">Rekomendasi Villa</h1>
                    <p>Pilihan villa terbaik dan terbaru yang siap untuk Anda pesan hari ini.</p>
                </div>
            </div>
            <div class="col-lg-6 text-start text-lg-end wow slideInRight" data-wow-delay="0.1s">
                <a href="{{ route('villa.index') }}" class="btn btn-outline-primary mb-5">
                    Lihat Semua Villa <i class="fa fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>

        <div class="row g-4">
            @forelse($villasTerbaru as $index => $villa)
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="{{ ($index % 3) * 0.2 }}s">
                @include('frontend.v_components.villa-card', ['villa' => $villa])
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <img src="{{ asset('frontend/img/icon-search.png') }}" alt="" style="width:80px; opacity:.4;">
                <p class="mt-3 text-muted">Belum ada villa tersedia saat ini.</p>
            </div>
            @endforelse

            @if($villasTerbaru->count() > 0)
            <div class="col-12 text-center wow fadeInUp" data-wow-delay="0.1s">
                <a class="btn btn-primary py-3 px-5" href="{{ route('villa.index') }}">
                    Lihat Semua Villa
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection