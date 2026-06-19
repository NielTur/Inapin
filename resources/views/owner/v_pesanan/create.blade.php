@extends('owner.v_layouts.app')

@section('title', 'Pesanan Manual - Panel Owner')
@section('page-title', 'Buat Pesanan Manual')

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
                    <select name="id_villa" class="form-select @error('id_villa') is-invalid @enderror" required>
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
                        <input type="date" name="tanggal_checkin" id="inputCheckin" class="form-control @error('tanggal_checkin') is-invalid @enderror" value="{{ old('tanggal_checkin', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
                        @error('tanggal_checkin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tanggal Check-out</label>
                        <input type="date" name="tanggal_checkout" id="inputCheckout" class="form-control @error('tanggal_checkout') is-invalid @enderror" value="{{ old('tanggal_checkout', date('Y-m-d', strtotime('+1 day'))) }}" min="{{ date('Y-m-d') }}" required>
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
    <script>
        const selectVilla = document.querySelector('select[name="id_villa"]');
        const inputCheckin = document.getElementById('inputCheckin');
        const inputCheckout = document.getElementById('inputCheckout');
        const textTotal = document.getElementById('textTotal');

        function hitungTotal() {
            const checkin = new Date(inputCheckin.value);
            const checkout = new Date(inputCheckout.value);

            let malam = (checkout - checkin) / (1000 * 60 * 60 * 24);
            if (malam < 1 || isNaN(malam)) malam = 1;

            const option = selectVilla.options[selectVilla.selectedIndex];
            let harga = 0;
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

        selectVilla.addEventListener('change', hitungTotal);
        inputCheckin.addEventListener('change', hitungTotal);
        inputCheckout.addEventListener('change', hitungTotal);
    </script>
    @endpush