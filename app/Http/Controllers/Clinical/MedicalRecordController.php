<?php

namespace App\Http\Controllers\Clinical;

use App\Http\Controllers\Controller;
use App\Models\Encounter;
use App\Models\ICD10;
use App\Models\ICD9;
use App\Models\InventoryBhp;
use App\Models\InventoryMedicine;
use App\Services\BillingService;
use App\Support\SimrsNumber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MedicalRecordController extends Controller
{
    public function queue(): View
    {
        return view('clinical.medical-queue', [
            'encounters' => Encounter::with(['patient', 'department', 'doctor', 'nursingAssessment', 'medicalRecord'])
                ->whereNotIn('status_encounter', ['selesai', 'batal'])
                ->latest('waktu_masuk')
                ->paginate(20),
        ]);
    }

    public function edit(Encounter $encounter): View
    {
        $encounter->load(['patient', 'department', 'doctor', 'nursingAssessment', 'medicalRecord', 'prescriptions.details', 'labOrders', 'radiologyOrders']);

        return view('clinical.medical-record', [
            'encounter' => $encounter,
            'icd10' => ICD10::where('is_active', true)->orderBy('kode')->get(),
            'icd9' => ICD9::orderBy('kode')->get(),
            'medicines' => InventoryMedicine::where('is_active', true)->orderBy('nama_obat')->get(),
            'bhps' => InventoryBhp::where('is_active', true)->orderBy('nama_bhp')->get(),
        ]);
    }

    public function update(Request $request, Encounter $encounter, BillingService $billingService): RedirectResponse
    {
        $data = $request->validate([
            'keluhan_utama' => ['required', 'string', 'min:5'],
            'riwayat_penyakit_sekarang' => ['nullable', 'string'],
            'riwayat_penyakit_dahulu' => ['nullable', 'string'],
            'pemeriksaan_fisik' => ['nullable', 'string'],
            'diagnosis_kerja' => ['required', 'string', 'min:3'],
            'icd10_primer' => ['required', 'exists:icd10,kode'],
            'icd10_sekunder' => ['nullable', 'array'],
            'icd9_prosedur' => ['nullable', 'exists:icd9_master,kode'],
            'rencana_terapi' => ['required', 'string', 'min:5'],
            'kondisi_saat_pulang' => ['nullable', 'string'],
            'data_spesifik_poli' => ['nullable', 'array'],
            'medicine_id' => ['nullable', 'array'],
            'medicine_id.*' => ['nullable', 'exists:inventory_medicines,id'],
            'jumlah' => ['nullable', 'array'],
            'aturan_pakai' => ['nullable', 'array'],
            'lab_items' => ['nullable', 'array'],
            'radiology_items' => ['nullable', 'array'],
            'bhp_id' => ['nullable', 'array'],
            'bhp_id.*' => ['nullable', 'exists:inventory_bhps,id'],
            'bhp_jumlah' => ['nullable', 'array'],
        ]);

        DB::transaction(function () use ($data, $encounter) {
            $recordData = collect($data)->only([
                'keluhan_utama',
                'riwayat_penyakit_sekarang',
                'riwayat_penyakit_dahulu',
                'pemeriksaan_fisik',
                'diagnosis_kerja',
                'icd10_primer',
                'icd10_sekunder',
                'icd9_prosedur',
                'rencana_terapi',
                'kondisi_saat_pulang',
                'data_spesifik_poli',
            ])->all();

            $medicalRecord = $encounter->medicalRecord()->updateOrCreate(
                ['encounter_id' => $encounter->id],
                $recordData + ['doctor_id' => auth('staff')->id(), 'signed_at' => now()]
            );

            // Sync BHPs
            $medicalRecord->bhps()->detach();
            $bhpIds = array_filter($data['bhp_id'] ?? []);
            foreach ($bhpIds as $index => $bhpId) {
                $bhp = \App\Models\InventoryBhp::find($bhpId);
                if ($bhp) {
                    $qty = (float) ($data['bhp_jumlah'][$index] ?? 1);
                    $medicalRecord->bhps()->attach($bhpId, [
                        'jumlah' => $qty,
                        'harga_satuan' => $bhp->harga_jual,
                        'subtotal' => $qty * $bhp->harga_jual,
                    ]);
                    $bhp->decrement('stok', $qty);
                }
            }

            $medicineIds = array_filter($data['medicine_id'] ?? []);
            if ($medicineIds) {
                $prescription = $encounter->prescriptions()->create([
                    'no_resep' => SimrsNumber::daily('RX', 'prescriptions', 'no_resep'),
                    'doctor_id' => auth('staff')->id(),
                    'status' => 'baru',
                    'catatan' => 'Resep elektronik dari pemeriksaan dokter.',
                ]);

                foreach ($medicineIds as $index => $medicineId) {
                    $medicine = \App\Models\InventoryMedicine::find($medicineId);
                    if (! $medicine) {
                        continue;
                    }

                    $qty = (float) ($data['jumlah'][$index] ?? 1);
                    $prescription->details()->create([
                        'inventory_medicine_id' => $medicine->id,
                        'nama_obat' => $medicine->nama_obat,
                        'jumlah' => $qty,
                        'satuan' => $medicine->satuan,
                        'aturan_pakai' => $data['aturan_pakai'][$index] ?? 'Sesuai instruksi dokter',
                        'rute' => 'oral',
                        'harga_satuan' => $medicine->harga_jual,
                        'subtotal' => $qty * (float) $medicine->harga_jual,
                    ]);
                }
            }

            foreach (array_filter($data['lab_items'] ?? []) as $labItem) {
                $encounter->labOrders()->create([
                    'no_order' => SimrsNumber::daily('LAB', 'lab_orders', 'no_order'),
                    'doctor_id' => auth('staff')->id(),
                    'jenis_pemeriksaan' => $labItem,
                    'prioritas' => request('lab_prioritas', 'rutin'),
                    'status' => 'order',
                    'catatan_klinis' => $data['diagnosis_kerja'],
                    'ordered_at' => now(),
                ]);
            }

            foreach (array_filter($data['radiology_items'] ?? []) as $radItem) {
                $encounter->radiologyOrders()->create([
                    'no_order' => SimrsNumber::daily('RAD', 'radiology_orders', 'no_order'),
                    'doctor_id' => auth('staff')->id(),
                    'jenis_pemeriksaan' => $radItem,
                    'prioritas' => request('radiology_prioritas', 'rutin'),
                    'status' => 'order',
                    'indikasi_klinis' => $data['diagnosis_kerja'],
                    'ordered_at' => now(),
                ]);
            }

            $encounter->update([
                'status_antrian' => $medicineIds ? 'menunggu_farmasi' : 'menunggu_kasir',
                'status_encounter' => 'diperiksa',
            ]);
        });

        $billingService->ensureInvoice($encounter->refresh());

        return redirect()->route('rekam-medis.antrian')
            ->with('swal_success', 'Rekam medis dan order pelayanan berhasil disimpan.');
    }
}
