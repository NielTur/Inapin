<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Villa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VillaController extends Controller
{
    public function index(Request $request): View
    {
        $query = Villa::with(['owner', 'dokumenVilla']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $villas         = $query->latest()->paginate(15)->withQueryString();
        $countPending   = Villa::where('status', 'pending')->count();
        $countDisetujui = Villa::where('status', 'disetujui')->count();
        $countDitolak   = Villa::where('status', 'ditolak')->count();
        $countNonaktif  = Villa::where('status', 'nonaktif')->count();

        return view('backend.v_villa.index', compact(
            'villas', 'countPending', 'countDisetujui', 'countDitolak', 'countNonaktif'
        ));
    }

    public function show($id): View
    {
        $villa = Villa::with(['owner', 'dokumenVilla', 'fasilitasVilla'])
            ->findOrFail($id);
        return view('backend.v_villa.show', compact('villa'));
    }
// method untuk mengaktifkan aksi villa //
    public function setujui($id): RedirectResponse
    {
        $villa = Villa::findOrFail($id);
        $villa->update([
            'status'        => 'disetujui',
            'catatan_admin' => null,
        ]);
        return back()->with('success', "Villa \"{$villa->nama_villa}\" berhasil disetujui!");
    }

    public function tolak(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'catatan_admin' => 'required|string|max:500',
        ], ['catatan_admin.required' => 'Alasan penolakan wajib diisi.']);

        $villa = Villa::findOrFail($id);
        $villa->update([
            'status'        => 'ditolak',
            'catatan_admin' => $request->catatan_admin,
        ]);
        return back()->with('success', "Villa \"{$villa->nama_villa}\" ditolak.");
    }

    public function nonaktifkan($id): RedirectResponse
    {
        $villa = Villa::findOrFail($id);
        $villa->update(['status' => 'nonaktif']);
        return back()->with('success', "Villa \"{$villa->nama_villa}\" dinonaktifkan.");
    }

    public function aktifkan($id): RedirectResponse
    {
        $villa = Villa::findOrFail($id);
        $villa->update(['status' => 'disetujui']);
        return back()->with('success', "Villa \"{$villa->nama_villa}\" berhasil diaktifkan kembali.");
    }
// disini //
    public function updateStatus(Request $request, $id): RedirectResponse
    {
        $villa = Villa::findOrFail($id);
        $villa->update(['status' => $request->status]);
        return back()->with('success', 'Status villa berhasil diperbarui.');
    }

    public function destroy($id): RedirectResponse
    {
        Villa::findOrFail($id)->delete();
        return back()->with('success', 'Villa berhasil dihapus.');
    }
}
