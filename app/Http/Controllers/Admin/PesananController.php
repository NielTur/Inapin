<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\View\View;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    public function index(Request $request): View
    {
        $pesanan = Pemesanan::with(['villa.owner', 'customer', 'detailPemesanan'])
            ->latest()->paginate(15)->withQueryString();

        return view('backend.v_pesanan.index', compact('pesanan'));
    }
}
