<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Villa;
use App\Models\Customer;
use App\Models\DetailPemesanan;
use Illuminate\Validation\Rule;

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

        return back()->with('success', 'Check-out tamu berhasil dikonfirmasi!');
    }

    public function create(): View
    {
        $villas = Villa::where('id_owner', Auth::id())
            ->where('status', 'disetujui')
            ->get();

        // Kumpulkan booked dates per villa untuk Flatpickr
        $villaBookedDates = [];
        foreach ($villas as $villa) {
            $villaBookedDates[$villa->id_villa] = $villa->getBookedDates();
        }

        return view('owner.v_pesanan.create', compact('villas', 'villaBookedDates'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'id_villa' => [
                'required',
                Rule::exists('villa', 'id_villa')
                    ->where('id_owner', Auth::id())
                    ->where('status', 'disetujui'),
            ],
            'tanggal_checkin'  => 'required|date|after_or_equal:today',
            'tanggal_checkout' => 'required|date|after:tanggal_checkin',
            'nama_tamu'        => 'required|string|max:255',
            'tipe_identitas'   => 'required|in:KTP,SIM,Paspor',
            'nomor_identitas'  => 'required|string|max:50',
            'alamat_tamu'      => 'required|string|max:500',
            'no_hp_tamu'       => 'required|string|max:20',
            'email_tamu'       => 'nullable|email|max:255',
            'metode_pembayaran' => 'required|in:tunai,midtrans',
        ]);

        $villa = Villa::where('id_villa', $request->id_villa)
            ->where('id_owner', Auth::id())
            ->where('status', 'disetujui')
            ->firstOrFail();

        // Cek overlap tanggal dengan pemesanan aktif
        if ($villa->isBookedBetween($request->tanggal_checkin, $request->tanggal_checkout)) {
            return back()->withErrors(['tanggal_checkin' => 'Villa sudah dipesan pada tanggal yang dipilih. Silakan pilih tanggal lain.'])->withInput();
        }

        if ($request->email_tamu) {
            $walkInCustomer = Customer::where('email', $request->email_tamu)->first();
            if (!$walkInCustomer) {
                $walkInCustomer = Customer::create([
                    'nama' => $request->nama_tamu,
                    'email' => $request->email_tamu,
                    'phone' => $request->no_hp_tamu,
                    'password' => bcrypt(\Illuminate\Support\Str::random(16)),
                ]);
            }
        } else {
            $walkInCustomer = Customer::create([
                'nama' => $request->nama_tamu,
                'email' => 'walkin_' . time() . '_' . rand(100, 999) . '@inapin.local',
                'phone' => $request->no_hp_tamu,
                'password' => bcrypt(\Illuminate\Support\Str::random(16)),
            ]);
        }

        $malam = (int) ((strtotime($request->tanggal_checkout) - strtotime($request->tanggal_checkin)) / 86400);
        $total = $malam * $villa->harga;

        $pemesanan = Pemesanan::create([
            'id_villa'          => $villa->id_villa,
            'id_customer'       => $walkInCustomer->id_customer,
            'nama_tamu'         => $request->nama_tamu,
            'email_tamu'        => $request->email_tamu,
            'no_hp_tamu'        => $request->no_hp_tamu,
            'tipe_identitas'    => $request->tipe_identitas,
            'nomor_identitas'   => $request->nomor_identitas,
            'alamat_tamu'       => $request->alamat_tamu,
            'metode_pembayaran' => $request->metode_pembayaran,
            'tanggal_pemesanan' => now(),
            'status'            => $request->metode_pembayaran === 'tunai' ? 'dibayar' : 'menunggu',
            'expires_at'        => $request->metode_pembayaran === 'tunai' ? null : now()->addMinutes(config('app.booking_payment_timeout', 30)),
        ]);


        DetailPemesanan::create([
            'id_pemesanan'    => $pemesanan->id_pemesanan,
            'tanggal_checkin' => $request->tanggal_checkin,
            'tanggal_checkout' => $request->tanggal_checkout,
            'harga_default'   => $villa->harga,
            'sub_total'       => $total,
        ]);

        if ($request->metode_pembayaran === 'midtrans') {
            return redirect()->route('owner.pesanan.bayarSnap', $pemesanan->id_pemesanan);
        }

        return redirect()->route('owner.pesanan.index')->with('success', 'Pesanan manual berhasil dibuat. Status: Dibayar.');
    }

    public function bayarSnap($id): View|RedirectResponse
    {
        $pemesanan = Pemesanan::whereHas('villa', fn($q) => $q->where('id_owner', Auth::id()))
            ->where('id_pemesanan', $id)
            ->with(['villa', 'detailPemesanan', 'customer'])
            ->firstOrFail();

        if ($pemesanan->status !== 'menunggu') {
            return redirect()->route('owner.pesanan.index')->with('success', 'Pesanan sudah dibayar atau selesai.');
        }

        if ($pemesanan->expires_at && $pemesanan->expires_at->isPast()) {
            $pemesanan->update(['status' => 'dibatalkan']);
            return redirect()->route('owner.pesanan.index')
                ->with('error', 'Waktu pembayaran habis. Pemesanan otomatis dibatalkan.');
        }

        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $detail = $pemesanan->detailPemesanan;

        $params = [
            'transaction_details' => [
                'order_id' => 'INAPIN-' . $pemesanan->id_pemesanan . '-' . time(),
                'gross_amount' => (int) $detail->sub_total,
            ],
            'customer_details' => [
                'first_name' => $pemesanan->nama_tamu,
                'email' => $pemesanan->email_tamu ?? 'walkin@inapin.local',
                'phone' => $pemesanan->no_hp_tamu ?? '0000000000',
            ],
            'item_details' => [
                [
                    'id' => $pemesanan->villa->id_villa,
                    'price' => (int) $detail->sub_total,
                    'quantity' => 1,
                    'name' => $pemesanan->villa->nama_villa,
                ]
            ],
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        return view('owner.v_pesanan.bayar_snap', compact('pemesanan', 'snapToken'));
    }

    public function batal($id): RedirectResponse
    {
        $pemesanan = Pemesanan::whereHas('villa', fn($q) => $q->where('id_owner', Auth::id()))
            ->where('id_pemesanan', $id)
            ->where('status', 'menunggu')
            ->firstOrFail();

        $pemesanan->update(['status' => 'dibatalkan']);

        return redirect()->route('owner.pesanan.index')
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }
}
