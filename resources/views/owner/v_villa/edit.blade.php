@extends('owner.v_layouts.app')

@section('title', 'Edit Villa - Panel Owner')
@section('page-title', 'Edit Villa')

@section('content')

    <div class="row g-4">
        <div class="col-lg-8">
            <form action="{{ route('owner.villa.update', $villa->id_villa) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

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
                                value="{{ old('nama_villa', $villa->nama_villa) }}" required>
                            @error('nama_villa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Deskripsi <span class="text-danger">*</span></label>
                            <textarea name="deskripsi" rows="4"
                                class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $villa->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Alamat Terstruktur --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea name="alamat" rows="2" class="form-control @error('alamat') is-invalid @enderror"
                                placeholder="Contoh: Jl. Bukit Indah No.5, RT 01/RW 02">{{ old('alamat', $villa->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Isi dengan nama jalan, nomor, RT/RW.</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kelurahan / Desa</label>
                            <input type="text" name="kelurahan"
                                class="form-control @error('kelurahan') is-invalid @enderror" placeholder="Contoh: Perwira"
                                value="{{ old('kelurahan', $villa->kelurahan) }}">
                            @error('kelurahan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kecamatan</label>
                            <input type="text" name="kecamatan"
                                class="form-control @error('kecamatan') is-invalid @enderror"
                                placeholder="Contoh: Bekasi Utara" value="{{ old('kecamatan', $villa->kecamatan) }}">
                            @error('kecamatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kota / Kabupaten <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="kota" class="form-control @error('kota') is-invalid @enderror"
                                placeholder="Contoh: Kota Bekasi" value="{{ old('kota', $villa->kota) }}" required>
                            @error('kota')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Provinsi</label>
                            <input type="text" name="provinsi" class="form-control @error('provinsi') is-invalid @enderror"
                                placeholder="Contoh: Jawa Barat" value="{{ old('provinsi', $villa->provinsi) }}">
                            @error('provinsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kapasitas Tamu <span class="text-danger">*</span></label>
                            <input type="number" name="kapasitas"
                                class="form-control @error('kapasitas') is-invalid @enderror"
                                value="{{ old('kapasitas', $villa->kapasitas) }}" min="1" required>
                            @error('kapasitas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status Villa</label>
                            @if($villa->status === 'disetujui')
                                <div class="form-control bg-light d-flex align-items-center gap-2">
                                    <span class="badge bg-success">Aktif</span>
                                    <span class="text-muted small">Villa Anda sudah disetujui admin.</span>
                                </div>
                                <input type="hidden" name="status" value="{{ $villa->status }}">
                                <div class="form-text">Status dikelola oleh admin.</div>
                            @else
                                <select name="status" class="form-select">
                                    <option value="aktif" {{ $villa->status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ $villa->status === 'nonaktif' ? 'selected' : '' }}>Nonaktif
                                    </option>
                                </select>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Harga per Malam (Rp) <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror"
                                    value="{{ old('harga', $villa->harga) }}" min="0" required>
                            </div>
                            @error('harga')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jumlah Kamar <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah_kamar"
                                class="form-control @error('jumlah_kamar') is-invalid @enderror"
                                value="{{ old('jumlah_kamar', $villa->jumlah_kamar ?? 1) }}" min="1" required>
                            @error('jumlah_kamar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jumlah Kamar Mandi <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="jumlah_kamar_mandi"
                                class="form-control @error('jumlah_kamar_mandi') is-invalid @enderror"
                                value="{{ old('jumlah_kamar_mandi', $villa->jumlah_kamar_mandi ?? 1) }}" min="1" required>
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
                        @forelse($villa->fasilitasVilla as $fas)
                            <div class="input-group mb-2 fasilitas-item">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fa fa-check text-primary"></i>
                                </span>
                                <input type="text" name="fasilitas[]" class="form-control border-start-0"
                                    value="{{ $fas->fasilitas }}" placeholder="Nama fasilitas">
                                <button type="button" class="btn btn-outline-danger btn-hapus-fasilitas">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        @empty
                            {{-- default kosong kalau belum ada fasilitas --}}
                        @endforelse
                    </div>

                    <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="btnTambahFasilitas">
                        <i class="fa fa-plus me-1"></i> Tambah Fasilitas
                    </button>
                </div>

                {{-- Foto Existing --}}
                @if($villa->dokumenVilla->count() > 0)
                    <div class="bg-white rounded p-4 mb-4 wow fadeInUp" data-wow-delay="0.3s">
                        <h6 class="fw-bold mb-4">
                            <i class="fa fa-images text-primary me-2"></i> Foto Saat Ini
                        </h6>
                        <div class="d-flex flex-wrap gap-3">
                            @foreach($villa->dokumenVilla as $dok)
                                <div class="position-relative">
                                    <img src="{{ asset('storage/' . $dok->file_path) }}" class="rounded border"
                                        style="width:100px; height:75px; object-fit:cover;" alt="">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Sosial Media & Kontak --}}
                <div class="bg-white rounded p-4 mb-4">
                    <h6 class="fw-bold mb-4">
                        <i class="fa fa-share-alt text-primary me-2"></i> Sosial Media & Kontak
                        <small class="text-muted fw-normal ms-2">(opsional)</small>
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Instagram</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fab fa-instagram"></i></span>
                                <input type="text" name="instagram" class="form-control" placeholder="@namaakun"
                                    value="{{ old('instagram', $villa->instagram ?? '') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Facebook</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fab fa-facebook-f"></i></span>
                                <input type="text" name="facebook" class="form-control" placeholder="namahalaman"
                                    value="{{ old('facebook', $villa->facebook ?? '') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">TikTok</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fab fa-tiktok"></i></span>
                                <input type="text" name="tiktok" class="form-control" placeholder="@namaakun"
                                    value="{{ old('tiktok', $villa->tiktok ?? '') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">No. WhatsApp</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fab fa-whatsapp"></i></span>
                                <input type="text" name="whatsapp" class="form-control" placeholder="08xxxxxxxxxx"
                                    value="{{ old('whatsapp', $villa->whatsapp ?? '') }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Upload Foto Baru --}}
                <div class="bg-white rounded p-4 mb-4 wow fadeInUp" data-wow-delay="0.3s">
                    <h6 class="fw-bold mb-4">
                        <i class="fa fa-upload text-primary me-2"></i> Tambah Foto Baru
                    </h6>
                    <input type="file" name="foto[]" id="inputFotoVilla" class="form-control" multiple accept="image/*"
                        onchange="previewFotoVilla(this)">
                    <small class="text-muted">Format: JPG, PNG, WEBP. Maks 2MB per foto.</small>
                    <div id="previewFotoContainer" class="d-flex flex-wrap gap-2 mt-3"></div>
                </div>

                {{-- Tombol --}}
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold">
                        <i class="fa fa-save me-2"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('owner.villa.index') }}" class="btn btn-outline-secondary px-4 py-2">
                        Batal
                    </a>
                </div>

            </form>
        </div>

        {{-- Info Villa --}}
        <div class="col-lg-4">
            <div class="bg-light rounded p-4 wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="fw-bold mb-3">Info Villa</h6>
                <p class="small text-muted mb-2">
                    <i class="fa fa-calendar text-primary me-2"></i>
                    Dibuat: {{ $villa->created_at->format('d M Y') }}
                </p>
                <p class="small text-muted mb-2">
                    <i class="fa fa-images text-primary me-2"></i>
                    {{ $villa->dokumenVilla->count() }} foto tersimpan
                </p>
                <p class="small text-muted mb-0">
                    <i class="fa fa-list text-primary me-2"></i>
                    {{ $villa->fasilitasVilla->count() }} fasilitas
                </p>

                <hr>
                <a href="{{ route('villa.detail', $villa->id_villa) }}" class="btn btn-outline-primary btn-sm w-100"
                    target="_blank">
                    <i class="fa fa-eye me-2"></i> Lihat di Website
                </a>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
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

        document.addEventListener('click', function (e) {
            if (e.target.closest('.btn-hapus-fasilitas')) {
                e.target.closest('.fasilitas-item').remove();
            }
        });

        function previewFotoVilla(input) {
            var container = document.getElementById('previewFotoContainer');
            container.innerHTML = '';
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
    </script>
@endpush