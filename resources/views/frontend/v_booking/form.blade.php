@extends('frontend.v_layouts.app')

@section('title', 'Pesan Villa - ' . $villa->nama_villa)

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
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

{{-- Hidden input untuk JS — WAJIB di sini, bukan di @push('scripts') --}}
<input type="hidden" id="hargaPerMalam" value="{{ (int) $villa->harga }}">

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
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="pesanUntukSaya" checked onchange="togglePemesananData()">
                            <label class="form-check-label" for="pesanUntukSaya">
                                Pemesanan ini untuk saya sendiri
                            </label>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama Lengkap Tamu</label>
                                <input type="text" name="nama_tamu" id="namaTamu" class="form-control bg-white"
                                    value="{{ Auth::user()->nama }}" readonly required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email Tamu</label>
                                <input type="email" name="email_tamu" id="emailTamu" class="form-control bg-white"
                                    value="{{ Auth::user()->email }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">No. Handphone Tamu</label>
                                <input type="text" name="no_hp_tamu" id="noHpTamu" class="form-control bg-white"
                                    value="{{ Auth::user()->phone ?? '' }}" readonly>
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
                                <input type="text" name="tanggal_checkin"
                                    class="form-control @error('tanggal_checkin') is-invalid @enderror"
                                    value="{{ old('tanggal_checkin', $checkin) }}"
                                    id="inputCheckin" placeholder="Pilih tanggal" required readonly>
                                @error('tanggal_checkin')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Check-out</label>
                                <input type="text" name="tanggal_checkout"
                                    class="form-control @error('tanggal_checkout') is-invalid @enderror"
                                    value="{{ old('tanggal_checkout', $checkout) }}"
                                    id="inputCheckout" placeholder="Pilih tanggal" required readonly>
                                @error('tanggal_checkout')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Jumlah Malam</label>
                                <input type="text" class="form-control bg-white fw-bold text-primary"
                                    id="jumlahMalam" value="{{ $malam }} malam" readonly>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Submit --}}
                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold wow fadeInUp" data-wow-delay="0.3s">
                        <i class="fa fa-check-circle me-2"></i> Konfirmasi Pemesanan
                    </button>
                </form>

            </div>

            {{-- ===== KANAN: Ringkasan Villa ===== --}}
            <div class="col-lg-5">
                <div class="bg-light rounded p-4 sticky-top wow fadeInUp" data-wow-delay="0.1s" style="top: 80px;">

                    <h5 class="fw-bold mb-4">Ringkasan Pemesanan</h5>

                    @php $foto = $villa->dokumenVilla->where('status', 'disetujui')->first(); @endphp
                    <img src="{{ $foto ? asset('storage/' . $foto->file_path) : asset('frontend/img/property-1.jpg') }}"
                        alt="{{ $villa->nama_villa }}"
                        class="img-fluid rounded mb-3 w-100" style="height:180px; object-fit:cover;">

                    <h6 class="fw-bold">{{ $villa->nama_villa }}</h6>
                    <p class="text-muted small mb-3">
                        <i class="fa fa-map-marker-alt text-primary me-1"></i>{{ $villa->kota }}
                    </p>

                    <hr>

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
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
<script>
    var hargaPerMalam = parseInt(document.getElementById('hargaPerMalam').value);
    var bookedDates = @json($bookedDates ?? []);

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

    // Flatpickr: Check-in
    var fpCheckoutForm = null;
    var fpCheckinForm = flatpickr('#inputCheckin', {
        locale: 'id',
        dateFormat: 'Y-m-d',
        minDate: 'today',
        defaultDate: '{{ old("tanggal_checkin", $checkin) }}',
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
                fpCheckoutForm.set('minDate', nextDay);
            }
            hitungUlang();
        }
    });

    // Flatpickr: Check-out
    fpCheckoutForm = flatpickr('#inputCheckout', {
        locale: 'id',
        dateFormat: 'Y-m-d',
        minDate: 'today',
        defaultDate: '{{ old("tanggal_checkout", $checkout) }}',
        disable: bookedDates,
        onDayCreate: function(dObj, dStr, fp, dayElem) {
            var dateStr = dayElem.dateObj.toISOString().split('T')[0];
            if (bookedDates.indexOf(dateStr) !== -1) {
                dayElem.classList.add('booked-date');
                dayElem.title = 'Sudah dipesan';
            }
        },
        onChange: function() {
            hitungUlang();
        }
    });

    function togglePemesananData() {
        var isChecked = document.getElementById('pesanUntukSaya').checked;
        var namaTamu = document.getElementById('namaTamu');
        var emailTamu = document.getElementById('emailTamu');
        var noHpTamu = document.getElementById('noHpTamu');

        if (isChecked) {
            namaTamu.value = "{{ Auth::user()->nama }}";
            emailTamu.value = "{{ Auth::user()->email }}";
            noHpTamu.value = "{{ Auth::user()->phone ?? '' }}";

            namaTamu.setAttribute('readonly', true);
            emailTamu.setAttribute('readonly', true);
            noHpTamu.setAttribute('readonly', true);
        } else {
            namaTamu.value = "";
            emailTamu.value = "";
            noHpTamu.value = "";

            namaTamu.removeAttribute('readonly');
            emailTamu.removeAttribute('readonly');
            noHpTamu.removeAttribute('readonly');

            namaTamu.focus();
        }
    }
</script>
@endpush