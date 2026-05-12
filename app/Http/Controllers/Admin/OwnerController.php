<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Owner;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    public function index(Request $request): View
    {
        $query = Owner::withCount('villa');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%'.$request->search.'%')
                ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }

        $owners = $query->latest()->paginate(15)->withQueryString();
        return view('backend.v_owner.index', compact('owners'));
    }

    public function destroy($id): RedirectResponse
    {
        Owner::findOrFail($id)->delete();
        return back()->with('success', 'Data owner berhasil dihapus.');
    }
}
