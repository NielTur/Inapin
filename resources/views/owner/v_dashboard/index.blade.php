@extends('owner.v_layouts.app')

@section('title', 'Owner Dashboard')
@section('page-title', 'Dashboard')

@section('content')

    {{-- Stat Cards --}}
    <div class="row g-4 mb-4">

        <div class="col-md-6 col-xl-3 wow fadeInUp" data-wow-delay="0.1s">
            <div class="bg-white rounded p-4 h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small mb-1">Total Villa</p>
                        <h3 class="fw-bold mb-0">{{ $totalVilla }}</h3>
                        <small class="text-success">{{ $villaAktif }} aktif</small>
                    </div>
                    <div class="bg-primary bg-opacity-10 rounded p-3">
                        <i class="fa fa-home fa-lg text-primary"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 wow fadeInUp" data-wow-delay="0.2s">
            <div class="bg-white rounded p-4 h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small mb-1">Menunggu Konfirmasi</p>
                        <h3 class="fw-bold mb-0">{{ $pesananMenunggu }}</h3>
                        <small class="text-warning">Perlu ditindaklanjuti</small>
                    </div>
                    <div class="bg-warning bg-opacity-10 rounded p-3">
                        <i class="fa fa-clock fa-lg text-warning"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 wow fadeInUp" data-wow-delay="0.3s">
            <div class="bg-white rounded p-4 h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small mb-1">Total Pesanan</p>
                        <h3 class="fw-bold mb-0">{{ $totalPesanan }}</h3>
                        <small class="text-success">{{ $pesananKonfirmasi }} dikonfirmasi</small>
                    </div>
                    <div class="bg-success bg-opacity-10 rounded p-3">
                        <i class="fa fa-clipboard-list fa-lg text-success"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 wow fadeInUp" data-wow-delay="0.4s">
            <div class="bg-white rounded p-4 h-100 border-start border-info border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small mb-1">Total Pendapatan</p>
                        <h3 class="fw-bold mb-0" style="font-size:1.3rem;">
                            Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                        </h3>
                        <small class="text-info">Dari pesanan dikonfirmasi</small>
                    </div>
                    <div class="bg-info bg-opacity-10 rounded p-3">
                        <i class="fa fa-wallet fa-lg text-info"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Pesanan Terbaru --}}
    <div class="bg-white rounded p-4 wow fadeInUp" data-wow-delay="0.1s">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0">Pesanan Terbaru</h5>
            <a href="{{ route('owner.pesanan.index') }}" class="btn btn-outline-primary btn-sm">
                Lihat Semua
            </a>
        </div>

        @if($pesananTerbaru->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Villa</th>
                            <th>Tamu</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pesananTerbaru as $p)
                            @php $detail = $p->detailPemesanan; @endphp
                            <tr>
                                <td class="fw-semibold">{{ $p->villa->nama_villa }}</td>
                                <td>
                                    <div>{{ $p->customer->nama }}</div>
                                    <small class="text-muted">{{ $p->customer->phone }}</small>
                                </td>
                                <td>{{ $detail ? \Carbon\Carbon::parse($detail->tanggal_checkin)->format('d M Y') : '-' }}</td>
                                <td>{{ $detail ? \Carbon\Carbon::parse($detail->tanggal_checkout)->format('d M Y') : '-' }}</td>
                                <td class="fw-semibold text-primary">
                                    Rp {{ $detail ? number_format($detail->sub_total, 0, ',', '.') : '-' }}
                                </td>
                                <td>
                                    @php
                                        $badgeMap = [
                                            'menunggu' => 'bg-warning text-dark',
                                            'dikonfirmasi' => 'bg-success',
                                            'ditolak' => 'bg-danger',
                                            'dibatalkan' => 'bg-secondary',
                                            'selesai' => 'bg-info',
                                        ];
                                        $status = $p->status ?? 'menunggu';
                                    @endphp
                                    <span class="badge {{ $badgeMap[$status] ?? 'bg-secondary' }} px-2 py-1">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('owner.pesanan.index') }}" class="btn btn-sm btn-outline-primary">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fa fa-clipboard-list fa-3x text-muted mb-3"></i>
                <p class="text-muted">Belum ada pesanan masuk.</p>
            </div>
        @endif
    </div>

@endsection