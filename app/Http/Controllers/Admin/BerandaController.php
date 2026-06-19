<?php

// ===============================================================
// FILE: app/Http/Controllers/Admin/BerandaController.php
// ===============================================================

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Owner;
use App\Models\Pemesanan;
use App\Models\Villa;
use Illuminate\View\View;

class BerandaController extends Controller
{
    public function index(): View
    {
        $totalCustomer = Customer::count();
        $totalOwner    = Owner::count();
        $totalVilla    = Villa::count();
        $totalPesanan  = Pemesanan::count();

        $pesananTerbaru = Pemesanan::with(['villa', 'customer', 'detailPemesanan'])
            ->latest()->take(5)->get();

        return view('backend.v_beranda.index', compact(
            'totalCustomer',
            'totalOwner',
            'totalVilla',
            'totalPesanan',
            'pesananTerbaru'
        ));
    }
}
