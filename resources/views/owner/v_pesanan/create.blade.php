@extends('owner.v_layouts.app')

@section('title', 'Pesanan Manual - Panel Owner')
@section('page-title', 'Buat Pesanan Manual')

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
<div class="row">
    <div class="col-lg-8">
        <div class="bg-white rounded p-4 wow fadeInUp" data-wow-delay="0.1s">
            <form action="{{ route('owner.pesanan.store') }}" method="POST">
                @csrf
                <h5 class="fw-bold mb-4">
                    <i class="fa fa-home text-primary me-2"></i> Detail Villa
                </h5>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Pilih Villa</label>
                    <select name="id_villa" id="selectVilla" class="form-select @error('id_villa') is-invalid @enderror" required>
                        <option value="">-- Pilih Villa Tersedia --</option>
                        @foreach($villas as $villa)
                        <option value="{{ $villa->id_villa }}" data-harga="{{ $villa->harga }}">{{ $villa->nama_villa }} - Rp {{ number_format($villa->harga, 0, ',', '.') }}/malam</option>
                        @endforeach
                    </select>
                    @error('id_villa') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tanggal Check-in</label>
                        <input type="text" name="tanggal_checkin" id="inputCheckin" class="form-control @error('tanggal_checkin') is-invalid @enderror" value="{{ old('tanggal_checkin', date('Y-m-d')) }}" placeholder="Pilih tanggal" required readonly>
                        @error('tanggal_checkin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tanggal Check-out</label>
                        <input type="text" name="tanggal_checkout" id="inputCheckout" class="form-control @error('tanggal_checkout') is-invalid @enderror" value="{{ old('tanggal_checkout', date('Y-m-d', strtotime('+1 day'))) }}" placeholder="Pilih tanggal" required readonly>
                        @error('tanggal_checkout') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <h5 class="fw-bold mb-4 mt-5">
                    <i class="fa fa-user text-primary me-2"></i> Data Tamu
                </h5>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama Lengkap (Sesuai KTP)</label>
                        <input type="text" name="nama_tamu" class="form-control @error('nama_tamu') is-invalid @enderror" value="{{ old('nama_tamu') }}" placeholder="Contoh: Budi Santoso" required>
                        @error('nama_tamu') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">No. Handphone</label>
                        <input type="text" name="no_hp_tamu" class="form-control @error('no_hp_tamu') is-invalid @enderror" value="{{ old('no_hp_tamu') }}" placeholder="Contoh: 08123456789" required>
                        @error('no_hp_tamu') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Tipe Identitas</label>
                        <select name="tipe_identitas" class="form-select @error('tipe_identitas') is-invalid @enderror" required>
                            <option value="">Pilih...</option>
                            <option value="KTP" {{ old('tipe_identitas') == 'KTP' ? 'selected' : '' }}>KTP</option>
                            <option value="SIM" {{ old('tipe_identitas') == 'SIM' ? 'selected' : '' }}>SIM</option>
                            <option value="Paspor" {{ old('tipe_identitas') == 'Paspor' ? 'selected' : '' }}>Paspor</option>
                        </select>
                        @error('tipe_identitas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Nomor Identitas (NIK/No. Paspor)</label>
                        <input type="text" name="nomor_identitas" class="form-control @error('nomor_identitas') is-invalid @enderror" value="{{ old('nomor_identitas') }}" placeholder="Contoh: 3171234567890" required>
                        @error('nomor_identitas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Alamat (Kota/Domisili)</label>
                    <textarea name="alamat_tamu" class="form-control @error('alamat_tamu') is-invalid @enderror" rows="2" placeholder="Contoh: Jakarta Selatan" required>{{ old('alamat_tamu') }}</textarea>
                    @error('alamat_tamu') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Email (Opsional)</label>
                    <input type="email" name="email_tamu" class="form-control @error('email_tamu') is-invalid @enderror" value="{{ old('email_tamu') }}" placeholder="Contoh: budi@gmail.com">
                    @error('email_tamu') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <h5 class="fw-bold mb-4 mt-5">
                    <i class="fa fa-wallet text-primary me-2"></i> Metode Pembayaran
                </h5>

                <div class="mb-4">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="metode_pembayaran" id="metodeTunai" value="tunai" checked>
                        <label class="form-check-label" for="metodeTunai">Tunai</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="metode_pembayaran" id="metodeMidtrans" value="midtrans">
                        <label class="form-check-label" for="metodeMidtrans">Transfer / QRIS</label>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <h6 class="mb-0 text-muted">Estimasi Total</h6>
                        <h4 class="text-primary fw-bold mb-0" id="textTotal">Rp 0</h4>
                    </div>
                    <div>
                        <a href="{{ route('owner.pesanan.index') }}" class="btn btn-light border me-2">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">Buat Pesanan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- <div class="col-lg-4">
        <div class="bg-light rounded p-4">
            <h6 class="fw-bold mb-3">Informasi Pesanan Manual</h6>
            <p class="small text-muted mb-2">
                <i class="fa fa-info-circle me-1 text-primary"></i>
                Fitur ini digunakan jika ada customer yang datang langsung (walk-in) dan ingin memesan villa secara offline.
            </p>
            <p class="small text-muted mb-2">
                <i class="fa fa-info-circle me-1 text-primary"></i>
                Jika memilih metode <b>Tunai</b>, status pesanan akan otomatis menjadi <b>Dibayar</b>.
            </p>
            <p class="small text-muted mb-0">
                <i class="fa fa-info-circle me-1 text-primary"></i>
                Jika memilih <b>Midtrans</b>, Anda akan diarahkan ke halaman pembayaran Snap Midtrans untuk menunjukkan QRIS/Virtual Account ke customer.
            </p>
        </div>
    </div>
</div> -->

    @endsection

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
    <script>
        // Data booked dates per villa (dari server)
        var villaBookedDates = @json($villaBookedDates ?? {});
        var currentBookedDates = [];

        const selectVilla = document.getElementById('selectVilla');
        const inputCheckin = document.getElementById('inputCheckin');
        const inputCheckout = document.getElementById('inputCheckout');
        const textTotal = document.getElementById('textTotal');

        // Init Flatpickr
        var fpCheckoutOwner = null;
        var fpCheckinOwner = flatpickr('#inputCheckin', {
            locale: 'id',
            dateFormat: 'Y-m-d',
            minDate: 'today',
            defaultDate: '{{ old("tanggal_checkin", date("Y-m-d")) }}',
            disable: [],
            onDayCreate: function(dObj, dStr, fp, dayElem) {
                var dateStr = dayElem.dateObj.toISOString().split('T')[0];
                if (currentBookedDates.indexOf(dateStr) !== -1) {
                    dayElem.classList.add('booked-date');
                    dayElem.title = 'Sudah dipesan';
                }
            },
            onChange: function(selectedDates) {
                if (selectedDates.length > 0) {
                    var nextDay = new Date(selectedDates[0]);
                    nextDay.setDate(nextDay.getDate() + 1);
                    fpCheckoutOwner.set('minDate', nextDay);
                }
                hitungTotal();
            }
        });

        fpCheckoutOwner = flatpickr('#inputCheckout', {
            locale: 'id',
            dateFormat: 'Y-m-d',
            minDate: 'today',
            defaultDate: '{{ old("tanggal_checkout", date("Y-m-d", strtotime("+1 day"))) }}',
            disable: [],
            onDayCreate: function(dObj, dStr, fp, dayElem) {
                var dateStr = dayElem.dateObj.toISOString().split('T')[0];
                if (currentBookedDates.indexOf(dateStr) !== -1) {
                    dayElem.classList.add('booked-date');
                    dayElem.title = 'Sudah dipesan';
                }
            },
            onChange: function() {
                hitungTotal();
            }
        });

        // Saat villa dipilih, update booked dates di Flatpickr
        selectVilla.addEventListener('change', function() {
            var villaId = this.value;
            currentBookedDates = villaId && villaBookedDates[villaId] ? villaBookedDates[villaId] : [];

            // Update disable dates pada kedua picker
            fpCheckinOwner.set('disable', currentBookedDates);
            fpCheckoutOwner.set('disable', currentBookedDates);

            // Clear selected dates
            fpCheckinOwner.clear();
            fpCheckoutOwner.clear();

            hitungTotal();
        });

        function hitungTotal() {
            var checkin = inputCheckin.value;
            var checkout = inputCheckout.value;

            if (!checkin || !checkout) {
                textTotal.innerText = 'Rp 0';
                return;
            }

            var malam = Math.floor((new Date(checkout) - new Date(checkin)) / (1000 * 60 * 60 * 24));
            if (malam < 1 || isNaN(malam)) malam = 1;

            const option = selectVilla.options[selectVilla.selectedIndex];
            var harga = 0;
            if (option && option.value !== "") {
                harga = parseFloat(option.getAttribute('data-harga'));
            }

            const total = malam * harga;
            textTotal.innerText = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                maximumFractionDigits: 0
            }).format(total);
        }
    </script>
    @endpush