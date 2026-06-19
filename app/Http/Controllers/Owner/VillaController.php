<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Villa;
use App\Models\FasilitasVilla;
use App\Models\DokumenVilla;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class VillaController extends Controller
{
    // ── Geocoding helper ──────────────────────────────────────
    private function geocodeAlamat(Request $request): array
    {
        // Structured search — pisah street/city/state, jauh lebih akurat
        $street = collect([$request->alamat, $request->kelurahan, $request->kecamatan])
            ->filter()
            ->implode(', ');

        $attempts = [];

        // Attempt 1: full structured
        if ($street && $request->filled('kota')) {
            $attempts[] = array_filter([
                'street' => $street,
                'city' => $request->kota,
                'state' => $request->provinsi ?: null,
                'format' => 'json',
                'limit' => 1,
                'countrycodes' => 'id',
            ]);
        }

        // Attempt 2: kecamatan+kelurahan sebagai street
        $streetShort = collect([$request->kecamatan, $request->kelurahan])->filter()->implode(', ');
        if ($streetShort && $request->filled('kota')) {
            $attempts[] = array_filter([
                'street' => $streetShort,
                'city' => $request->kota,
                'state' => $request->provinsi ?: null,
                'format' => 'json',
                'limit' => 1,
                'countrycodes' => 'id',
            ]);
        }

        // Attempt 3: city + state saja (fallback)
        if ($request->filled('kota')) {
            $attempts[] = array_filter([
                'city' => $request->kota,
                'state' => $request->provinsi ?: null,
                'format' => 'json',
                'limit' => 1,
                'countrycodes' => 'id',
            ]);
        }

        foreach ($attempts as $params) {
            try {
                $resp = Http::withHeaders([
                    'User-Agent' => 'inapin-villaapp/1.0',
                    'Accept-Language' => 'id,en',
                ])->timeout(6)->get('https://nominatim.openstreetmap.org/search', $params);

                $data = $resp->json();
                if (!empty($data[0]['lat'])) {
                    return [
                        'latitude' => round((float) $data[0]['lat'], 8),
                        'longitude' => round((float) $data[0]['lon'], 8),
                    ];
                }
            } catch (\Throwable) {
                continue;
            }
        }

        return ['latitude' => null, 'longitude' => null];
    }

    public function index(): View
    {
        $villas = Villa::where('id_owner', Auth::id())
            ->with(['fasilitasVilla', 'dokumenVilla'])
            ->latest()
            ->paginate(10);

        return view('owner.v_villa.index', compact('villas'));
    }

    public function create(): View
    {
        return view('owner.v_villa.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_villa' => 'required|string|max:100',
            'deskripsi' => 'required|string',
            'kota' => 'required|string|max:100',
            'kelurahan' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'alamat' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'kapasitas' => 'required|integer|min:1',
            'jumlah_kamar' => 'required|integer|min:1',
            'jumlah_kamar_mandi' => 'required|integer|min:1',
            'fasilitas' => 'nullable|array',
            'fasilitas.*' => 'string|max:100',
            'foto.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'nama_villa.required' => 'Nama villa wajib diisi.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'kota.required' => 'Kota/Kabupaten wajib diisi.',
            'alamat.required' => 'Alamat lengkap wajib diisi.',
            'harga.required' => 'Harga wajib diisi.',
            'kapasitas.required' => 'Kapasitas wajib diisi.',
            'foto.*.image' => 'File harus berupa gambar.',
            'foto.*.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        if ($request->filled('latitude') && $request->filled('longitude')) {
            $coords = [
                'latitude' => (float) $request->latitude,
                'longitude' => (float) $request->longitude,
            ];
        } else {
            $coords = $this->geocodeAlamat($request);
        }

        $villa = Villa::create([
            'id_owner' => Auth::id(),
            'nama_villa' => $request->nama_villa,
            'deskripsi' => $request->deskripsi,
            'kota' => $request->kota,
            'kelurahan' => $request->kelurahan,
            'kecamatan' => $request->kecamatan,
            'provinsi' => $request->provinsi,
            'latitude' => $coords['latitude'],
            'longitude' => $coords['longitude'],
            'alamat' => $request->alamat,
            'harga' => $request->harga,
            'kapasitas' => $request->kapasitas,
            'jumlah_kamar' => $request->jumlah_kamar,
            'jumlah_kamar_mandi' => $request->jumlah_kamar_mandi,
            'whatsapp' => $request->whatsapp,
            'instagram' => $request->instagram,
            'facebook' => $request->facebook,
            'tiktok' => $request->tiktok,
            'status' => 'pending',
        ]);

        if ($request->filled('fasilitas')) {
            foreach ($request->fasilitas as $fas) {
                if (trim($fas)) {
                    FasilitasVilla::create([
                        'id_villa' => $villa->id_villa,
                        'fasilitas' => trim($fas),
                    ]);
                }
            }
        }

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $path = $file->store('foto_villa', 'public');
                DokumenVilla::create([
                    'id_villa' => $villa->id_villa,
                    'id_owner' => Auth::id(),
                    'file_path' => $path,
                    'status' => 'disetujui',
                ]);
            }
        }

        return redirect()->route('owner.villa.index')
            ->with('success', 'Villa berhasil ditambahkan! Menunggu persetujuan admin.');
    }

    public function edit($id): View
    {
        $villa = Villa::where('id_villa', $id)
            ->where('id_owner', Auth::id())
            ->with(['fasilitasVilla', 'dokumenVilla'])
            ->firstOrFail();

        return view('owner.v_villa.edit', compact('villa'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $villa = Villa::where('id_villa', $id)
            ->where('id_owner', Auth::id())
            ->firstOrFail();

        $request->validate([
            'nama_villa' => 'required|string|max:100',
            'deskripsi' => 'required|string',
            'kota' => 'required|string|max:100',
            'kelurahan' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'alamat' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'kapasitas' => 'required|integer|min:1',
            'jumlah_kamar' => 'required|integer|min:1',
            'jumlah_kamar_mandi' => 'required|integer|min:1',
            'fasilitas' => 'nullable|array',
            'foto.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->filled('latitude') && $request->filled('longitude')) {
            $coords = [
                'latitude' => (float) $request->latitude,
                'longitude' => (float) $request->longitude,
            ];
        } else {
            $coords = $this->geocodeAlamat($request);
        }

        $villa->update([
            'nama_villa' => $request->nama_villa,
            'deskripsi' => $request->deskripsi,
            'kota' => $request->kota,
            'kelurahan' => $request->kelurahan,
            'kecamatan' => $request->kecamatan,
            'provinsi' => $request->provinsi,
            'latitude' => $coords['latitude'],
            'longitude' => $coords['longitude'],
            'alamat' => $request->alamat,
            'harga' => $request->harga,
            'kapasitas' => $request->kapasitas,
            'jumlah_kamar' => $request->jumlah_kamar,
            'jumlah_kamar_mandi' => $request->jumlah_kamar_mandi,
            'whatsapp' => $request->whatsapp,
            'instagram' => $request->instagram,
            'facebook' => $request->facebook,
            'tiktok' => $request->tiktok,
            'status' => $request->status ?? $villa->status,
        ]);

        $villa->fasilitasVilla()->delete();
        if ($request->filled('fasilitas')) {
            foreach ($request->fasilitas as $fas) {
                if (trim($fas)) {
                    FasilitasVilla::create([
                        'id_villa' => $villa->id_villa,
                        'fasilitas' => trim($fas),
                    ]);
                }
            }
        }

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $path = $file->store('foto_villa', 'public');
                DokumenVilla::create([
                    'id_villa' => $villa->id_villa,
                    'id_owner' => Auth::id(),
                    'file_path' => $path,
                    'status' => 'disetujui',
                ]);
            }
        }

        return redirect()->route('owner.villa.index')
            ->with('success', 'Villa berhasil diperbarui!');
    }

    public function destroy($id): RedirectResponse
    {
        $villa = Villa::where('id_villa', $id)
            ->where('id_owner', Auth::id())
            ->firstOrFail();

        foreach ($villa->dokumenVilla as $dok) {
            Storage::disk('public')->delete($dok->file_path);
        }

        $villa->fasilitasVilla()->delete();
        $villa->dokumenVilla()->delete();
        $villa->delete();

        return redirect()->route('owner.villa.index')
            ->with('success', 'Villa berhasil dihapus.');
    }

    public function destroyFoto($id_dokumen_villa): RedirectResponse
    {
        $dokumen = DokumenVilla::where('id_dokumen_villa', $id_dokumen_villa)
            ->where('id_owner', Auth::id())
            ->firstOrFail();

        if (Storage::disk('public')->exists($dokumen->file_path)) {
            Storage::disk('public')->delete($dokumen->file_path);
        }

        $dokumen->delete();

        return back()->with('success', 'Foto berhasil dihapus.');
    }
}
