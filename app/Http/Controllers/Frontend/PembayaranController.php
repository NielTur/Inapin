<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Midtrans\Config;

class PembayaranController extends Controller
{
    public function callback(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $hashedKey = hash(
            'sha512',
            $request->order_id .
            $request->status_code .
            $request->gross_amount .
            $serverKey
        );

        if ($hashedKey !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $parts = explode('-', $request->order_id);
        $orderId = $parts[1] ?? null;

        if (!$orderId) {
            return response()->json(['message' => 'Invalid order_id'], 400);
        }

        $pemesanan = Pemesanan::findOrFail($orderId);

        if (in_array($request->transaction_status, ['capture', 'settlement'])) {
            $pemesanan->update([
                'status' => 'dibayar',
                'expires_at' => null,
            ]);
        } elseif (in_array($request->transaction_status, ['cancel', 'deny', 'expire'])) {
            $pemesanan->update(['status' => 'dibatalkan']);
        }

        return response()->json(['message' => 'OK']);
    }

    public function finish(Request $request)
    {
        // Midtrans kirim order_id sebagai query param: INAPIN-{id}-{timestamp}
        if ($request->filled('order_id')) {
            $parts = explode('-', $request->order_id);
            $id = $parts[1] ?? null;
            if ($id) {
                return redirect()->route('booking.detail', $id)
                    ->with('success', 'Pembayaran berhasil diproses!');
            }
        }

        return redirect()->route('booking.riwayat')
            ->with('success', 'Pembayaran berhasil diproses!');
    }
}