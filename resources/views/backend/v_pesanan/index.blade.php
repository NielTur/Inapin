@extends('backend.v_layouts.app')
@section('title', 'Semua Pesanan - Admin')
@section('page-title', 'Semua Pesanan')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">Semua Pesanan</h5>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Villa</th>
                    <th>Owner</th>
                    <th>Tamu</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesanan as $i => $p)
                @php $detail = $p->detailPemesanan; @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $p->villa->nama_villa ?? '-' }}</td>
                    <td>{{ $p->villa->owner->nama ?? '-' }}</td>
                    <td>{{ $p->customer->nama ?? '-' }}</td>
                    <td>{{ $detail ? \Carbon\Carbon::parse($detail->tanggal_checkin)->format('d M Y') : '-' }}</td>
                    <td>{{ $detail ? \Carbon\Carbon::parse($detail->tanggal_checkout)->format('d M Y') : '-' }}</td>
                    <td>Rp {{ $detail ? number_format($detail->sub_total, 0, ',', '.') : '-' }}</td>
                    <td>
                        @php
                        $badge = ['menunggu'=>'warning','dibayar'=>'success','checked_in'=>'primary','checked_out'=>'info','dibatalkan'=>'danger'];
                        $label = ['menunggu'=>'Menunggu','dibayar'=>'Dibayar','checked_in'=>'Menginap','checked_out'=>'Selesai','dibatalkan'=>'Dibatalkan'];
                        $status = $p->status ?? 'menunggu';
                        @endphp
                        <span class="badge bg-{{ $badge[$status] ?? 'secondary' }}">
                            {{ $label[$status] ?? ucfirst($status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">Tidak ada data pesanan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $pesanan->links() }}</div>

@endsection