@extends('owner.v_layouts.app')

@section('title', 'Pesanan Masuk - Panel Owner')
@section('page-title', 'Pesanan Masuk')

@section('content')

{{-- Top Action & Filter Tab --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div class="d-flex gap-2 flex-wrap">
        @php
        $statusList = [
        '' => 'Semua',
        'menunggu' => 'Menunggu Bayar',
        'dibayar' => 'Dibayar',
        'checked_in' => 'Menginap',
        'checked_out' => 'Selesai',
        'dibatalkan' => 'Dibatalkan',
        ];
        @endphp
        @foreach($statusList as $val => $label)
        <a href="{{ route('owner.pesanan.index', $val ? ['status' => $val] : []) }}"
            class="btn btn-sm {{ request('status') === $val ? 'btn-primary' : 'btn-outline-secondary' }}">
            {{ $label }}
            @if($val === 'menunggu' && $totalMenunggu > 0)
            <span class="badge bg-danger ms-1">{{ $totalMenunggu }}</span>
            @endif
        </a>
        @endforeach
    </div>
    <div>
        <a href="{{ route('owner.pesanan.create') }}" class="btn btn-primary fw-semibold">
            <i class="fa fa-plus me-1"></i> Pesan
        </a>
    </div>
</div>

{{-- Tabel Pesanan --}}
<div class="bg-white rounded p-4 wow fadeInUp" data-wow-delay="0.1s">
    @if($pesanan->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Villa</th>
                    <th>Tamu</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Malam</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pesanan as $i => $p)
                @php
                $detail = $p->detailPemesanan;
                $malam = $detail
                ? \Carbon\Carbon::parse($detail->tanggal_checkin)->diffInDays($detail->tanggal_checkout)
                : 0;
                $badgeMap = [
                'menunggu' => 'bg-warning text-dark',
                'dibayar' => 'bg-success',
                'checked_in' => 'bg-primary',
                'checked_out' => 'bg-info',
                'dibatalkan' => 'bg-secondary',
                ];
                $labelMap = [
                'menunggu' => 'Menunggu Bayar',
                'dibayar' => 'Dibayar',
                'checked_in' => 'Menginap',
                'checked_out' => 'Selesai',
                'dibatalkan' => 'Dibatalkan',
                ];
                $status = $p->status ?? 'menunggu';
                @endphp
                <tr>
                    <td class="text-muted small">{{ $pesanan->firstItem() + $i }}</td>
                    <td class="fw-semibold">{{ $p->villa->nama_villa }}</td>
                    <td>
                        <div class="fw-medium">{{ $p->nama_tamu ?? $p->customer->nama }}</div>
                        <small class="text-muted">{{ $p->no_hp_tamu ?? $p->customer->phone }}</small>
                    </td>
                    <td>{{ $detail ? \Carbon\Carbon::parse($detail->tanggal_checkin)->format('d M Y') : '-' }}</td>
                    <td>{{ $detail ? \Carbon\Carbon::parse($detail->tanggal_checkout)->format('d M Y') : '-' }}</td>
                    <td>{{ $malam }} malam</td>
                    <td class="fw-semibold text-primary">
                        Rp {{ $detail ? number_format($detail->sub_total, 0, ',', '.') : '-' }}
                    </td>
                    <td>
                        <span class="badge {{ $badgeMap[$status] ?? 'bg-secondary' }} px-2 py-1">
                            {{ $labelMap[$status] ?? ucfirst($status) }}
                        </span>
                    </td>
                    <td>
                        {{-- Tombol Check-in: aktif kalau dibayar --}}
                        @if($status === 'dibayar')
                        <form action="{{ route('owner.pesanan.checkin', $p->id_pemesanan) }}" method="POST"
                            onsubmit="return confirm('Konfirmasi check-in {{ addslashes($p->nama_tamu ?? $p->customer->nama) }}?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-success" title="Konfirmasi Check-in">
                                <i class="fa fa-sign-in-alt me-1"></i> Check-in
                            </button>
                        </form>

                        {{-- Tombol Check-out: aktif kalau checked_in --}}
                        @elseif($status === 'checked_in')
                        <form action="{{ route('owner.pesanan.checkout', $p->id_pemesanan) }}" method="POST"
                            onsubmit="return confirm('Konfirmasi check-out {{ addslashes($p->nama_tamu ?? $p->customer->nama) }}?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-primary" title="Konfirmasi Check-out">
                                <i class="fa fa-sign-out-alt me-1"></i> Check-out
                            </button>
                        </form>

                        {{-- Tombol Bayar & Batal: aktif kalau menunggu --}}
                        @elseif($status === 'menunggu')
                        <div class="d-flex gap-1">
                            <a href="{{ route('owner.pesanan.bayarSnap', $p->id_pemesanan) }}" class="btn btn-sm btn-info" title="Buka QRIS">
                                <i class="fa fa-qrcode"></i>
                            </a>
                            <form action="{{ route('owner.pesanan.batal', $p->id_pemesanan) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Batalkan Pesanan">
                                    <i class="fa fa-times"></i>
                                </button>
                            </form>
                        </div>

                        @else
                        <span class="text-muted small">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($pesanan->hasPages())
    <div class="mt-3">{{ $pesanan->links() }}</div>
    @endif

    @else
    <div class="text-center py-5">
        <i class="fa fa-clipboard-list fa-3x text-muted mb-3"></i>
        <h6 class="text-muted">Tidak ada pesanan</h6>
        <p class="text-muted small">
            {{ request('status') ? 'Tidak ada pesanan dengan status ini.' : 'Belum ada pesanan masuk.' }}
        </p>
    </div>
    @endif
</div>

@endsection