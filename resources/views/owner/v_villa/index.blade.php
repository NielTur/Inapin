@extends('owner.v_layouts.app')

@section('title', 'Villa Saya - Panel Owner')
@section('page-title', 'Villa Saya')

@section('content')

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1">Daftar Villa</h5>
        <p class="text-muted small mb-0">Kelola semua villa milik Anda</p>
    </div>
    <a href="{{ route('owner.villa.create') }}" class="btn btn-primary">
        <i class="fa fa-plus me-2"></i> Tambah Villa
    </a>
</div>

{{-- Table --}}
<div class="bg-white rounded p-4 wow fadeInUp" data-wow-delay="0.1s">
    @if($villas->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th width="50">#</th>
                    <th>Foto</th>
                    <th>Nama Villa</th>
                    <th>Kota</th>
                    <th>Harga/Malam</th>
                    <th>Kapasitas</th>
                    <th>Status</th>
                    <th width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($villas as $i => $villa)
                @php $foto = $villa->dokumenVilla->where('status','disetujui')->first(); @endphp
                <tr>
                    <td class="text-muted small">{{ $villas->firstItem() + $i }}</td>
                    <td>
                        <img src="{{ $foto ? asset('storage/'.$foto->file_path) : asset('frontend/img/property-1.jpg') }}"
                            class="rounded" style="width:60px; height:45px; object-fit:cover;" alt="">
                    </td>
                    <td>
                        <div class="fw-semibold">{{ $villa->nama_villa }}</div>
                        <small class="text-muted">{{ $villa->fasilitasVilla->count() }} fasilitas</small>
                    </td>
                    <td>{{ $villa->kota }}</td>
                    <td class="fw-semibold text-primary">
                        Rp {{ number_format($villa->harga, 0, ',', '.') }}
                    </td>
                    <td>{{ $villa->kapasitas }} tamu</td>
                    <td>
                        @if($villa->status === 'disetujui')
                        <span class="badge bg-success px-2 py-1">Aktif</span>
                        @elseif($villa->status === 'pending')
                        <span class="badge bg-warning text-dark px-2 py-1">Menunggu Review</span>
                        @elseif($villa->status === 'ditolak')
                        <span class="badge bg-danger px-2 py-1">Ditolak</span>
                        @if($villa->catatan_admin)
                        <div class="mt-1" style="font-size:11px; max-width:180px;">
                            <span class="text-danger"
                                data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="{{ $villa->catatan_admin }}">
                                <i class="fa fa-exclamation-circle me-1"></i>
                                {{ Str::limit($villa->catatan_admin, 40) }}
                            </span>
                        </div>
                        @endif
                        @else
                        <span class="badge bg-secondary px-2 py-1">Nonaktif</span>
                        @endif

                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('villa.detail', $villa->id_villa) }}"
                                class="btn btn-sm btn-outline-info" target="_blank"
                                title="Lihat di website">
                                <i class="fa fa-eye"></i>
                            </a>
                            <a href="{{ route('owner.villa.edit', $villa->id_villa) }}"
                                class="btn btn-sm btn-outline-primary"
                                title="Edit">
                                <i class="fa fa-edit"></i>
                            </a>
                            <form action="{{ route('owner.villa.destroy', $villa->id_villa) }}"
                                method="POST"
                                onsubmit="return confirm('Yakin hapus villa ini? Semua data termasuk foto akan ikut terhapus.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($villas->hasPages())
    <div class="mt-3">{{ $villas->links() }}</div>
    @endif

    @else
    <div class="text-center py-5">
        <i class="fa fa-home fa-3x text-muted mb-3"></i>
        <h6 class="text-muted">Belum ada villa</h6>
        <p class="text-muted small">Mulai tambahkan villa pertama Anda sekarang.</p>
        <a href="{{ route('owner.villa.create') }}" class="btn btn-primary mt-2">
            <i class="fa fa-plus me-2"></i> Tambah Villa
        </a>
    </div>
    @endif
</div>

@endsection