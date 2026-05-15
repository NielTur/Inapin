<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PesananController extends Controller
{
    public function index(Request $request): View
    {
        $query = Pemesanan::whereHas('villa', fn($q) => $q->where('id_owner', Auth::id()))
            ->with(['villa', 'customer', 'detailPemesanan'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pesanan = $query->paginate(10)->withQueryString();

        $totalMenunggu = Pemesanan::whereHas('villa', fn($q) => $q->where('id_owner', Auth::id()))
            ->where('status', 'menunggu')->count();

        return view('owner.v_pesanan.index', compact('pesanan', 'totalMenunggu'));
    }

    public function checkin($id): RedirectResponse
    {
        $pemesanan = Pemesanan::whereHas('villa', fn($q) => $q->where('id_owner', Auth::id()))
            ->where('id_pemesanan', $id)
            ->where('status', 'dibayar')
            ->firstOrFail();

        $pemesanan->update(['status' => 'checked_in']);

        return back()->with('success', 'Check-in tamu berhasil dikonfirmasi!');
    }

    public function checkout($id): RedirectResponse
    {
        $pemesanan = Pemesanan::whereHas('villa', fn($q) => $q->where('id_owner', Auth::id()))
            ->where('id_pemesanan', $id)
            ->where('status', 'checked_in')
            ->firstOrFail();

        $pemesanan->update(['status' => 'checked_out']);
        $pemesanan->villa->update(['tersedia' => true]);

        return back()->with('success', 'Check-out tamu berhasil dikonfirmasi!');
    }
}