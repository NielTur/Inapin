@extends('backend.v_layouts.app')

@section('title', 'Dashboard - Admin VillaKu')

@section('content')

<div class="row page-titles mx-0">
    <div class="col p-0">
        <h4 class="font-medium mb-0">Dashboard</h4>
    </div>
</div>

{{-- Stat Cards --}}
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row">
                    <div class="round round-lg align-self-center round-info">
                        <i class="mdi mdi-account-multiple"></i>
                    </div>
                    <div class="ml-3 align-self-center">
                        <h3 class="mb-0 font-weight-bold">{{ $totalCustomer }}</h3>
                        <span class="text-muted">Total Tamu</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row">
                    <div class="round round-lg align-self-center round-warning">
                        <i class="mdi mdi-account-key"></i>
                    </div>
                    <div class="ml-3 align-self-center">
                        <h3 class="mb-0 font-weight-bold">{{ $totalOwner }}</h3>
                        <span class="text-muted">Total Owner</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row">
                    <div class="round round-lg align-self-center round-danger">
                        <i class="mdi mdi-home-city"></i>
                    </div>
                    <div class="ml-3 align-self-center">
                        <h3 class="mb-0 font-weight-bold">{{ $totalVilla }}</h3>
                        <span class="text-muted">Total Villa</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row">
                    <div class="round round-lg align-self-center round-success">
                        <i class="mdi mdi-receipt"></i>
                    </div>
                    <div class="ml-3 align-self-center">
                        <h3 class="mb-0 font-weight-bold">{{ $totalPesanan }}</h3>
                        <span class="text-muted">Total Pesanan</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Pesanan Terbaru --}}
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Pesanan Terbaru</h4>
                <div class="table-responsive">
                    <table id="zero_config" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Villa</th>
                                <th>Tamu</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pesananTerbaru as $i => $p)
                            @php $detail = $p->detailPemesanan; @endphp
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $p->villa->nama_villa ?? '-' }}</td>
                                <td>{{ $p->customer->nama ?? '-' }}</td>
                                <td>{{ $detail ? \Carbon\Carbon::parse($detail->tanggal_checkin)->format('d M Y') : '-' }}</td>
                                <td>{{ $detail ? \Carbon\Carbon::parse($detail->tanggal_checkout)->format('d M Y') : '-' }}</td>
                                <td>Rp {{ $detail ? number_format($detail->sub_total, 0, ',', '.') : '-' }}</td>
                                <td>
                                    @php
                                    $badge = ['menunggu'=>'warning','dibayar'=>'success','checked_in'=>'primary','checked_out'=>'info','dibatalkan'=>'secondary'];
                                    $label = ['menunggu'=>'Menunggu','dibayar'=>'Dibayar','checked_in'=>'Menginap','checked_out'=>'Selesai','dibatalkan'=>'Dibatalkan'];
                                    $status = $p->status ?? 'menunggu';
                                    @endphp
                                    <span class="badge badge-{{ $badge[$status] ?? 'secondary' }}">
                                        {{ $label[$status] ?? ucfirst($status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection