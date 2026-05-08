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
        $query = Villa::where('status', 'aktif')
            ->with(['fasilitasVilla', 'dokumenVilla', 'owner']);

        // Filter: Keyword / Nama Villa
        if ($request->filled('kota')) {
            $query->where(function ($q) use ($request) {
                $q->where('kota', 'like', '%' . $request->kota . '%')
                    ->orWhere('nama_villa', 'like', '%' . $request->kota . '%')
                    ->orWhere('alamat', 'like', '%' . $request->kota . '%');
            });
        }

        // Filter: Harga Min
        if ($request->filled('harga_min')) {
            $query->where('harga', '>=', $request->harga_min);
        }

        // Filter: Harga Max
        if ($request->filled('harga_max')) {
            $query->where('harga', '<=', $request->harga_max);
        }

        // Filter: Kapasitas Tamu
        if ($request->filled('tamu')) {
            $query->where('kapasitas', '>=', $request->tamu);
        }

        // Filter: Rating
        if ($request->filled('rating')) {
            $query->where('ulasan', '>=', $request->rating);
        }
        // Filter: Jumlah Kamar
        if ($request->filled('kamar')) {
            $query->where('jumlah_kamar', '>=', $request->kamar);
        }

        // Sorting
        $sort = $request->get('sort', 'terbaru');
        match ($sort) {
            'harga_asc'  => $query->orderBy('harga', 'asc'),
            'harga_desc' => $query->orderBy('harga', 'desc'),
            'rating'     => $query->orderByDesc('ulasan'),
            default      => $query->latest(),
        };

        $villas = $query->paginate(9)->withQueryString();

        // Data untuk filter sidebar
        $kotaList   = Villa::where('status', 'aktif')->distinct()->pluck('kota');
        $hargaMin   = Villa::where('status', 'aktif')->min('harga') ?? 0;
        $hargaMax   = Villa::where('status', 'aktif')->max('harga') ?? 10000000;
        $totalVilla = Villa::where('status', 'aktif')->count();

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
            ->where('status', 'aktif')
            ->with(['fasilitasVilla', 'dokumenVilla'])
            ->firstOrFail();

        return view('frontend.v_villa.detail', compact('villa'));
    }
}
