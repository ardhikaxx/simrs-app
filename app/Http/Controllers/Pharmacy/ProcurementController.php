<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Models\InventoryMedicine;
use App\Models\InventoryTransaction;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Support\SimrsNumber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProcurementController extends Controller
{
    public function index(): View
    {
        return view('pharmacy.procurement.index', [
            'orders' => PurchaseOrder::with(['user'])->latest()->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('pharmacy.procurement.create', [
            'medicines' => InventoryMedicine::where('is_active', true)->orderBy('nama_obat')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'supplier_name' => ['required', 'string', 'max:150'],
            'order_date' => ['required', 'date'],
            'medicine_id' => ['required', 'array'],
            'qty' => ['required', 'array'],
            'price' => ['required', 'array'],
        ]);

        DB::transaction(function () use ($request) {
            $total = 0;
            $items = [];
            foreach ($request->medicine_id as $index => $id) {
                $qty = (float) $request->qty[$index];
                $price = (float) $request->price[$index];
                $subtotal = $qty * $price;
                $total += $subtotal;
                
                $items[] = [
                    'inventory_medicine_id' => $id,
                    'qty_ordered' => $qty,
                    'cost_price' => $price,
                    'subtotal' => $subtotal,
                ];
            }

            $po = PurchaseOrder::create([
                'no_po' => SimrsNumber::daily('PO', 'purchase_orders', 'no_po'),
                'supplier_name' => $request->supplier_name,
                'user_id' => auth('staff')->id(),
                'status' => 'ordered',
                'total_amount' => $total,
                'order_date' => $request->order_date,
            ]);

            $po->details()->createMany($items);
        });

        return redirect()->route('farmasi.procurement.index')->with('swal_success', 'Purchase Order berhasil diterbitkan.');
    }

    public function receive(PurchaseOrder $po): RedirectResponse
    {
        if ($po->status === 'received') {
            return back()->with('swal_error', 'PO sudah pernah diterima.');
        }

        DB::transaction(function () use ($po) {
            $po->load('details.medicine');
            
            foreach ($po->details as $detail) {
                $medicine = $detail->medicine;
                $before = $medicine->stok;
                
                $medicine->increment('stok', $detail->qty_ordered);
                $medicine->update(['harga_beli' => $detail->cost_price]); // Update last cost price
                
                InventoryTransaction::create([
                    'inventory_medicine_id' => $medicine->id,
                    'user_id' => auth('staff')->id(),
                    'jenis_transaksi' => 'masuk',
                    'qty' => $detail->qty_ordered,
                    'stok_sebelum' => $before,
                    'stok_sesudah' => $medicine->stok,
                    'referensi' => $po->no_po,
                    'catatan' => 'Penerimaan stok dari supplier: ' . $po->supplier_name,
                ]);

                $detail->update(['qty_received' => $detail->qty_ordered]);
            }

            $po->update(['status' => 'received', 'received_at' => now()]);
        });

        return back()->with('swal_success', 'Barang berhasil diterima dan stok perbekalan telah bertambah.');
    }
}
