@extends('backend.v_layouts.app')

@section('title', 'Detail Villa - Admin')
@section('page-title', 'Detail Villa')

@section('content')

<div class="mb-3">
    <a href="{{ route('admin.villa.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fa fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="row g-4">

    {{-- Kolom Kiri --}}
    <div class="col-md-8">

        {{-- Info Villa --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3">{{ $villa->nama_villa }}</h5>
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="text-muted" style="width:140px;">Owner</td>
                        <td>{{ $villa->owner->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Kota</td>
                        <td>{{ $villa->kota }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Alamat</td>
                        <td>{{ $villa->alamat ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Harga</td>
                        <td>Rp {{ number_format($villa->harga, 0, ',', '.') }} / malam</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Kapasitas</td>
                        <td>{{ $villa->kapasitas }} orang</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Kamar</td>
                        <td>{{ $villa->jumlah_kamar }} kamar, {{ $villa->jumlah_kamar_mandi }} kamar mandi</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td>
                            @if($villa->status == 'pending')
                                <span class="badge bg-warning text-dark">Menunggu Review</span>
                            @elseif($villa->status == 'disetujui')
                                <span class="badge bg-success">Disetujui</span>
                            @elseif($villa->status == 'ditolak')
                                <span class="badge bg-danger">Ditolak</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Deskripsi</td>
                        <td>{{ $villa->deskripsi ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Fasilitas --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Fasilitas</h6>
                @if($villa->fasilitasVilla->count())
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($villa->fasilitasVilla as $fas)
                            <span class="badge bg-light text-dark border">{{ $fas->fasilitas }}</span>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0">Tidak ada fasilitas.</p>
                @endif
            </div>
        </div>

        {{-- Foto Villa --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Foto Villa</h6>
                @if($villa->dokumenVilla->count())
                    <div class="row g-2">
                        @foreach($villa->dokumenVilla as $foto)
                            <div class="col-4">
                                <img src="{{ asset('storage/' . $foto->file_path) }}"
                                    class="img-fluid rounded"
                                    style="height:150px; width:100%; object-fit:cover;">
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0">Tidak ada foto.</p>
                @endif
            </div>
        </div>

    </div>

    {{-- Kolom Kanan - Keputusan Admin --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm sticky-top" style="top:80px;">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Keputusan Admin</h6>

                @if($villa->status == 'pending')

                    {{-- Setujui --}}
                    <form action="{{ route('admin.villa.setujui', $villa->id_villa) }}" method="POST" class="mb-3">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fa fa-check me-1"></i> Setujui Villa
                        </button>
                    </form>

                    <hr>

                    {{-- Tolak --}}
                    <form action="{{ route('admin.villa.tolak', $villa->id_villa) }}" method="POST">
                        @csrf @method('PATCH')
                        <div class="mb-2">
                            <label class="form-label small fw-semibold">
                                Alasan Penolakan <span class="text-danger">*</span>
                            </label>
                            <textarea name="catatan_admin"
                                class="form-control @error('catatan_admin') is-invalid @enderror"
                                rows="3"
                                placeholder="Tulis alasan penolakan..."
                                required>{{ old('catatan_admin') }}</textarea>
                            @error('catatan_admin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fa fa-times me-1"></i> Tolak Villa
                        </button>
                    </form>

                @elseif($villa->status == 'disetujui')

                    <div class="alert alert-success">
                        <i class="fa fa-check-circle me-1"></i> Villa ini sudah disetujui.
                    </div>
                    <form action="{{ route('admin.villa.nonaktifkan', $villa->id_villa) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-warning w-100">
                            <i class="fa fa-ban me-1"></i> Nonaktifkan
                        </button>
                    </form>

                @elseif($villa->status == 'ditolak')

                    <div class="alert alert-danger">
                        <i class="fa fa-times-circle me-1"></i> Villa ini ditolak.
                        @if($villa->catatan_admin)
                            <hr><small>{{ $villa->catatan_admin }}</small>
                        @endif
                    </div>

                @elseif($villa->status == 'nonaktif')

                    <div class="alert alert-secondary">
                        <i class="fa fa-ban me-1"></i> Villa nonaktif.
                    </div>
                    <form action="{{ route('admin.villa.aktifkan', $villa->id_villa) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fa fa-check me-1"></i> Aktifkan Kembali
                        </button>
                    </form>

                @endif

            </div>
        </div>
    </div>

</div>

@endsection