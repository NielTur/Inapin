@extends('frontend.v_layouts.app')

@section('title', 'Riwayat Pemesanan - VillaKu')

@section('content')

    {{-- PAGE HEADER --}}
    <div class="container-fluid bg-light py-4 mb-5">
        <div class="container">
            <h1 class="fw-bold mb-2">Riwayat Pemesanan</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb text-uppercase mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('beranda') }}">Beranda</a></li>
                    <li class="breadcrumb-item text-body active">Riwayat Pemesanan</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container-xxl pb-5">
        <div class="container">

            @include('frontend.v_components.alert')

            @forelse($pemesanan as $p)
                @php $detail = $p->detailPemesanan; @endphp
                <div class="bg-light rounded p-4 mb-4 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="row g-4 align-items-center">

                        {{-- Foto Villa --}}
                        <div class="col-md-3">
                            @php $foto = $p->villa->dokumenVilla->where('status', 'disetujui')->first(); @endphp
                            <img src="{{ $foto ? asset('storage/' . $foto->file_path) : asset('frontend/img/property-1.jpg') }}"
                                class="img-fluid rounded w-100" style="height:140px; object-fit:cover;" alt="">
                        </div>

                        {{-- Info Pemesanan --}}
                        <div class="col-md-5">
                            <h5 class="fw-bold mb-1">{{ $p->villa->nama_villa }}</h5>
                            <p class="text-muted small mb-2">
                                <i class="fa fa-map-marker-alt text-primary me-1"></i>{{ $p->villa->kota }}
                            </p>

                            @if($detail)
                                <div class="d-flex gap-3 flex-wrap mb-2">
                                    <span class="small">
                                        <i class="fa fa-sign-in-alt text-primary me-1"></i>
                                        {{ \Carbon\Carbon::parse($detail->tanggal_checkin)->format('d M Y') }}
                                    </span>
                                    <span class="text-muted">→</span>
                                    <span class="small">
                                        <i class="fa fa-sign-out-alt text-primary me-1"></i>
                                        {{ \Carbon\Carbon::parse($detail->tanggal_checkout)->format('d M Y') }}
                                    </span>
                                </div>
                            @endif

                            <p class="small text-muted mb-0">
                                <i class="fa fa-calendar text-primary me-1"></i>
                                Dipesan: {{ $p->tanggal_pemesanan->format('d M Y, H:i') }}
                            </p>
                        </div>

                        {{-- Harga & Status --}}
                        <div class="col-md-4 text-md-end">
                            @if($detail)
                                <h5 class="text-primary fw-bold mb-2">
                                    Rp {{ number_format($detail->sub_total, 0, ',', '.') }}
                                </h5>
                            @endif

                            {{-- Badge Status --}}
                            @php
                                $statusMap = [
                                    'menunggu' => ['bg-warning text-dark', 'Menunggu Pembayaran'],
                                    'dibayar' => ['bg-success text-white', 'Dibayar'],
                                    'checked_in' => ['bg-primary text-white', 'Sedang Menginap'],
                                    'checked_out' => ['bg-info text-white', 'Selesai'],
                                    'dibatalkan' => ['bg-secondary text-white', 'Dibatalkan'],
                                ];
                                $status = $p->status ?? 'menunggu';
                                $badge = $statusMap[$status] ?? ['bg-secondary text-white', ucfirst($status)];
                            @endphp
                            <span class="badge {{ $badge[0] }} px-3 py-2 mb-3">{{ $badge[1] }}</span>

                            <div class="d-flex gap-2 justify-content-md-end flex-wrap">

                                {{-- Menunggu: Lanjut Bayar + Batalkan --}}
                                @if($status === 'menunggu')
                                    <a href="{{ route('booking.bayar', $p->id_pemesanan) }}" class="btn btn-primary btn-sm">
                                        <i class="fa fa-credit-card me-1"></i> Lanjut Bayar
                                    </a>
                                    <form action="{{ route('booking.batal', $p->id_pemesanan) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="fa fa-times me-1"></i> Batalkan
                                        </button>
                                    </form>
                                @endif

                                {{-- Dibayar / Checked In / Checked Out: Lihat Detail --}}
                                @if(in_array($status, ['dibayar', 'checked_in', 'checked_out']))
                                    <a href="{{ route('booking.detail', $p->id_pemesanan) }}"
                                        class="btn btn-outline-primary btn-sm">
                                        <i class="fa fa-eye me-1"></i> Lihat Detail
                                    </a>
                                @endif

                                {{-- Dibatalkan: Hapus --}}
                                @if($status === 'dibatalkan')
                                    <form action="{{ route('booking.hapus', $p->id_pemesanan) }}" method="POST"
                                        onsubmit="return confirm('Hapus riwayat ini secara permanen?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash me-1"></i> Hapus
                                        </button>
                                    </form>
                                @endif

                            </div>
                        </div>

                    </div>
                </div>
            @empty
                <div class="text-center py-5 wow fadeInUp" data-wow-delay="0.1s">
                    <img src="{{ asset('frontend/img/icon-deal.png') }}" alt="" style="width:80px; opacity:.3;">
                    <h5 class="mt-4 text-muted">Belum ada pemesanan</h5>
                    <p class="text-muted">Yuk cari dan pesan villa impian kamu sekarang!</p>
                    <a href="{{ route('villa.index') }}" class="btn btn-primary px-5 py-3 mt-2">
                        <i class="fa fa-search me-2"></i> Cari Villa
                    </a>
                </div>
            @endforelse

        </div>
    </div>

@endsection