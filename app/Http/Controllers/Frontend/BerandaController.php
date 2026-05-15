<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Villa;
use Illuminate\View\View;

class BerandaController extends Controller
{
    public function index(): View
    {
        $villasTerbaru = Villa::where('status', 'disetujui')
            ->where('tersedia', true)
            ->with(['fasilitasVilla', 'dokumenVilla'])
            ->latest()
            ->take(6)
            ->get();

        $kotaList = Villa::where('status', 'disetujui')
            ->where('tersedia', true)
            ->selectRaw('kota, COUNT(*) as total')
            ->groupBy('kota')
            ->orderByDesc('total')
            ->pluck('kota')
            ->take(5);

        return view('frontend.v_beranda.index', compact('villasTerbaru', 'kotaList'));
    }
}