@extends('owner.v_layouts.app')

@section('title', 'Pembayaran Pesanan - Panel Owner')
@section('page-title', 'Pembayaran Midtrans')

@push('scripts')
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="bg-white rounded p-5 text-center shadow-sm">
            <h4 class="fw-bold mb-3">Selesaikan Pembayaran</h4>
            <p class="text-muted mb-4">
                Tunjukkan layar ini kepada customer atau buka popup pembayaran untuk menyelesaikan transaksi via Midtrans.
            </p>

            <div class="bg-light p-3 rounded mb-4 text-start">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Nama Tamu</span>
                    <span class="fw-semibold">{{ $pemesanan->nama_tamu }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Villa</span>
                    <span class="fw-semibold">{{ $pemesanan->villa->nama_villa }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Total Tagihan</span>
                    <span class="fw-bold text-primary">Rp {{ number_format($pemesanan->detailPemesanan->sub_total, 0, ',', '.') }}</span>
                </div>
            </div>

            <button id="pay-button" class="btn btn-primary w-100 py-3 fw-bold mb-3">
                <i class="fa fa-qrcode me-2"></i> Buka Layar Pembayaran (QRIS / Transfer)
            </button>

            <form action="{{ route('owner.pesanan.batal', $pemesanan->id_pemesanan) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('Yakin ingin membatalkan pesanan ini?')">Batalkan Pesanan</button>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script type="text/javascript">
    document.getElementById('pay-button').onclick = function() {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) {
                window.location.href = "{{ route('owner.pesanan.index') }}?status=dibayar";
            },
            onPending: function(result) {
                alert("Menunggu pembayaran Anda!");
            },
            onError: function(result) {
                alert("Pembayaran gagal!");
            },
            onClose: function() {
                console.log('Customer closed the popup without finishing the payment');
            }
        });
    };
</script>
@endpush