<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Villa;
use App\Models\Pemesanan;
use App\Models\DetailPemesanan;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    public function form($id, Request $request): View
    {
        $villa = Villa::where('id_villa', $id)
            ->where('status', 'disetujui')
            ->where('tersedia', true)
            ->with(['fasilitasVilla', 'dokumenVilla'])
            ->firstOrFail();

        $checkin = $request->get('checkin', date('Y-m-d'));
        $checkout = $request->get('checkout', date('Y-m-d', strtotime('+1 day')));
        $tamu = $request->get('tamu', 1);

        $malam = max(1, (int) ((strtotime($checkout) - strtotime($checkin)) / 86400));
        $total = $malam * $villa->harga;

        return view('frontend.v_booking.form', compact('villa', 'checkin', 'checkout', 'tamu', 'malam', 'total'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(
            [
                'id_villa' => [
                    'required',
                    Rule::exists('villa', 'id_villa')
                        ->where('status', 'disetujui')
                        ->where('tersedia', true),
                ],
                'tanggal_checkin'  => 'required|date|after_or_equal:today',
                'tanggal_checkout' => 'required|date|after:tanggal_checkin',
                'nama_tamu'        => 'required|string|max:255',
                'email_tamu'       => 'nullable|email|max:255',
                'no_hp_tamu'       => 'nullable|string|max:20',
            ],
            [
                'id_villa.exists'                => 'Villa tidak tersedia untuk dipesan.',
                'tanggal_checkin.after_or_equal' => 'Tanggal check-in tidak boleh sebelum hari ini.',
                'tanggal_checkout.after'         => 'Tanggal check-out harus setelah check-in.',
                'nama_tamu.required'             => 'Nama tamu wajib diisi.',
            ]
        );

        $villa = Villa::where('id_villa', $request->id_villa)
            ->where('status', 'disetujui')
            ->where('tersedia', true)
            ->firstOrFail();

        $malam = (int) ((strtotime($request->tanggal_checkout) - strtotime($request->tanggal_checkin)) / 86400);
        $total = $malam * $villa->harga;

        $pemesanan = Pemesanan::create([
            'id_villa'          => $villa->id_villa,
            'id_customer'       => Auth::id(),
            'nama_tamu'         => $request->nama_tamu,
            'email_tamu'        => $request->email_tamu,
            'no_hp_tamu'        => $request->no_hp_tamu,
            'metode_pembayaran' => 'midtrans',
            'tanggal_pemesanan' => now(),
            'expires_at'        => now()->addMinutes(config('app.booking_payment_timeout', 30)),
        ]);

        $villa->update(['tersedia' => false]);

        DetailPemesanan::create([
            'id_pemesanan'    => $pemesanan->id_pemesanan,
            'tanggal_checkin' => $request->tanggal_checkin,
            'tanggal_checkout' => $request->tanggal_checkout,
            'harga_default'   => $villa->harga,
            'sub_total'       => $total,
        ]);

        return redirect()->route('booking.bayar', $pemesanan->id_pemesanan);
    }

    public function bayar($id): View|RedirectResponse
    {
        $pemesanan = Pemesanan::where('id_pemesanan', $id)
            ->where('id_customer', Auth::id())
            ->with(['villa.dokumenVilla', 'detailPemesanan', 'customer'])
            ->firstOrFail();

        // Kalau sudah dibayar atau status lain, ke halaman detail
        if ($pemesanan->status !== 'menunggu') {
            return redirect()->route('booking.detail', $id);
        }

        // Cek expired
        if ($pemesanan->expires_at && $pemesanan->expires_at->isPast()) {
            $pemesanan->update(['status' => 'dibatalkan']);
            $pemesanan->villa->update(['tersedia' => true]);
            return redirect()->route('booking.riwayat')
                ->with('error', 'Waktu pembayaran habis. Pemesanan otomatis dibatalkan.');
        }

        // Generate Snap Token
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
                'first_name' => $pemesanan->nama_tamu ?? $pemesanan->customer->nama,
                'email' => $pemesanan->email_tamu ?? $pemesanan->customer->email,
                'phone' => $pemesanan->no_hp_tamu ?? $pemesanan->customer->phone ?? '',
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

        return view('frontend.v_booking.bayar', compact('pemesanan', 'snapToken'));
    }

    public function detail($id): View|RedirectResponse
    {
        $pemesanan = Pemesanan::where('id_pemesanan', $id)
            ->where('id_customer', Auth::id())
            ->with(['villa.dokumenVilla', 'detailPemesanan'])
            ->firstOrFail();

        // Kalau masih menunggu bayar, redirect ke halaman bayar
        if ($pemesanan->status === 'menunggu') {
            return redirect()->route('booking.bayar', $id);
        }

        return view('frontend.v_booking.detail', compact('pemesanan'));
    }

    public function riwayat(): View
    {
        $pemesanan = Pemesanan::where('id_customer', Auth::id())
            ->with(['villa', 'detailPemesanan'])
            ->latest()
            ->get();

        return view('frontend.v_booking.riwayat', compact('pemesanan'));
    }

    public function batal($id): RedirectResponse
    {
        $pemesanan = Pemesanan::where('id_pemesanan', $id)
            ->where('id_customer', Auth::id())
            ->where('status', 'menunggu')
            ->firstOrFail();

        $pemesanan->update(['status' => 'dibatalkan']);
        $pemesanan->villa->update(['tersedia' => true]);

        return redirect()->route('booking.riwayat')
            ->with('success', 'Pemesanan berhasil dibatalkan.');
    }

    public function hapus($id): RedirectResponse
    {
        $pemesanan = Pemesanan::where('id_pemesanan', $id)
            ->where('id_customer', Auth::id())
            ->where('status', 'dibatalkan')
            ->firstOrFail();

        $pemesanan->detailPemesanan()->delete();
        $pemesanan->delete();

        return redirect()->route('booking.riwayat')
            ->with('success', 'Riwayat pemesanan berhasil dihapus.');
    }
}
