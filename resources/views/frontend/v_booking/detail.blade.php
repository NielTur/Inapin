@extends('frontend.v_layouts.app')

@section('title', 'Detail Transaksi #' . $pemesanan->id_pemesanan . ' - VillaKu')

@section('content')

{{-- PAGE HEADER --}}
<div class="container-fluid bg-light py-4 mb-5">
    <div class="container">
        <h4 class="fw-bold mb-1">Detail Transaksi</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size:13px;">
                <li class="breadcrumb-item"><a href="{{ route('beranda') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('booking.riwayat') }}">Riwayat Pemesanan</a></li>
                <li class="breadcrumb-item active">Detail Transaksi</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container pb-5">
    <div class="row g-4 justify-content-center">

        {{-- ══ KIRI: Ringkasan Pemesanan ══════════════════════ --}}
        <div class="col-lg-7">

            {{-- Status Banner --}}
            @php
            $statusConfig = [
            'dibayar' => ['bg' => '#ecfdf5', 'color' => '#10b981', 'icon' => 'fa-check-circle', 'label' => 'Pembayaran Diterima', 'sub' => 'Menunggu konfirmasi check-in dari owner.'],
            'checked_in' => ['bg' => '#eff6ff', 'color' => '#3b82f6', 'icon' => 'fa-home', 'label' => 'Sedang Menginap', 'sub' => 'Selamat Menikmati Villa Anda!'],
            'checked_out' => ['bg' => '#f0fdf4', 'color' => '#16a34a', 'icon' => 'fa-flag-checkered', 'label' => 'Selesai Menginap', 'sub' => 'Terima kasih telah menginap bersama kami.'],
            'dibatalkan' => ['bg' => '#f9fafb', 'color' => '#6b7280', 'icon' => 'fa-ban', 'label' => 'Pemesanan Dibatalkan', 'sub' => 'Pemesanan ini telah dibatalkan.'],
            ];
            $sc = $statusConfig[$pemesanan->status] ?? $statusConfig['dibatalkan'];
            @endphp
            <div class="rounded-4 p-4 mb-4 d-flex align-items-center gap-3"
                style="background:{{ $sc['bg'] }}; border:1px solid {{ $sc['color'] }}30;">
                <i class="fa {{ $sc['icon'] }} fa-2x" style="color:{{ $sc['color'] }};"></i>
                <div>
                    <div class="fw-bold" style="color:{{ $sc['color'] }}; font-size:16px;">{{ $sc['label'] }}</div>
                    <div class="text-muted small">{{ $sc['sub'] }}</div>
                    <div class="text-muted small">
                        Dipesan pada {{ $pemesanan->tanggal_pemesanan->format('d M Y, H:i') }} WIB
                    </div>
                </div>
            </div>

            {{-- Info Pemesanan --}}
            <div class="bg-white rounded-4 shadow-sm p-4 mb-4" style="border:1px solid #f0f0f0;">
                <h6 class="fw-bold mb-4 d-flex align-items-center gap-2">
                    <span class="rounded-3 d-flex align-items-center justify-content-center"
                        style="background:#edf7f3; color:var(--primary); width:30px; height:30px;">
                        <i class="fa fa-receipt" style="font-size:12px;"></i>
                    </span>
                    Ringkasan Pemesanan
                </h6>

                {{-- Villa --}}
                <div class="d-flex gap-3 mb-4 pb-4" style="border-bottom:1px solid #f4f4f4;">
                    @php $foto = $pemesanan->villa->dokumenVilla?->where('status', 'disetujui')->first()?->file_path; @endphp
                    <img src="{{ $foto ? asset('storage/' . $foto) : asset('frontend/img/property-1.jpg') }}"
                        class="rounded-3 flex-shrink-0" style="width:80px; height:65px; object-fit:cover;">
                    <div>
                        <div class="fw-semibold" style="font-size:15px;">{{ $pemesanan->villa->nama_villa }}</div>
                        <div class="text-muted small">
                            <i class="fa fa-map-marker-alt me-1 text-primary" style="font-size:10px;"></i>
                            {{ $pemesanan->villa->kota }}
                        </div>
                        <div class="text-muted small mt-1">
                            {{ Str::limit($pemesanan->villa->alamat, 50) }}
                        </div>
                    </div>
                </div>

                {{-- Detail baris --}}
                @php $detail = $pemesanan->detailPemesanan; @endphp
                <div class="row g-3" style="font-size:14px;">
                    <div class="col-6">
                        <div class="text-muted small mb-1">No. Pemesanan</div>
                        <div class="fw-semibold">#{{ str_pad($pemesanan->id_pemesanan, 6, '0', STR_PAD_LEFT) }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small mb-1">Nama Tamu</div>
                        <div class="fw-semibold">{{ $pemesanan->nama_tamu ?? $pemesanan->customer->nama }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small mb-1">Metode Pembayaran</div>
                        <div class="fw-semibold">{{ ucfirst(str_replace('_', ' ', $pemesanan->metode_pembayaran)) }}
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small mb-1">Check-in</div>
                        <div class="fw-semibold">{{ \Carbon\Carbon::parse($detail->tanggal_checkin)->format('d M Y') }}
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small mb-1">Check-out</div>
                        <div class="fw-semibold">{{ \Carbon\Carbon::parse($detail->tanggal_checkout)->format('d M Y') }}
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small mb-1">Durasi</div>
                        @php $malam = \Carbon\Carbon::parse($detail->tanggal_checkin)->diffInDays($detail->tanggal_checkout); @endphp
                        <div class="fw-semibold">{{ $malam }} Malam</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small mb-1">Harga per Malam</div>
                        <div class="fw-semibold">Rp {{ number_format($detail->harga_default, 0, ',', '.') }}</div>
                    </div>
                </div>

                {{-- Total --}}
                <div class="mt-4 pt-3 d-flex justify-content-between align-items-center"
                    style="border-top:2px dashed #e9ecef;">
                    <span class="fw-semibold">Total Pembayaran</span>
                    <span class="fw-bold text-primary" style="font-size:20px;">
                        Rp {{ number_format($detail->sub_total, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('booking.riwayat') }}" class="btn btn-primary px-4 rounded-3">
                    <i class="fa fa-list me-2"></i> Riwayat Pemesanan
                </a>
                <a href="{{ route('villa.detail', $pemesanan->id_villa) }}"
                    class="btn btn-outline-primary px-4 rounded-3">
                    <i class="fa fa-home me-2"></i> Lihat Villa
                </a>
                <a href="{{ route('beranda') }}" class="btn btn-outline-secondary px-4 rounded-3">
                    <i class="fa fa-search me-2"></i> Cari Villa Lain
                </a>
            </div>

        </div>

        {{-- ══ KANAN: Info Status ═════════════════════════════ --}}
        <div class="col-lg-5">
            <div class="bg-white rounded-4 shadow-sm p-4 sticky-top" style="border:1px solid #f0f0f0; top:80px;">

                <h6 class="fw-bold mb-4 d-flex align-items-center gap-2">
                    <span class="rounded-3 d-flex align-items-center justify-content-center"
                        style="background:#edf7f3; color:var(--primary); width:30px; height:30px;">
                        <i class="fa fa-info-circle" style="font-size:12px;"></i>
                    </span>
                    Status Pemesanan
                </h6>

                {{-- Timeline Status --}}
                @php
                $steps = [
                ['status' => 'menunggu', 'label' => 'Menunggu Pembayaran', 'icon' => 'fa-clock'],
                ['status' => 'dibayar', 'label' => 'Pembayaran Diterima', 'icon' => 'fa-check-circle'],
                ['status' => 'checked_in', 'label' => 'Check-in', 'icon' => 'fa-sign-in-alt'],
                ['status' => 'checked_out', 'label' => 'Check-out / Selesai', 'icon' => 'fa-flag-checkered'],
                ];
                $statusOrder = ['menunggu' => 0, 'dibayar' => 1, 'checked_in' => 2, 'checked_out' => 3];
                $currentOrder = $statusOrder[$pemesanan->status] ?? -1;
                @endphp

                @if($pemesanan->status !== 'dibatalkan')
                <div class="mb-4">
                    @foreach($steps as $step)
                    @php $stepOrder = $statusOrder[$step['status']] ?? 0; @endphp
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width:36px; height:36px;
                                                                                   background:{{ $stepOrder <= $currentOrder ? 'var(--primary)' : '#e9ecef' }};
                                                                                   color:{{ $stepOrder <= $currentOrder ? '#fff' : '#adb5bd' }};">
                            <i class="fa {{ $step['icon'] }}" style="font-size:13px;"></i>
                        </div>
                        <span class="small {{ $stepOrder <= $currentOrder ? 'fw-semibold text-dark' : 'text-muted' }}">
                            {{ $step['label'] }}
                        </span>
                        @if($stepOrder === $currentOrder)
                        <span class="badge bg-primary ms-auto" style="font-size:10px;">Sekarang</span>
                        @endif
                    </div>
                    @endforeach
                </div>
                @else
                <div class="alert alert-secondary rounded-3 mb-4">
                    <i class="fa fa-ban me-2"></i> Pemesanan ini telah dibatalkan.
                </div>
                @endif

                {{-- Info kontak owner --}}
                @if(in_array($pemesanan->status, ['dibayar', 'checked_in']))
                <hr>
                <p class="small fw-semibold mb-2">Hubungi Owner Villa</p>
                <p class="small text-muted mb-0">
                    Jika ada pertanyaan, hubungi owner langsung melalui WhatsApp villa.
                </p>
                @if($pemesanan->villa->whatsapp)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $pemesanan->villa->whatsapp) }}" target="_blank"
                    class="btn btn-success btn-sm w-100 mt-2">
                    <i class="fab fa-whatsapp me-2"></i> WhatsApp Owner
                </a>
                @endif
                @endif

            </div>
        </div>

    </div>
</div>

@endsection