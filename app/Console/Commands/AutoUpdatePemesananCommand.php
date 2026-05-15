<?php

namespace App\Console\Commands;

use App\Models\Pemesanan;
use Illuminate\Console\Command;

class AutoUpdatePemesananCommand extends Command
{
    protected $signature = 'pemesanan:auto-update';
    protected $description = 'Auto update status pemesanan: expired payment, auto no-show, auto checkout';

    public function handle(): void
    {
        // Job 1 — Expired payment: menunggu + expires_at lewat
        $expired = Pemesanan::where('status', 'menunggu')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->with('villa')
            ->get();

        foreach ($expired as $p) {
            $p->update(['status' => 'dibatalkan']);
            $p->villa?->update(['tersedia' => true]);
        }
        $this->info("Expired payment: {$expired->count()} pemesanan dibatalkan.");

        // Job 2 — Auto no-show: dibayar tapi tidak checkin sampai H+1
        $noShow = Pemesanan::where('status', 'dibayar')
            ->whereHas(
                'detailPemesanan',
                fn($q) =>
                $q->where('tanggal_checkin', '<', now()->subDay()->toDateString())
            )
            ->with('villa')
            ->get();

        foreach ($noShow as $p) {
            $p->update(['status' => 'checked_out']);
            $p->villa?->update(['tersedia' => true]);
        }
        $this->info("No-show: {$noShow->count()} pemesanan di-checkout otomatis.");

        // Job 3 — Auto checkout: checked_in melewati tanggal checkout
        $autoCheckout = Pemesanan::where('status', 'checked_in')
            ->whereHas(
                'detailPemesanan',
                fn($q) =>
                $q->where('tanggal_checkout', '<', now()->toDateString())
            )
            ->with('villa')
            ->get();

        foreach ($autoCheckout as $p) {
            $p->update(['status' => 'checked_out']);
            $p->villa?->update(['tersedia' => true]);
        }
        $this->info("Auto checkout: {$autoCheckout->count()} pemesanan di-checkout otomatis.");
    }
}