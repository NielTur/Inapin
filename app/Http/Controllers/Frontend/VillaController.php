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
        $query = Villa::where('status', 'disetujui')
            ->where('tersedia', true)
            ->with(['fasilitasVilla', 'dokumenVilla', 'owner']);

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

        $sort = $request->get('sort', 'terbaru');
        match ($sort) {
            'harga_asc' => $query->orderBy('harga', 'asc'),
            'harga_desc' => $query->orderBy('harga', 'desc'),
            'rating' => $query->orderByDesc('ulasan'),
            default => $query->latest(),
        };

        $villas = $query->paginate(9)->withQueryString();

        $kotaList = Villa::where('status', 'disetujui')
            ->where('tersedia', true)
            ->selectRaw('kota, COUNT(*) as total')
            ->groupBy('kota')
            ->orderByDesc('total')
            ->pluck('kota')
            ->take(5);

        $hargaMin = Villa::where('status', 'disetujui')->where('tersedia', true)->min('harga') ?? 0;
        $hargaMax = Villa::where('status', 'disetujui')->where('tersedia', true)->max('harga') ?? 10000000;
        $totalVilla = Villa::where('status', 'disetujui')->where('tersedia', true)->count();

        return view('frontend.v_villa.index', compact(
            'villas',
            'kotaList',
            'hargaMin',
            'hargaMax',
            'totalVilla',
            'sort'
        ));
    }

    public function detail($id): View
    {
        $villa = Villa::where('id_villa', $id)
            ->where('status', 'disetujui')
            ->with(['fasilitasVilla', 'dokumenVilla'])
            ->firstOrFail();

        return view('frontend.v_villa.detail', compact('villa'));
    }
}