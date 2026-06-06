<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Models\InventoryTransaction;
use App\Models\Prescription;
use App\Services\BillingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PrescriptionController extends Controller
{
    public function index(): View
    {
        return view('pharmacy.prescriptions.index', [
            'prescriptions' => Prescription::with(['encounter.patient', 'encounter.department', 'doctor', 'details.medicine'])
                ->latest()
                ->paginate(20),
        ]);
    }

    public function dispense(Prescription $prescription, BillingService $billingService): RedirectResponse
    {
        DB::transaction(function () use ($prescription) {
            $prescription->load(['details.medicine', 'encounter']);

            foreach ($prescription->details as $detail) {
                $medicine = $detail->medicine;
                if (! $medicine) {
                    continue;
                }

                $before = $medicine->stok;
                $qty = (int) ceil($detail->jumlah);
                abort_if($before < $qty, 422, "Stok {$medicine->nama_obat} tidak mencukupi.");

                $medicine->decrement('stok', $qty);
                $medicine->refresh();

                InventoryTransaction::create([
                    'inventory_medicine_id' => $medicine->id,
                    'user_id' => auth('staff')->id(),
                    'jenis_transaksi' => 'keluar',
                    'qty' => -$qty,
                    'stok_sebelum' => $before,
                    'stok_sesudah' => $medicine->stok,
                    'referensi' => $prescription->no_resep,
                    'catatan' => 'Dispensing resep elektronik.',
                ]);
            }

            $prescription->update([
                'pharmacist_id' => auth('staff')->id(),
                'status' => 'selesai',
                'verified_at' => $prescription->verified_at ?: now(),
                'dispensed_at' => now(),
            ]);

            $prescription->encounter->update(['status_antrian' => 'menunggu_kasir']);
        });

        $billingService->ensureInvoice($prescription->encounter->refresh());

        return back()->with('swal_success', 'Resep berhasil didispensing dan stok farmasi diperbarui.');
    }
}
