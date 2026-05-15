<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pemesanan extends Model
{
    protected $table = 'pemesanan';
    protected $primaryKey = 'id_pemesanan';

    protected $fillable = [
        'id_villa',
        'id_customer',
        'metode_pembayaran',
        'tanggal_pemesanan',
        'status',
        'expires_at',
    ];

    protected $casts = [
        'tanggal_pemesanan' => 'datetime',
        'expires_at' => 'datetime',
    ];

    // ── Scopes ────────────────────────────────────────────────

    public function scopeAktif(Builder $query): Builder
    {
        return $query->whereIn('status', ['dibayar', 'checked_in']);
    }

    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('status', 'menunggu')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now());
    }

    // ── Relationships ──────────────────────────────────────────

    public function villa(): BelongsTo
    {
        return $this->belongsTo(Villa::class, 'id_villa', 'id_villa');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'id_customer', 'id_customer');
    }

    public function detailPemesanan(): HasOne
    {
        return $this->hasOne(DetailPemesanan::class, 'id_pemesanan', 'id_pemesanan');
    }
}