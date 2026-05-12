<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\DetailPemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;

class PembayaranController extends Controller
{
    public function __construct()
    {
        Config::$serverKey    = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = false;
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    // Buat transaksi Midtrans & redirect ke halaman bayar
    public function charge($id)
    {
        $pemesanan = Pemesanan::with(['detailPemesanan', 'villa', 'customer'])
            ->where('id_pemesanan', $id)
            ->where('id_customer', Auth::id())
            ->firstOrFail();

        $detail = $pemesanan->detailPemesanan;

        $params = [
            'transaction_details' => [
                'order_id'     => 'INAPIN-' . $pemesanan->id_pemesanan . '-' . time(),
                'gross_amount' => (int) $detail->sub_total,
            ],
            'customer_details' => [
                'first_name' => $pemesanan->customer->nama,
                'email'      => $pemesanan->customer->email,
                'phone'      => $pemesanan->customer->phone ?? '',
            ],
            'item_details' => [
                [
                    'id'       => $pemesanan->villa->id_villa,
                    'price'    => (int) $detail->sub_total,
                    'quantity' => 1,
                    'name'     => $pemesanan->villa->nama_villa,
                ],
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return view('frontend.v_booking.bayar', compact('pemesanan', 'snapToken'));
    }

    // Callback dari Midtrans (webhook)
    public function callback(Request $request)
    {
        $serverKey    = env('MIDTRANS_SERVER_KEY');
        $hashedKey    = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashedKey !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $orderId      = explode('-', $request->order_id)[1];
        $pemesanan    = Pemesanan::findOrFail($orderId);

        if (in_array($request->transaction_status, ['capture', 'settlement'])) {
            $pemesanan->update(['status' => 'dikonfirmasi']);
        } elseif (in_array($request->transaction_status, ['cancel', 'deny', 'expire'])) {
            $pemesanan->update(['status' => 'dibatalkan']);
        }

        return response()->json(['message' => 'OK']);
    }

    // Halaman setelah bayar
    public function finish(Request $request)
    {
        return redirect()->route('booking.riwayat')
            ->with('success', 'Pembayaran berhasil! Pesanan kamu sedang diproses.');
    }
}
