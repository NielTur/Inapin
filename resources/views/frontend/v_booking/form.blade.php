@extends('frontend.v_layouts.app')

@section('title', 'Pesan Villa - ' . $villa->nama_villa)

@section('content')

{{-- PAGE HEADER --}}
<div class="container-fluid bg-light py-4 mb-5">
    <div class="container">
        <h1 class="fw-bold mb-2">Form Pemesanan</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb text-uppercase mb-0">
                <li class="breadcrumb-item"><a href="{{ route('beranda') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('villa.detail', $villa->id_villa) }}">{{ $villa->nama_villa }}</a></li>
                <li class="breadcrumb-item text-body active">Pemesanan</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container-xxl pb-5">
    <div class="container">
        <div class="row g-5">

            {{-- ===== KIRI: Form ===== --}}
            <div class="col-lg-7">

                <form action="{{ route('booking.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_villa" value="{{ $villa->id_villa }}">

                    @include('frontend.v_components.alert')

                    {{-- Data Pemesan --}}
                    <div class="bg-light rounded p-4 mb-4 wow fadeInUp" data-wow-delay="0.1s">
                        <h5 class="fw-bold mb-4">
                            <i class="fa fa-user text-primary me-2"></i> Data Pemesan
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama Lengkap</label>
                                <input type="text" class="form-control bg-white"
                                    value="{{ Auth::user()->nama }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="text" class="form-control bg-white"
                                    value="{{ Auth::user()->email }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">No. Handphone</label>
                                <input type="text" class="form-control bg-white"
                                    value="{{ Auth::user()->phone ?? '-' }}" readonly>
                            </div>
                        </div>
                        <small class="text-muted mt-2 d-block">
                            <i class="fa fa-info-circle me-1"></i>
                            Data tidak sesuai?
                            <a href="{{ route('akun.profil') }}">Perbarui profil Anda</a>
                        </small>
                    </div>

                    {{-- Detail Menginap --}}
                    <div class="bg-light rounded p-4 mb-4 wow fadeInUp" data-wow-delay="0.2s">
                        <h5 class="fw-bold mb-4">
                            <i class="fa fa-calendar-alt text-primary me-2"></i> Detail Menginap
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Check-in</label>
                                <input type="date" name="tanggal_checkin"
                                    class="form-control @error('tanggal_checkin') is-invalid @enderror"
                                    value="{{ old('tanggal_checkin', $checkin) }}"
                                    min="{{ date('Y-m-d') }}"
                                    id="inputCheckin" required>
                                @error('tanggal_checkin')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Check-out</label>
                                <input type="date" name="tanggal_checkout"
                                    class="form-control @error('tanggal_checkout') is-invalid @enderror"
                                    value="{{ old('tanggal_checkout', $checkout) }}"
                                    min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                    id="inputCheckout" required>
                                @error('tanggal_checkout')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- <div class="col-md-6">
                                <label class="form-label fw-semibold">Jumlah Tamu</label>
                                <select class="form-select">
                                    @for($i = 1; $i <= $villa->kapasitas; $i++)
                                        <option value="{{ $i }}" {{ $tamu == $i ? 'selected' : '' }}>
                                            {{ $i }} Tamu
                                        </option>
                                        @endfor
                                </select>
                            </div> -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Jumlah Malam</label>
                                <input type="text" class="form-control bg-white fw-bold text-primary"
                                    id="jumlahMalam" value="{{ $malam }} malam" readonly>
                            </div>
                        </div>
                    </div>

                    {{-- Metode Pembayaran --}}
                    <div class="bg-light rounded p-4 mb-4 wow fadeInUp" data-wow-delay="0.3s">
                        <h5 class="fw-bold mb-4">
                            <i class="fa fa-credit-card text-primary me-2"></i> Metode Pembayaran
                        </h5>
                        @error('metode_pembayaran')
                        <div class="alert alert-danger py-2">{{ $message }}</div>
                        @enderror
                        <div class="row g-3">
                            @php
                            $metodes = [
                            'transfer_bank' => ['label' => 'Transfer Bank', 'icon' => 'fa-university'],
                            'virtual_account' => ['label' => 'Virtual Account', 'icon' => 'fa-wallet'],
                            'qris' => ['label' => 'QRIS', 'icon' => 'fa-qrcode'],
                            ];
                            @endphp
                            @foreach($metodes as $value => $data)
                            <div class="col-md-4">
                                <input type="radio" class="btn-check" name="metode_pembayaran"
                                    id="metode_{{ $value }}" value="{{ $value }}"
                                    {{ old('metode_pembayaran') == $value ? 'checked' : '' }}>
                                <label class="btn btn-outline-primary w-100 py-3 d-flex flex-column align-items-center gap-2"
                                    for="metode_{{ $value }}">
                                    <i class="fas {{ $data['icon'] }} fa-lg"></i>
                                    <span class="small fw-semibold">{{ $data['label'] }}</span>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Tombol Submit --}}
                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold wow fadeInUp" data-wow-delay="0.4s">
                        <i class="fa fa-check-circle me-2"></i> Konfirmasi Pemesanan
                    </button>
                </form>

            </div>

            {{-- ===== KANAN: Ringkasan Villa ===== --}}
            <div class="col-lg-5">
                <div class="bg-light rounded p-4 sticky-top wow fadeInUp" data-wow-delay="0.1s" style="top: 80px;">

                    <h5 class="fw-bold mb-4">Ringkasan Pemesanan</h5>

                    {{-- Foto Villa --}}
                    @php $foto = $villa->dokumenVilla->where('status', 'disetujui')->first(); @endphp
                    <img src="{{ $foto ? asset('storage/' . $foto->file_path) : asset('frontend/img/property-1.jpg') }}"
                        alt="{{ $villa->nama_villa }}"
                        class="img-fluid rounded mb-3 w-100" style="height:180px; object-fit:cover;">

                    {{-- Info Villa --}}
                    <h6 class="fw-bold">{{ $villa->nama_villa }}</h6>
                    <p class="text-muted small mb-3">
                        <i class="fa fa-map-marker-alt text-primary me-1"></i>{{ $villa->kota }}
                    </p>

                    <hr>

                    {{-- Rincian Harga --}}
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Harga per malam</span>
                        <span class="small">Rp {{ number_format($villa->harga, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Jumlah malam</span>
                        <span class="small" id="ringkasanMalam">{{ $malam }} malam</span>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">Total</span>
                        <span class="fw-bold text-primary fs-5" id="ringkasanTotal">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="mt-3 bg-white rounded p-3">
                        <small class="text-muted">
                            <i class="fa fa-info-circle text-primary me-1"></i>
                            Pemesanan akan dikonfirmasi oleh owner villa dalam 1x24 jam.
                        </small>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    var hargaPerMalam = {
        {
            (int) $villa - > harga
        }
    };

    function hitungUlang() {
        var checkin = document.getElementById('inputCheckin').value;
        var checkout = document.getElementById('inputCheckout').value;

        if (checkin && checkout) {
            var malam = Math.floor((new Date(checkout) - new Date(checkin)) / 86400000);
            if (malam > 0) {
                var total = malam * hargaPerMalam;
                document.getElementById('jumlahMalam').value = malam + ' malam';
                document.getElementById('ringkasanMalam').textContent = malam + ' malam';
                document.getElementById('ringkasanTotal').textContent =
                    'Rp ' + total.toLocaleString('id-ID');
            }
        }
    }

    document.getElementById('inputCheckin').addEventListener('change', hitungUlang);
    document.getElementById('inputCheckout').addEventListener('change', hitungUlang);
</script>
@endpush