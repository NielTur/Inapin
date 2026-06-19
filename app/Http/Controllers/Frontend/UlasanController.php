<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Ulasan;
use App\Models\Villa;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class UlasanController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'id_villa' => 'required|exists:villa,id_villa',
            'rating'   => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:500',
        ], [
            'rating.required' => 'Pilih rating bintang terlebih dahulu.',
            'rating.min'      => 'Rating minimal 1 bintang.',
            'rating.max'      => 'Rating maksimal 5 bintang.',
        ]);

        $idVilla    = $request->id_villa;
        $idCustomer = Auth::id();

        // Cek apakah tamu punya pemesanan yang sudah checkout di villa ini

        $bolehRating = Pemesanan::where('id_customer', $idCustomer)
            ->where('id_villa', $idVilla)
            ->where('status', 'checked_out')
            ->whereHas('detailPemesanan', function ($q) {
                $q->where('tanggal_checkout', '<=', now()->toDateString());
            })
            ->exists();

        if (!$bolehRating) {
            return back()->with('error', 'Anda hanya bisa memberikan rating setelah checkout dari villa ini.');
        }

        // Simpan atau update rating (1 tamu 1 rating per villa)
        Ulasan::updateOrCreate(
            ['id_villa' => $idVilla, 'id_customer' => $idCustomer],
            ['rating' => $request->rating, 'komentar' => $request->komentar]
        );

        // Update rata-rata ulasan di tabel villa
        $avgRating = Ulasan::where('id_villa', $idVilla)->avg('rating');
        Villa::where('id_villa', $idVilla)->update([
            'ulasan' => round($avgRating, 1)
        ]);

        return back()->with('success', 'Terima kasih! Rating Anda telah disimpan.');
    }
}
