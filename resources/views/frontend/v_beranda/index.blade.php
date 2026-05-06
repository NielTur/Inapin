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

    function toggleGuestPicker() {
        var picker = document.getElementById('guestPicker');
        picker.style.display = picker.style.display === 'none' ? 'block' : 'none';
    }

    function changeCount(type, delta) {
        if (type === 'tamu') {
            tamuVal = Math.max(1, tamuVal + delta);
            document.getElementById('tamuCount').textContent = tamuVal;
            document.getElementById('inputTamu').value = tamuVal;
        } else {
            kamarVal = Math.max(1, kamarVal + delta);
            document.getElementById('kamarCount').textContent = kamarVal;
            document.getElementById('inputKamar').value = kamarVal;
        }
        document.getElementById('guestLabel').textContent = tamuVal + ' Tamu, ' + kamarVal + ' Kamar';
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

                {{-- Check-in --}}
                <div class="col-md-2">
                    <div class="bg-white rounded px-3 py-2 h-100">
                        <small class="text-muted d-block" style="font-size:11px; text-transform:uppercase; letter-spacing:.5px;">Check-in</small>
                        <input type="date" name="checkin" class="form-control border-0 p-0 fw-semibold"
                            value="{{ request('checkin') }}"
                            style="font-size:14px; box-shadow:none;">
                    </div>
                </div>

                {{-- Check-out --}}
                <div class="col-md-2">
                    <div class="bg-white rounded px-3 py-2 h-100">
                        <small class="text-muted d-block" style="font-size:11px; text-transform:uppercase; letter-spacing:.5px;">Check-out</small>
                        <input type="date" name="checkout" class="form-control border-0 p-0 fw-semibold"
                            value="{{ request('checkout') }}"
                            style="font-size:14px; box-shadow:none;">
                    </div>
                </div>

                {{-- Tamu & Kamar --}}
                <div class="col-md-2 position-relative">
                    <div class="bg-white rounded px-3 py-2 h-100" style="cursor:pointer;" onclick="toggleGuestPicker()">
                        <small class="text-muted d-block" style="font-size:11px; text-transform:uppercase; letter-spacing:.5px;">Tamu & Kamar</small>
                        <div class="fw-semibold" id="guestLabel" style="font-size:14px;">
                            1 Tamu, 1 Kamar
                        </div>
                    </div>

                    {{-- Hidden inputs --}}
                    <input type="hidden" name="tamu" id="inputTamu" value="{{ request('tamu', 1) }}">
                    <input type="hidden" name="kamar" id="inputKamar" value="{{ request('kamar', 1) }}">

                    {{-- Dropdown Picker --}}
                    <div id="guestPicker" class="bg-white rounded shadow p-3 position-absolute"
                        style="display:none; top:110%; left:0; width:260px; z-index:999; border:1px solid #eee;">

                        {{-- Tamu --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <div class="fw-semibold" style="font-size:14px;">Tamu</div>
                                <!-- <small class="text-muted">Dewasa</small> -->
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
                                <small class="text-muted">Jumlah kamar</small>
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
                @php
                $kotaPopuler = $kotaPopuler ?? collect(['Bali', 'Puncak', 'Lombok', 'Bandung', 'Yogyakarta']);
                @endphp
                @foreach($kotaPopuler as $kota)
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

{{-- ===================== TESTIMONI ===================== --}}
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
            <h1 class="mb-3">Kata Mereka</h1>
            <p>Ribuan tamu puas telah memesan villa melalui VillaKu. Ini pengalaman mereka.</p>
        </div>
        <div class="owl-carousel testimonial-carousel wow fadeInUp" data-wow-delay="0.1s">
            <div class="testimonial-item bg-light rounded p-3">
                <div class="bg-white border rounded p-4">
                    <p>"Pengalaman menginap yang luar biasa! Villa-nya bersih, fasilitas lengkap, dan pemandangannya indah banget. Pasti balik lagi!"</p>
                    <div class="d-flex align-items-center">
                        <img class="img-fluid flex-shrink-0 rounded" src="{{ asset('frontend/img/testimonial-1.jpg') }}"
                            style="width: 45px; height: 45px;">
                        <div class="ps-3">
                            <h6 class="fw-bold mb-1">Siti Rahayu</h6>
                            <small>Jakarta</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="testimonial-item bg-light rounded p-3">
                <div class="bg-white border rounded p-4">
                    <p>"Proses booking sangat mudah dan cepat. Harga juga terjangkau untuk fasilitas yang didapat. Recommended banget buat liburan keluarga!"</p>
                    <div class="d-flex align-items-center">
                        <img class="img-fluid flex-shrink-0 rounded" src="{{ asset('frontend/img/testimonial-2.jpg') }}"
                            style="width: 45px; height: 45px;">
                        <div class="ps-3">
                            <h6 class="fw-bold mb-1">Budi Santoso</h6>
                            <small>Surabaya</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="testimonial-item bg-light rounded p-3">
                <div class="bg-white border rounded p-4">
                    <p>"Tim support VillaKu sangat responsif dan membantu. Ketika ada masalah kecil langsung direspon. Pelayanannya top!"</p>
                    <div class="d-flex align-items-center">
                        <img class="img-fluid flex-shrink-0 rounded" src="{{ asset('frontend/img/testimonial-3.jpg') }}"
                            style="width: 45px; height: 45px;">
                        <div class="ps-3">
                            <h6 class="fw-bold mb-1">Dewi Lestari</h6>
                            <small>Bandung</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection