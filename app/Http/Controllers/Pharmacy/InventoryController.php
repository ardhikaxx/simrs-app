<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Models\InventoryMedicine;
use App\Models\InventoryTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryController extends Controller
{
    public function index(Request $request): View
    {
        return view('pharmacy.inventory.index', [
            'medicines' => InventoryMedicine::query()
                ->when($request->filled('q'), fn ($query) => $query->where('nama_obat', 'like', '%' . $request->q . '%')->orWhere('kode_obat', 'like', '%' . $request->q . '%'))
                ->orderBy('nama_obat')
                ->paginate(20)
                ->withQueryString(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'kode_obat' => ['required', 'string', 'max:30', 'unique:inventory_medicines,kode_obat'],
            'nama_obat' => ['required', 'string', 'max:150'],
            'kategori' => ['required', 'string', 'max:80'],
            'satuan' => ['required', 'string', 'max:30'],
            'stok' => ['required', 'integer', 'min:0'],
            'stok_minimum' => ['required', 'integer', 'min:0'],
            'harga_beli' => ['required', 'numeric', 'min:0'],
            'harga_jual' => ['required', 'numeric', 'min:0'],
            'expired_at' => ['nullable', 'date'],
            'manufacturer' => ['nullable', 'string', 'max:120'],
        ]);

        $medicine = InventoryMedicine::create($data + ['is_active' => true]);
        InventoryTransaction::create([
            'inventory_medicine_id' => $medicine->id,
            'user_id' => auth('staff')->id(),
            'jenis_transaksi' => 'masuk',
            'qty' => $medicine->stok,
            'stok_sebelum' => 0,
            'stok_sesudah' => $medicine->stok,
            'referensi' => 'STOK-AWAL',
            'catatan' => 'Input obat baru.',
        ]);

        return back()->with('swal_success', 'Data obat berhasil ditambahkan.');
    }
}
