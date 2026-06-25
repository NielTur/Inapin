<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Villa;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VillaController extends Controller
{
    public function index(Request $request): View
    {
        $searchCheckin      = $request->filled('checkin')  ? $request->checkin  : null;
        $searchCheckout     = $request->filled('checkout') ? $request->checkout : null;
        $searchHasTanggal   = $searchCheckin && $searchCheckout;

        $query = Villa::where('status', 'disetujui')
            ->where('tersedia', true)
            ->with(['fasilitasVilla', 'dokumenVilla', 'owner']);

        // Tambah flag is_booked jika customer search dengan filter tanggal
        if ($searchHasTanggal) {
            $query->withExists(['pemesanan as is_booked' => function ($q) use ($searchCheckin, $searchCheckout) {
                $q->whereIn('status', ['menunggu', 'dibayar', 'checked_in'])
                  ->whereHas('detailPemesanan', function ($sq) use ($searchCheckin, $searchCheckout) {
                      $sq->where('tanggal_checkin', '<', $searchCheckout)
                         ->where('tanggal_checkout', '>', $searchCheckin);
                  });
            }]);
        }

        if ($request->filled('kota')) {
            $query->where(function ($q) use ($request) {
                $q->where('kota', 'like', '%' . $request->kota . '%')
                    ->orWhere('nama_villa', 'like', '%' . $request->kota . '%')
                    ->orWhere('alamat', 'like', '%' . $request->kota . '%');
            });
        }

        if ($request->filled('harga_min')) {
            $query->where('harga', '>=', $request->harga_min);
        }

        if ($request->filled('harga_max')) {
            $query->where('harga', '<=', $request->harga_max);
        }

        if ($request->filled('tamu')) {
            $query->where('kapasitas', '>=', $request->tamu);
        }

        if ($request->filled('rating')) {
            $query->where('ulasan', '>=', $request->rating);
        }

        if ($request->filled('kamar')) {
            $query->where('jumlah_kamar', '>=', $request->kamar);
        }

        // ← BARU: filter kecamatan
        if ($request->filled('kecamatan')) {
            $query->where('kecamatan', $request->kecamatan);
        }

        $sort = $request->get('sort', 'terbaru');
        match ($sort) {
            'harga_asc'  => $query->orderBy('harga', 'asc'),
            'harga_desc' => $query->orderBy('harga', 'desc'),
            'rating'     => $query->orderByDesc('ulasan'),
            default      => $query->latest(),
        };

        $villas = $query->paginate(9)->withQueryString();

        $kotaList = Villa::where('status', 'disetujui')
            ->where('tersedia', true)
            ->selectRaw('kota, COUNT(*) as total')
            ->groupBy('kota')
            ->orderByDesc('total')
            ->pluck('kota');

        // kecamatan list, hanya muncul kalau kota dipilih
        $kecamatanList = collect();
        if ($request->filled('kota')) {
            $kecamatanList = Villa::where('status', 'disetujui')
                ->where('tersedia', true)
                ->where('kota', 'like', '%' . $request->kota . '%')
                ->whereNotNull('kecamatan')
                ->where('kecamatan', '!=', '')
                ->distinct()
                ->orderBy('kecamatan')
                ->pluck('kecamatan');
        }

        $hargaMin   = Villa::where('status', 'disetujui')->where('tersedia', true)->min('harga') ?? 0;
        $hargaMax   = Villa::where('status', 'disetujui')->where('tersedia', true)->max('harga') ?? 10000000;
        $totalVilla = Villa::where('status', 'disetujui')->where('tersedia', true)->count();

        return view('frontend.v_villa.index', compact(
            'villas',
            'kotaList',
            'kecamatanList',
            'hargaMin',
            'hargaMax',
            'totalVilla',
            'sort',
            'searchHasTanggal'
        ));
    }

    public function detail($id, Request $request): View
    {
        $villa = Villa::where('id_villa', $id)
            ->where('status', 'disetujui')
            ->with(['fasilitasVilla', 'dokumenVilla'])
            ->firstOrFail();

        $bookedDates = $villa->getBookedDates();

        // Flow 2: Cek overlap jika customer masuk dari search dengan filter tanggal
        $isFullyBooked  = false;
        $searchCheckin  = $request->query('checkin');
        $searchCheckout = $request->query('checkout');

        if ($searchCheckin && $searchCheckout) {
            $isFullyBooked = $villa->isBookedBetween($searchCheckin, $searchCheckout);
        }

        return view('frontend.v_villa.detail', compact(
            'villa', 'bookedDates', 'isFullyBooked', 'searchCheckin', 'searchCheckout'
        ));
    }
}
