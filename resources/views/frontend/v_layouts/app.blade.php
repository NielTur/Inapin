<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>@yield('title', 'VillaKu - Temukan Villa Impianmu')</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link href="{{ asset('frontend/img/favicon.ico') }}" rel="icon">

    <!-- Google Fonts (sama persis bawaan template) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap" rel="stylesheet">

    <!-- Icon Font -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries -->
    <link href="{{ asset('frontend/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">

    <!-- Template CSS -->
    <link href="{{ asset('frontend/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/css/style.css') }}" rel="stylesheet">

    @stack('styles')
</head>

<body>
    <div class="container-xxl bg-white p-0">

        {{-- SPINNER --}}
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>

        {{-- NAVBAR --}}
        <div class="container-fluid nav-bar bg-transparent">
            <nav class="navbar navbar-expand-lg bg-white navbar-light py-0 px-4">
                <a href="{{ route('beranda') }}" class="navbar-brand d-flex align-items-center text-center">
                    <div class="icon p-2 me-2">
                        <img class="img-fluid" src="{{ asset('frontend/img/icon-deal.png') }}" alt="VillaKu" style="width: 30px; height: 30px;">
                    </div>
                    <h1 class="m-0 text-primary">VillaKu</h1>
                </a>

                <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto">
                        <a href="{{ route('beranda') }}"
                            class="nav-item nav-link {{ request()->routeIs('beranda') ? 'active' : '' }}">Beranda</a>
                        <a href="{{ route('villa.index') }}"
                            class="nav-item nav-link {{ request()->routeIs('villa.*') ? 'active' : '' }}">Cari Villa</a>

                        @auth
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                                {{ auth()->user()->nama }}
                            </a>
                            <div class="dropdown-menu rounded-0 m-0">
                                <a href="{{ route('booking.riwayat') }}" class="dropdown-item">
                                    <i class="fa fa-history me-2"></i> Riwayat Pemesanan
                                </a>
                                <a href="{{ route('akun.profil') }}" class="dropdown-item">
                                    <i class="fa fa-user me-2"></i> Profil Saya
                                </a>
                                <div class="dropdown-divider"></div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fa fa-sign-out-alt me-2"></i> Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                        @else
                        <a href="{{ route('login') }}" class="nav-item nav-link">Masuk</a>
                        <a href="{{ route('register') }}" class="btn btn-primary px-3 d-none d-lg-flex align-items-center ms-2">Daftar</a>
                        @endauth
                    </div>
                </div>
            </nav>
        </div>

        {{-- FLASH MESSAGES --}}
        @if(session('success') || session('error') || session('warning'))
        <div class="container mt-3">
            @include('frontend.v_components.alert')
        </div>
        @endif

        {{-- KONTEN --}}
        @yield('content')

        {{-- FOOTER --}}
        <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-lg-3 col-md-6">
                        <h5 class="text-white mb-4">VillaKu</h5>
                        <p>Platform terpercaya untuk menemukan dan memesan villa impian di seluruh Indonesia.</p>
                        <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>Stockholm, Swedia</p>
                        <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+62 812 3456 7890</p>
                        <p class="mb-2"><i class="fa fa-envelope me-3"></i>halo@villaku.id</p>
                        <div class="d-flex pt-2">
                            <a class="btn btn-outline-light btn-social" href="#"><i class="fab fa-instagram"></i></a>
                            <a class="btn btn-outline-light btn-social" href="#"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-outline-light btn-social" href="#"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h5 class="text-white mb-4">Tautan Cepat</h5>
                        <a class="btn btn-link text-white-50" href="{{ route('beranda') }}">Beranda</a>
                        <a class="btn btn-link text-white-50" href="{{ route('villa.index') }}">Cari Villa</a>
                        <a class="btn btn-link text-white-50" href="#">Tentang Kami</a>
                        <a class="btn btn-link text-white-50" href="#">Kebijakan Privasi</a>
                        <a class="btn btn-link text-white-50" href="#">Syarat & Ketentuan</a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h5 class="text-white mb-4">Galeri Villa</h5>
                        <div class="row g-2 pt-2">
                            @for($i = 1; $i <= 6; $i++)
                                <div class="col-4">
                                <img class="img-fluid rounded bg-light p-1"
                                    src="{{ asset('frontend/img/property-' . $i . '.jpg') }}" alt="">
                        </div>
                        @endfor
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="text-white mb-4">Daftarkan Villa Anda</h5>
                    <p>Punya villa? Bergabunglah sebagai mitra owner dan mulai terima pemesanan sekarang.</p>
                    <a href="#" class="btn btn-primary py-2 px-4">Jadi Mitra Owner</a>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        &copy; {{ date('Y') }} <a class="border-bottom" href="{{ route('beranda') }}">VillaKu</a>. Hak Cipta Dilindungi.
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <div class="footer-menu">
                            <a href="{{ route('beranda') }}">Beranda</a>
                            <a href="#">Bantuan</a>
                            <a href="#">FAQ</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Back to Top --}}
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

    </div>

    {{-- JS Libraries --}}
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('frontend/lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('frontend/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('frontend/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('frontend/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('frontend/js/main.js') }}"></script>

    @stack('scripts')
</body>

</html>