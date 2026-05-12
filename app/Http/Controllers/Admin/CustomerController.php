<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $query = Customer::withCount('pemesanan');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%'.$request->search.'%')
                ->orWhere('email', 'like', '%'.$request->search.'%')
                ->orWhere('phone', 'like', '%'.$request->search.'%');
            });
        }

        $customers = $query->latest()->paginate(15)->withQueryString();
        return view('backend.v_customer.index', compact('customers'));
    }

    public function destroy($id): RedirectResponse
    {
        Customer::findOrFail($id)->delete();
        return back()->with('success', 'Data tamu berhasil dihapus.');
    }
}
