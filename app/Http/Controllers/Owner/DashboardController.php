<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\Villa;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $idOwner = Auth::id();

        $totalVilla = Villa::where('id_owner', $idOwner)->count();
        $villaAktif = Villa::where('id_owner', $idOwner)->where('status', 'disetujui')->count();

        $totalPesanan = Pemesanan::whereHas('villa', fn($q) => $q->where('id_owner', $idOwner))->count();
        $pesananMenunggu = Pemesanan::whereHas('villa', fn($q) => $q->where('id_owner', $idOwner))
            ->where('status', 'menunggu')->count();

        // Aktif = sudah bayar atau sedang menginap
        $pesananAktif = Pemesanan::whereHas('villa', fn($q) => $q->where('id_owner', $idOwner))
            ->whereIn('status', ['dibayar', 'checked_in'])->count();

        // Pendapatan dari semua yang sudah/pernah bayar
        $totalPendapatan = Pemesanan::whereHas('villa', fn($q) => $q->where('id_owner', $idOwner))
            ->whereIn('status', ['dibayar', 'checked_in', 'checked_out'])
            ->with('detailPemesanan')
            ->get()
            ->sum(fn($p) => $p->detailPemesanan?->sub_total ?? 0);

        $pesananTerbaru = Pemesanan::whereHas('villa', fn($q) => $q->where('id_owner', $idOwner))
            ->with(['villa', 'customer', 'detailPemesanan'])
            ->latest()
            ->take(5)
            ->get();

        return view('owner.v_dashboard.index', compact(
            'totalVilla',
            'villaAktif',
            'totalPesanan',
            'pesananMenunggu',
            'pesananAktif',
            'totalPendapatan',
            'pesananTerbaru'
        ));
    }
}