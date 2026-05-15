<?php

namespace App\Models;

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
        'harga',
        'kapasitas',
        'jumlah_kamar',
        'jumlah_kamar_mandi',
        'whatsapp',
        'instagram',
        'facebook',
        'tiktok',
        'status',
        'tersedia',
        'ulasan',
        'alamat',
    ];

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