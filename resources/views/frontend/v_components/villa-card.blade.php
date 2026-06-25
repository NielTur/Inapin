{{--
    Komponen: villa-card.blade.php
    Cara pakai: @include('frontend.v_components.villa-card', ['villa' => $villa])
--}}

@php $isHabis = ($searchHasTanggal ?? false) && ($villa->is_booked ?? false); @endphp

<div class="property-item rounded overflow-hidden">

    {{-- Gambar Villa --}}
    <div class="position-relative overflow-hidden">
        <a href="{{ route('villa.detail', array_merge(['id' => $villa->id_villa], array_filter(request()->only(['checkin', 'checkout'])))) }}">
            @if($villa->dokumenVilla && $villa->dokumenVilla->where('status', 'disetujui')->first())
            <img class="img-fluid w-100"
                src="{{ asset('storage/' . $villa->dokumenVilla->where('status', 'disetujui')->first()->file_path) }}"
                alt="{{ $villa->nama_villa }}"
                style="height: 220px; object-fit: cover; {{ $isHabis ? 'filter: brightness(0.55);' : '' }}">
            @else
            <img class="img-fluid w-100"
                src="{{ asset('frontend/img/property-1.jpg') }}"
                alt="{{ $villa->nama_villa }}"
                style="height: 220px; object-fit: cover; {{ $isHabis ? 'filter: brightness(0.55);' : '' }}">
            @endif
        </a>

        {{-- Overlay "Habis!" — hanya saat search dengan tanggal & villa sudah di-booking --}}
        @if($isHabis)
        <a href="{{ route('villa.detail', array_merge(['id' => $villa->id_villa], array_filter(request()->only(['checkin', 'checkout'])))) }}"
           class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center text-decoration-none"
           style="z-index: 2;">
            <span style="font-size: 32px; line-height: 1; filter: drop-shadow(0 2px 4px rgba(0,0,0,.5));">🚫</span>
            <span class="fw-bold text-white mt-1" style="font-size: 1.1rem; text-shadow: 0 2px 6px rgba(0,0,0,.7); letter-spacing: .5px;">Habis!</span>
        </a>
        @endif

        {{-- Badge Kota --}}
        <div class="bg-primary rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3" style="z-index: 3;">
            {{ $villa->kota }}
        </div>
        {{-- Badge Status --}}
        <div class="bg-white rounded-top text-primary position-absolute start-0 bottom-0 mx-4 pt-1 px-3" style="z-index: 3;">
            Sewa Villa
        </div>
    </div>

    {{-- Info Villa --}}
    <div class="p-4 pb-0">
        <h5 class="{{ $isHabis ? 'text-muted' : 'text-primary' }} mb-3">
            Rp {{ number_format($villa->harga, 0, ',', '.') }}
            <small class="text-muted fs-6 fw-normal">/ malam</small>
        </h5>
        <a class="d-block h5 mb-2 text-decoration-none text-dark"
            href="{{ route('villa.detail', array_merge(['id' => $villa->id_villa], array_filter(request()->only(['checkin', 'checkout'])))) }}">
            {{ $villa->nama_villa }}
        </a>
        @if($isHabis)
        <span class="badge bg-danger text-white fw-normal mb-2" style="font-size: 0.75rem;">
            <i class="fa fa-calendar-times me-1"></i> Tidak tersedia untuk tanggal ini
        </span>
        @endif
        <p class="text-muted mb-0">
            <i class="fa fa-map-marker-alt text-primary me-2"></i>
            {{ Str::limit($villa->alamat, 40) }}
        </p>
    </div>

    {{-- Footer Card: Fasilitas Ringkas --}}
    <div class="d-flex border-top mt-3">
        <small class="flex-fill text-center border-end py-2">
            <i class="fa fa-users text-primary me-2"></i>{{ $villa->kapasitas }} Tamu
        </small>
        <small class="flex-fill text-center border-end py-2">
            @php $jumlahFasilitas = $villa->fasilitasVilla ? $villa->fasilitasVilla->count() : 0; @endphp
            <i class="fa fa-list text-primary me-2"></i>{{ $jumlahFasilitas }} Fasilitas
        </small>
        <small class="flex-fill text-center py-2">
            <i class="fa fa-star text-primary me-2"></i>
            {{ $villa->ulasan ?? 'Baru' }}
        </small>
    </div>

</div>