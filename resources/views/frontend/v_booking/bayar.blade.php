@extends('frontend.v_layouts.app')

@section('title', 'Selesaikan Pembayaran - VillaKu')

@push('styles')
    <style>
        .timer-danger {
            color: #ef4444 !important;
        }

        .timer-pulse {
            animation: timerPulse 1s infinite;
        }

        @keyframes timerPulse {

            0%,
            100% {
                opacity: 1
            }

            50% {
                opacity: .4
            }
        }
    </style>
@endpush

@section('content')

    {{-- Hidden inputs untuk JS — JANGAN pakai {{ }} di dalam script --}}
    <input type="hidden" id="expiresAt" value="{{ $pemesanan->expires_at }}">
    <input type="hidden" id="snapToken" value="{{ $snapToken }}">
    <input type="hidden" id="detailUrl" value="{{ route('booking.detail', $pemesanan->id_pemesanan) }}">
    <input type="hidden" id="riwayatUrl" value="{{ route('booking.riwayat') }}">

    {{-- PAGE HEADER --}}
    <div class="container-fluid bg-light py-4 mb-5">
        <div class="container">
            <h4 class="fw-bold mb-1">Selesaikan Pembayaran</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size:13px;">
                    <li class="breadcrumb-item"><a href="{{ route('beranda') }}">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('booking.riwayat') }}">Riwayat</a></li>
                    <li class="breadcrumb-item active">Pembayaran</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container pb-5">
        <div class="row g-4 justify-content-center">

            {{-- ══ KIRI ══════════════════════════════════════════ --}}
            <div class="col-lg-7">

                {{-- Timer Countdown --}}
                <div class="bg-white rounded-4 shadow-sm p-4 mb-4 text-center" style="border:1px solid #f0f0f0;">
                    <p class="text-muted small mb-2">Selesaikan pembayaran dalam</p>
                    <div id="timerDisplay" class="fw-bold mb-3"
                        style="font-size:52px; letter-spacing:6px; font-family:monospace;">
                        --:--
                    </div>
                    <div class="progress mx-auto mb-3" style="height:6px; border-radius:10px; max-width:300px;">
                        <div id="timerBar" class="progress-bar bg-primary" role="progressbar"
                            style="width:100%; transition:width 1s linear;"></div>
                    </div>
                    <p class="text-muted small mb-0">
                        <i class="fa fa-exclamation-circle me-1"></i>
                        Pesanan otomatis dibatalkan jika waktu habis.
                    </p>
                </div>

                {{-- Tombol Bayar --}}
                <div class="bg-white rounded-4 shadow-sm p-4 mb-4" style="border:1px solid #f0f0f0;">
                    <h6 class="fw-bold mb-3">
                        <i class="fa fa-credit-card text-primary me-2"></i> Lakukan Pembayaran
                    </h6>
                    <p class="text-muted small mb-4">
                        Klik tombol di bawah untuk membuka halaman pembayaran.
                        Tersedia transfer bank, virtual account, QRIS, e-wallet, dan kartu kredit.
                    </p>
                    <button id="btnBayar" onclick="bayarSekarang()" class="btn btn-primary w-100 py-3 fw-bold rounded-3"
                        style="font-size:16px;">
                        <i class="fa fa-lock me-2"></i> Bayar Sekarang
                    </button>
                    <div class="text-center mt-3">
                        <small class="text-muted">
                            <i class="fa fa-shield-alt text-success me-1"></i>
                            Transaksi aman diproses oleh Midtrans
                        </small>
                    </div>
                </div>

                {{-- Info Pemesanan --}}
                <div class="bg-white rounded-4 shadow-sm p-4" style="border:1px solid #f0f0f0;">
                    <h6 class="fw-bold mb-3">
                        <i class="fa fa-receipt text-primary me-2"></i> Detail Pemesanan
                    </h6>
                    @php $detail = $pemesanan->detailPemesanan; @endphp
                    <div class="row g-3" style="font-size:13px;">
                        <div class="col-6">
                            <span class="text-muted d-block">No. Pesanan</span>
                            <span class="fw-semibold">#{{ str_pad($pemesanan->id_pemesanan, 6, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted d-block">Metode</span>
                            <span
                                class="fw-semibold">{{ ucfirst(str_replace('_', ' ', $pemesanan->metode_pembayaran)) }}</span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted d-block">Check-in</span>
                            <span
                                class="fw-semibold">{{ \Carbon\Carbon::parse($detail->tanggal_checkin)->format('d M Y') }}</span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted d-block">Check-out</span>
                            <span
                                class="fw-semibold">{{ \Carbon\Carbon::parse($detail->tanggal_checkout)->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ══ KANAN: Ringkasan Villa ════════════════════════ --}}
            <div class="col-lg-5">
                <div class="bg-white rounded-4 shadow-sm p-4 sticky-top" style="border:1px solid #f0f0f0; top:80px;">

                    @php $foto = $pemesanan->villa->dokumenVilla?->where('status', 'disetujui')->first()?->file_path; @endphp
                    <img src="{{ $foto ? asset('storage/' . $foto) : asset('frontend/img/property-1.jpg') }}"
                        class="rounded-3 w-100 mb-3" style="height:160px; object-fit:cover;">

                    <h6 class="fw-bold mb-1">{{ $pemesanan->villa->nama_villa }}</h6>
                    <p class="text-muted small mb-3">
                        <i class="fa fa-map-marker-alt text-primary me-1"></i>{{ $pemesanan->villa->kota }}
                    </p>

                    <hr>

                    @php $malam = \Carbon\Carbon::parse($detail->tanggal_checkin)->diffInDays($detail->tanggal_checkout); @endphp
                    <div class="d-flex justify-content-between mb-2" style="font-size:13px;">
                        <span class="text-muted">Harga per malam</span>
                        <span>Rp {{ number_format($detail->harga_default, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2" style="font-size:13px;">
                        <span class="text-muted">Durasi</span>
                        <span>{{ $malam }} malam</span>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Total</span>
                        <span class="fw-bold text-primary" style="font-size:20px;">
                            Rp {{ number_format($detail->sub_total, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('booking.riwayat') }}" class="btn btn-outline-secondary btn-sm w-100">
                            <i class="fa fa-arrow-left me-1"></i> Kembali ke Riwayat
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script>
        var expiresAt = new Date(document.getElementById('expiresAt').value);
        var snapToken = document.getElementById('snapToken').value;
        var detailUrl = document.getElementById('detailUrl').value;
        var riwayatUrl = document.getElementById('riwayatUrl').value;
        var totalDetik = Math.max(0, Math.floor((expiresAt - new Date()) / 1000));
        var timerInterval;

        function sisaDetik() {
            return Math.max(0, Math.floor((expiresAt - new Date()) / 1000));
        }

        function formatWaktu(detik) {
            var m = Math.floor(detik / 60).toString().padStart(2, '0');
            var s = (detik % 60).toString().padStart(2, '0');
            return m + ':' + s;
        }

        function updateTimer() {
            var sisa = sisaDetik();
            var display = document.getElementById('timerDisplay');
            var bar = document.getElementById('timerBar');
            var btn = document.getElementById('btnBayar');

            display.textContent = formatWaktu(sisa);

            var persen = totalDetik > 0 ? (sisa / totalDetik) * 100 : 0;
            bar.style.width = persen + '%';

            if (sisa <= 300) {
                display.classList.add('timer-danger', 'timer-pulse');
                bar.classList.remove('bg-primary');
                bar.classList.add('bg-danger');
            }

            if (sisa <= 0) {
                clearInterval(timerInterval);
                display.textContent = '00:00';
                btn.disabled = true;
                btn.innerHTML = '<i class="fa fa-times-circle me-2"></i> Waktu Habis';
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-secondary');
                setTimeout(function () { window.location.href = riwayatUrl; }, 2500);
            }
        }

        updateTimer();
        timerInterval = setInterval(updateTimer, 1000);

        function bayarSekarang() {
            snap.pay(snapToken, {
                onSuccess: function () {
                    window.location.href = detailUrl;
                },
                onPending: function () {
                    window.location.href = detailUrl;
                },
                onError: function () {
                    alert('Pembayaran gagal. Silakan coba lagi.');
                },
                onClose: function () {
                    // User tutup popup tanpa bayar — tetap di halaman ini
                }
            });
        }
    </script>
@endpush