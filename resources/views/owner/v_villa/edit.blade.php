@extends('owner.v_layouts.app')

@section('title', 'Edit Villa - Panel Owner')
@section('page-title', 'Edit Villa')

@section('content')

<div class="row g-4">
    <div class="col-lg-8">
        <form action="{{ route('owner.villa.update', $villa->id_villa) }}" method="POST" enctype="multipart/form-data" onsubmit="return stripRupiah(this)">
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

                    {{-- ── Alamat Terstruktur ──────────────────────── --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold">Alamat Lengkap <span class="text-danger">*</span></label>
                        <textarea name="alamat" rows="2" id="fieldAlamat"
                            class="form-control @error('alamat') is-invalid @enderror"
                            placeholder="Contoh: Jl. Bukit Indah No.5, RT 01/RW 02">{{ old('alamat', $villa->alamat) }}</textarea>
                        @error('alamat')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Isi dengan nama jalan, nomor, RT/RW.</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kelurahan / Desa</label>
                        <input type="text" name="kelurahan" id="fieldKelurahan"
                            class="form-control @error('kelurahan') is-invalid @enderror" placeholder="Contoh: Perwira"
                            value="{{ old('kelurahan', $villa->kelurahan) }}">
                        @error('kelurahan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kecamatan</label>
                        <input type="text" name="kecamatan" id="fieldKecamatan"
                            class="form-control @error('kecamatan') is-invalid @enderror"
                            placeholder="Contoh: Bekasi Utara" value="{{ old('kecamatan', $villa->kecamatan) }}">
                        @error('kecamatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kota / Kabupaten <span
                                class="text-danger">*</span></label>
                        <input type="text" name="kota" id="fieldKota"
                            class="form-control @error('kota') is-invalid @enderror" placeholder="Contoh: Kota Bekasi"
                            value="{{ old('kota', $villa->kota) }}" required>
                        @error('kota')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Provinsi</label>
                        <input type="text" name="provinsi" id="fieldProvinsi"
                            class="form-control @error('provinsi') is-invalid @enderror"
                            placeholder="Contoh: Jawa Barat" value="{{ old('provinsi', $villa->provinsi) }}">
                        @error('provinsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Status --}}
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
                            <span class="text-muted small">Dikelola oleh admin.</span>
                        </div>
                        <input type="hidden" name="status" value="{{ $villa->status }}">
                        @else
                        <select name="status" class="form-select">
                            <option value="pending" @selected(old('status', $villa->status) === 'pending')>Menunggu Review
                            </option>
                            <option value="nonaktif" @selected(old('status', $villa->status) === 'nonaktif')>Nonaktif
                            </option>
                        </select>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Harga per Malam (Rp) <span
                                class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" inputmode="numeric" name="harga"
                                class="form-control input-rupiah @error('harga') is-invalid @enderror"
                                placeholder="Contoh: 1.500.000"
                                value="{{ old('harga') ? number_format(old('harga'), 0, ',', '.') : number_format($villa->harga, 0, ',', '.') }}"
                                oninput="formatRupiah(this)" required>
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
                    @endforelse
                </div>
                <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="btnTambahFasilitas">
                    <i class="fa fa-plus me-1"></i> Tambah Fasilitas
                </button>
            </div>

            {{-- Foto Existing --}}
            @if($villa->dokumenVilla->count() > 0)
            <div class="bg-white rounded p-4 mb-4 wow fadeInUp" data-wow-delay="0.25s">
                <h6 class="fw-bold mb-4">
                    <i class="fa fa-images text-primary me-2"></i> Foto Saat Ini
                </h6>
                <div class="d-flex flex-wrap gap-3">
                    @foreach($villa->dokumenVilla as $dok)
                    <div class="position-relative d-inline-block">
                        <img src="{{ asset('storage/' . $dok->file_path) }}" class="rounded border"
                            style="width:100px; height:75px; object-fit:cover;" alt="">
                        <button type="button" class="btn btn-danger btn-sm rounded-circle p-0 position-absolute" style="top: -5px; right: -5px; width: 24px; height: 24px; line-height: 20px;" onclick="hapusFoto({{ $dok->id_dokumen_villa }})">
                            <i class="fa fa-times small"></i>
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Sosial Media --}}
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
                                value="{{ old('instagram', $villa->instagram) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Facebook</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fab fa-facebook-f"></i></span>
                            <input type="text" name="facebook" class="form-control" placeholder="namahalaman"
                                value="{{ old('facebook', $villa->facebook) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">TikTok</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fab fa-tiktok"></i></span>
                            <input type="text" name="tiktok" class="form-control" placeholder="@namaakun"
                                value="{{ old('tiktok', $villa->tiktok) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">No. WhatsApp</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fab fa-whatsapp"></i></span>
                            <input type="text" name="whatsapp" class="form-control" placeholder="08xxxxxxxxxx"
                                value="{{ old('whatsapp', $villa->whatsapp) }}">
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

    {{-- Info sidebar --}}
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
            <p class="small text-muted mb-2">
                <i class="fa fa-list text-primary me-2"></i>
                {{ $villa->fasilitasVilla->count() }} fasilitas
            </p>
            <p class="small mb-0">
                @if($villa->latitude && $villa->longitude)
                <i class="fa fa-map-marker-alt text-success me-2"></i>
                <span class="text-success fw-semibold">Koordinat tersimpan</span>
                <br><small class="text-muted ms-4">{{ $villa->latitude }}, {{ $villa->longitude }}</small>
                @else
                <i class="fa fa-map-marker-alt text-warning me-2"></i>
                <span class="text-warning fw-semibold">Koordinat belum ada</span>
                @endif
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
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // ===== FASILITAS =====
    document.getElementById('btnTambahFasilitas').addEventListener('click', function() {
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

    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-hapus-fasilitas')) {
            e.target.closest('.fasilitas-item').remove();
        }
    });

    // ===== PREVIEW FOTO =====
    function previewFotoVilla(input) {
        var container = document.getElementById('previewFotoContainer');
        container.innerHTML = '';
        if (input.files) {
            Array.from(input.files).forEach(function(file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'rounded border';
                    img.style = 'width:100px; height:75px; object-fit:cover;';
                    container.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        }
    }

    // ===== MAP PICKER =====
    document.addEventListener('DOMContentLoaded', function() {

        var mapPicker = L.map('mapPicker').setView([-6.2088, 106.8456], 5);
        var pinMarker = null;

        L.tileLayer('https://mt{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
            subdomains: ['0', '1', '2', '3'],
            attribution: '&copy; <a href="https://www.google.com/maps">Google Maps</a>',
            maxZoom: 20,
        }).addTo(mapPicker);

        // ── Core: set/update pin ──────────────────────────────────
        function setPin(lat, lng, zoom) {
            if (pinMarker) mapPicker.removeLayer(pinMarker);
            pinMarker = L.marker([lat, lng], {
                draggable: true
            }).addTo(mapPicker);
            mapPicker.setView([lat, lng], zoom || 16);
            updateKoordinatDisplay(lat, lng);

            pinMarker.on('dragend', function(e) {
                var pos = e.target.getLatLng();
                updateKoordinatDisplay(pos.lat, pos.lng);
            });
        }

        function updateKoordinatDisplay(lat, lng) {
            document.getElementById('inputLatitude').value = lat.toFixed(8);
            document.getElementById('inputLongitude').value = lng.toFixed(8);
            document.getElementById('displayKoordinat').value = lat.toFixed(6) + ', ' + lng.toFixed(6);
        }

        // ── Load koordinat yang sudah tersimpan di DB ─────────────
        var savedLat = parseFloat(document.getElementById('inputLatitude').value) || 0;
        var savedLng = parseFloat(document.getElementById('inputLongitude').value) || 0;
        if (savedLat && savedLng) {
            setPin(savedLat, savedLng, 15);
        }

        // ── Klik langsung di peta ─────────────────────────────────
        mapPicker.on('click', function(e) {
            setPin(e.latlng.lat, e.latlng.lng, 16);
        });

        // ── Helper: ambil semua field alamat dari form ────────────
        function buatFieldsDariForm() {
            return {
                alamat: document.getElementById('fieldAlamat')?.value.trim() || '',
                kelurahan: document.getElementById('fieldKelurahan')?.value.trim() || '',
                kecamatan: document.getElementById('fieldKecamatan')?.value.trim() || '',
                kota: document.getElementById('fieldKota')?.value.trim() || '',
                provinsi: document.getElementById('fieldProvinsi')?.value.trim() || '',
            };
        }

        // ── Strategy 1: Structured Search (lebih akurat) ──────────
        function geocodeStructured(fields, onDone) {
            var street = [fields.alamat, fields.kelurahan, fields.kecamatan]
                .filter(Boolean).join(', ');

            // Coba dari paling spesifik ke paling umum
            var attempts = [];

            if (street && fields.kota) {
                attempts.push({
                    street: street,
                    city: fields.kota,
                    state: fields.provinsi
                });
            }

            var streetShort = [fields.kecamatan, fields.kelurahan].filter(Boolean).join(', ');
            if (streetShort && fields.kota) {
                attempts.push({
                    street: streetShort,
                    city: fields.kota,
                    state: fields.provinsi
                });
            }

            if (fields.kota) {
                attempts.push({
                    city: fields.kota,
                    state: fields.provinsi
                });
            }

            function tryStructured(idx) {
                if (idx >= attempts.length) {
                    // Semua structured gagal → fallback free-text
                    var fallback = [fields.alamat, fields.kelurahan, fields.kecamatan, fields.kota, fields.provinsi]
                        .filter(Boolean).join(', ');
                    geocodeFreeText(fallback, onDone);
                    return;
                }

                var base = {
                    format: 'json',
                    limit: 1,
                    countrycodes: 'id'
                };
                var params = Object.assign({}, base, attempts[idx]);

                // Bersihkan key kosong
                Object.keys(params).forEach(function(k) {
                    if (!params[k]) delete params[k];
                });

                var qs = Object.keys(params)
                    .map(function(k) {
                        return encodeURIComponent(k) + '=' + encodeURIComponent(params[k]);
                    }).join('&');

                fetch('https://nominatim.openstreetmap.org/search?' + qs, {
                        headers: {
                            'User-Agent': 'inapin-villa/1.0',
                            'Accept-Language': 'id,en'
                        }
                    })
                    .then(function(res) {
                        return res.json();
                    })
                    .then(function(data) {
                        if (data && data.length > 0) {
                            setPin(parseFloat(data[0].lat), parseFloat(data[0].lon), 17);
                            onDone();
                        } else {
                            tryStructured(idx + 1);
                        }
                    })
                    .catch(function() {
                        tryStructured(idx + 1);
                    });
            }

            tryStructured(0);
        }

        // ── Strategy 2: Free-text fallback ───────────────────────
        function geocodeFreeText(query, onDone) {
            if (!query) {
                onDone();
                return;
            }

            var parts = query.split(',').map(function(s) {
                return s.trim();
            }).filter(Boolean);
            var queries = [query + ', Indonesia'];
            for (var i = 1; i < parts.length - 1; i++) {
                queries.push(parts.slice(i).join(', ') + ', Indonesia');
            }
            if (parts.length >= 2) queries.push(parts.slice(-2).join(', ') + ', Indonesia');
            queries.push(parts[parts.length - 1] + ', Indonesia');

            function tryFree(idx) {
                if (idx >= queries.length) {
                    alert('Lokasi tidak ditemukan. Coba geser pin manual di peta.');
                    onDone();
                    return;
                }
                fetch('https://nominatim.openstreetmap.org/search?format=json&limit=1&countrycodes=id&q=' +
                        encodeURIComponent(queries[idx]), {
                            headers: {
                                'User-Agent': 'inapin-villa/1.0',
                                'Accept-Language': 'id,en'
                            }
                        })
                    .then(function(res) {
                        return res.json();
                    })
                    .then(function(data) {
                        if (data && data.length > 0) {
                            setPin(parseFloat(data[0].lat), parseFloat(data[0].lon), 16);
                            onDone();
                        } else {
                            tryFree(idx + 1);
                        }
                    })
                    .catch(function() {
                        tryFree(idx + 1);
                    });
            }

            tryFree(0);
        }

        // ── Main: cariLokasi() — dipanggil tombol & blur ──────────
        function cariLokasi() {
            var btnCari = document.getElementById('btnCariLokasi');
            btnCari.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i> Mencari...';
            btnCari.disabled = true;

            var onDone = function() {
                btnCari.innerHTML = '<i class="fa fa-search me-1"></i> Cari';
                btnCari.disabled = false;
            };

            var manualQuery = document.getElementById('searchAlamat').value.trim();
            var fields = buatFieldsDariForm();

            // Kalau search box diisi manual (beda dari gabungan form) → free-text
            var formQuery = [fields.alamat, fields.kelurahan, fields.kecamatan, fields.kota, fields.provinsi]
                .filter(Boolean).join(', ');

            if (manualQuery && manualQuery !== formQuery) {
                geocodeFreeText(manualQuery, onDone);
            } else {
                // Dari form → pakai structured search
                geocodeStructured(fields, onDone);
            }
        }

        // ── Auto-geocode saat field Kota di-blur ──────────────────
        var fieldKota = document.getElementById('fieldKota');
        if (fieldKota) {
            fieldKota.addEventListener('blur', function() {
                if (!document.getElementById('inputLatitude').value && this.value.trim()) {
                    var fields = buatFieldsDariForm();
                    document.getElementById('searchAlamat').value = [fields.alamat, fields.kelurahan, fields.kecamatan, fields.kota, fields.provinsi]
                        .filter(Boolean).join(', ');
                    cariLokasi();
                }
            });
        }

        // ── Tombol "Dari Alamat Form" ─────────────────────────────
        document.getElementById('btnGeocodeForm').addEventListener('click', function() {
            var fields = buatFieldsDariForm();
            if (!fields.kota) {
                alert('Isi minimal kolom Kota/Kabupaten terlebih dahulu.');
                return;
            }
            document.getElementById('searchAlamat').value = [fields.alamat, fields.kelurahan, fields.kecamatan, fields.kota, fields.provinsi]
                .filter(Boolean).join(', ');
            cariLokasi();
        });

        // ── Tombol Cari manual ────────────────────────────────────
        document.getElementById('btnCariLokasi').addEventListener('click', cariLokasi);

        document.getElementById('searchAlamat').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                cariLokasi();
            }
        });

        // ── Tombol Reset ──────────────────────────────────────────
        document.getElementById('btnResetPin').addEventListener('click', function() {
            if (pinMarker) mapPicker.removeLayer(pinMarker);
            pinMarker = null;
            document.getElementById('inputLatitude').value = '';
            document.getElementById('inputLongitude').value = '';
            document.getElementById('displayKoordinat').value = '';
            document.getElementById('searchAlamat').value = '';
            mapPicker.setView([-6.2088, 106.8456], 5);
        });

        setTimeout(function() {
            mapPicker.invalidateSize();
        }, 300);
    });

    function formatRupiah(el) {
        var raw = el.value.replace(/\D/g, '');
        el.value = raw ? parseInt(raw).toLocaleString('id-ID') : '';
    }

    function stripRupiah(form) {
        form.querySelectorAll('.input-rupiah').forEach(function(el) {
            el.value = el.value.replace(/\./g, '');
        });
        return true;
    }

    function hapusFoto(id) {
        if (confirm('Yakin ingin menghapus foto ini?')) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ url("owner/villa/foto") }}/' + id;

            var csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';

            var methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';

            form.appendChild(csrfInput);
            form.appendChild(methodInput);
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endpush