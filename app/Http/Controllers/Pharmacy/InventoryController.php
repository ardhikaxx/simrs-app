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

    public function show(InventoryMedicine $medicine): View
    {
        $medicine->load(['transactions.user']);
        return view('pharmacy.inventory.show', compact('medicine'));
    }

    public function update(Request $request, InventoryMedicine $medicine): RedirectResponse
    {
        $data = $request->validate([
            'kode_obat' => ['required', 'string', 'max:30', 'unique:inventory_medicines,kode_obat,' . $medicine->id],
            'nama_obat' => ['required', 'string', 'max:150'],
            'kategori' => ['required', 'string', 'max:80'],
            'satuan' => ['required', 'string', 'max:30'],
            'stok_minimum' => ['required', 'integer', 'min:0'],
            'harga_beli' => ['required', 'numeric', 'min:0'],
            'harga_jual' => ['required', 'numeric', 'min:0'],
            'expired_at' => ['nullable', 'date'],
            'manufacturer' => ['nullable', 'string', 'max:120'],
        ]);

        $medicine->update($data);

        return back()->with('swal_success', 'Data obat berhasil diperbarui.');
    }

    public function destroy(InventoryMedicine $medicine): RedirectResponse
    {
        if ($medicine->stok > 0) {
            return back()->with('swal_error', 'Gagal menghapus! Stok obat masih tersedia.');
        }

        $medicine->delete();

        return back()->with('swal_success', 'Data obat berhasil dihapus.');
    }
}
