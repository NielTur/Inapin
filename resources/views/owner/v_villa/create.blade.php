@extends('owner.v_layouts.app')

@section('title', 'Tambah Villa - Panel Owner')
@section('page-title', 'Tambah Villa Baru')

@section('content')

    <div class="row g-4">
        <div class="col-lg-8">
            <form action="{{ route('owner.villa.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Info Dasar --}}
                <div class="bg-white rounded p-4 mb-4 wow fadeInUp" data-wow-delay="0.1s">
                    <h6 class="fw-bold mb-4">
                        <i class="fa fa-info-circle text-primary me-2"></i> Informasi Dasar
                    </h6>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Nama Villa <span class="text-danger">*</span></label>
                            <input type="text" name="nama_villa"
                                class="form-control @error('nama_villa') is-invalid @enderror"
                                placeholder="Contoh: Villa Bukit Indah" value="{{ old('nama_villa') }}" required>
                            @error('nama_villa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Deskripsi <span class="text-danger">*</span></label>
                            <textarea name="deskripsi" rows="4"
                                class="form-control @error('deskripsi') is-invalid @enderror"
                                placeholder="Ceritakan keunggulan villa Anda...">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Alamat Terstruktur --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea name="alamat" rows="2" class="form-control @error('alamat') is-invalid @enderror"
                                placeholder="Contoh: Jl. Bukit Indah No.5, RT 01/RW 02">{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Isi dengan nama jalan, nomor, RT/RW.</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kelurahan / Desa</label>
                            <input type="text" name="kelurahan"
                                class="form-control @error('kelurahan') is-invalid @enderror" placeholder="Contoh: Perwira"
                                value="{{ old('kelurahan') }}">
                            @error('kelurahan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kecamatan</label>
                            <input type="text" name="kecamatan"
                                class="form-control @error('kecamatan') is-invalid @enderror"
                                placeholder="Contoh: Bekasi Utara" value="{{ old('kecamatan') }}">
                            @error('kecamatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kota / Kabupaten <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="kota" class="form-control @error('kota') is-invalid @enderror"
                                placeholder="Contoh: Kota Bekasi" value="{{ old('kota') }}" required>
                            @error('kota')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Provinsi</label>
                            <input type="text" name="provinsi" class="form-control @error('provinsi') is-invalid @enderror"
                                placeholder="Contoh: Jawa Barat" value="{{ old('provinsi') }}">
                            @error('provinsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kapasitas Tamu <span class="text-danger">*</span></label>
                            <input type="number" name="kapasitas"
                                class="form-control @error('kapasitas') is-invalid @enderror" placeholder="Contoh: 8"
                                value="{{ old('kapasitas') }}" min="1" required>
                            @error('kapasitas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Harga per Malam (Rp) <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror"
                                    placeholder="Contoh: 1500000" value="{{ old('harga') }}" min="0" required>
                            </div>
                            @error('harga')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jumlah Kamar <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah_kamar"
                                class="form-control @error('jumlah_kamar') is-invalid @enderror" placeholder="Contoh: 3"
                                value="{{ old('jumlah_kamar', 1) }}" min="1" required>
                            @error('jumlah_kamar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jumlah Kamar Mandi <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="jumlah_kamar_mandi"
                                class="form-control @error('jumlah_kamar_mandi') is-invalid @enderror"
                                placeholder="Contoh: 2" value="{{ old('jumlah_kamar_mandi', 1) }}" min="1" required>
                            @error('jumlah_kamar_mandi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Fasilitas --}}
                <div class="bg-white rounded p-4 mb-4 wow fadeInUp" data-wow-delay="0.2s">
                    <h6 class="fw-bold mb-4">
                        <i class="fa fa-list text-primary me-2"></i> Fasilitas Villa
                    </h6>

                    <div id="fasilitasContainer">
                        @php
                            $fasilitasDefault = ['Kolam Renang', 'WiFi Gratis', 'Dapur Lengkap', 'Parkir Luas', 'AC Setiap Kamar', 'BBQ Area'];
                        @endphp
                        @foreach($fasilitasDefault as $fas)
                            <div class="input-group mb-2 fasilitas-item">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fa fa-check text-primary"></i>
                                </span>
                                <input type="text" name="fasilitas[]" class="form-control border-start-0" value="{{ $fas }}"
                                    placeholder="Nama fasilitas">
                                <button type="button" class="btn btn-outline-danger btn-hapus-fasilitas">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="btnTambahFasilitas">
                        <i class="fa fa-plus me-1"></i> Tambah Fasilitas
                    </button>
                </div>

                {{-- Upload Foto --}}
                <div class="bg-white rounded p-4 mb-4 wow fadeInUp" data-wow-delay="0.3s">
                    <h6 class="fw-bold mb-4">
                        <i class="fa fa-images text-primary me-2"></i> Foto Villa
                    </h6>
                    <input type="file" name="foto[]" id="inputFotoVilla"
                        class="form-control @error('foto.*') is-invalid @enderror" multiple accept="image/*"
                        onchange="previewFotoVilla(this)">
                    <small class="text-muted">Format: JPG, PNG, WEBP. Maks 2MB per foto. Bisa pilih lebih dari 1.</small>
                    @error('foto.*')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror

                    {{-- Preview --}}
                    <div id="previewFotoContainer" class="d-flex flex-wrap gap-2 mt-3"></div>
                </div>

                {{-- Tombol --}}
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold">
                        <i class="fa fa-save me-2"></i> Simpan Villa
                    </button>
                    <a href="{{ route('owner.villa.index') }}" class="btn btn-outline-secondary px-4 py-2">
                        Batal
                    </a>
                </div>

            </form>
        </div>

        {{-- Tips --}}
        <div class="col-lg-4">
            <div class="bg-light rounded p-4 wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="fw-bold mb-3"><i class="fa fa-lightbulb text-warning me-2"></i> Tips</h6>
                <ul class="list-unstyled small text-muted">
                    <li class="mb-2"><i class="fa fa-check text-success me-2"></i> Gunakan foto berkualitas tinggi</li>
                    <li class="mb-2"><i class="fa fa-check text-success me-2"></i> Deskripsi yang detail meningkatkan
                        kepercayaan tamu</li>
                    <li class="mb-2"><i class="fa fa-check text-success me-2"></i> Cantumkan semua fasilitas yang tersedia
                    </li>
                    <li class="mb-2"><i class="fa fa-check text-success me-2"></i> Harga kompetitif menarik lebih banyak
                        tamu</li>
                </ul>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Tambah fasilitas baru
        document.getElementById('btnTambahFasilitas').addEventListener('click', function () {
            var container = document.getElementById('fasilitasContainer');
            var div = document.createElement('div');
            div.className = 'input-group mb-2 fasilitas-item';
            div.innerHTML = `
                <span class="input-group-text bg-light border-end-0">
                    <i class="fa fa-check text-primary"></i>
                </span>
                <input type="text" name="fasilitas[]" class="form-control border-start-0" placeholder="Nama fasilitas">
                <button type="button" class="btn btn-outline-danger btn-hapus-fasilitas">
                    <i class="fa fa-times"></i>
                </button>
            `;
            container.appendChild(div);
        });

        // Hapus fasilitas
        document.addEventListener('click', function (e) {
            if (e.target.closest('.btn-hapus-fasilitas')) {
                e.target.closest('.fasilitas-item').remove();
            }
        });

        // Preview foto
        function previewFotoVilla(input) {
            var container = document.getElementById('previewFotoContainer');
            container.innerHTML = '';
            if (input.files) {
                Array.from(input.files).forEach(function (file) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        var img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'rounded border';
                        img.style = 'width:100px; height:75px; object-fit:cover;';
                        container.appendChild(img);
                    }
                    reader.readAsDataURL(file);
                });
            }
        }
    </script>
@endpush