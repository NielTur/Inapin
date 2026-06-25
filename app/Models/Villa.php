<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Villa extends Model
{
    protected $table = 'villa';
    protected $primaryKey = 'id_villa';

    protected $fillable = [
        'id_owner',
        'nama_villa',
        'deskripsi',
        'kota',
        'kelurahan',
        'kecamatan',
        'provinsi',
        'latitude',
        'longitude',
        'harga',
        'kapasitas',
        'jumlah_kamar',
        'jumlah_kamar_mandi',
        'whatsapp',
        'instagram',
        'facebook',
        'tiktok',
        'status',
        'catatan_admin',
        'tersedia',
        'ulasan',
        'alamat',
    ];

    /**
     * Cek apakah villa sudah di-booking pada rentang tanggal tertentu.
     * Overlap terjadi jika: existing_checkin < requested_checkout AND existing_checkout > requested_checkin
     */
    public function isBookedBetween(string $checkin, string $checkout): bool
    {
        return $this->pemesanan()
            ->whereIn('status', ['menunggu', 'dibayar', 'checked_in'])
            ->whereHas('detailPemesanan', function ($q) use ($checkin, $checkout) {
                $q->where('tanggal_checkin', '<', $checkout)
                  ->where('tanggal_checkout', '>', $checkin);
            })
            ->exists();
    }

    /**
     * Ambil semua tanggal yang sudah di-booking (untuk blokir di Flatpickr calendar).
     */
    public function getBookedDates(): array
    {
        $bookings = $this->pemesanan()
            ->whereIn('status', ['menunggu', 'dibayar', 'checked_in'])
            ->with('detailPemesanan')
            ->get();

        $dates = [];
        foreach ($bookings as $booking) {
            $detail = $booking->detailPemesanan;
            if (!$detail) continue;
            $start = Carbon::parse($detail->tanggal_checkin);
            $end = Carbon::parse($detail->tanggal_checkout);
            while ($start->lt($end)) {
                $dates[] = $start->format('Y-m-d');
                $start->addDay();
            }
        }
        return array_values(array_unique($dates));
    }

    public function fasilitasVilla(): HasMany
    {
        return $this->hasMany(FasilitasVilla::class, 'id_villa', 'id_villa');
    }

    public function dokumenVilla(): HasMany
    {
        return $this->hasMany(DokumenVilla::class, 'id_villa', 'id_villa');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Owner::class, 'id_owner', 'id_owner');
    }

    public function pemesanan(): HasMany
    {
        return $this->hasMany(Pemesanan::class, 'id_villa', 'id_villa');
    }

    public function ulasanVilla(): HasMany
    {
        return $this->hasMany(Ulasan::class, 'id_villa', 'id_villa');
    }
}
