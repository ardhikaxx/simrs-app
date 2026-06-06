<?php

namespace App\Services;

use App\Models\BillingInvoice;
use App\Models\Encounter;
use App\Support\SimrsNumber;
use Illuminate\Support\Facades\DB;

class BillingService
{
    public function ensureInvoice(Encounter $encounter): BillingInvoice
    {
        return DB::transaction(function () use ($encounter) {
            $encounter->loadMissing([
                'department',
                'medicalRecord',
                'prescriptions.details',
                'labOrders',
                'radiologyOrders',
                'billingInvoice.billingDetails',
            ]);

            $invoice = $encounter->billingInvoice ?: BillingInvoice::create([
                'no_invoice' => SimrsNumber::daily('INV', 'billing_invoices', 'no_invoice'),
                'encounter_id' => $encounter->id,
                'metode_penjamin' => $encounter->cara_bayar,
                'status' => 'draft',
                'issued_at' => now(),
            ]);

            $invoice->billingDetails()->delete();

            $items = [
                ['Registrasi', 'Administrasi pendaftaran pasien', 1, 25000],
                ['Konsultasi', 'Jasa konsultasi ' . ($encounter->department?->nama_depart ?? 'Dokter'), 1, $this->consultationTariff($encounter)],
            ];

            if ($encounter->nursingAssessment) {
                $items[] = ['Keperawatan', 'Asesmen awal dan triase keperawatan', 1, 35000];
            }

            foreach ($encounter->labOrders as $order) {
                $items[] = ['Laboratorium', $order->jenis_pemeriksaan . ' (' . strtoupper($order->prioritas) . ')', 1, $order->prioritas === 'cito' ? 210000 : 150000];
            }

            foreach ($encounter->radiologyOrders as $order) {
                $items[] = ['Radiologi', $order->jenis_pemeriksaan, 1, str_contains(strtolower($order->jenis_pemeriksaan), 'ct') ? 850000 : 280000];
            }

            foreach ($encounter->prescriptions as $prescription) {
                foreach ($prescription->details as $detail) {
                    $items[] = ['Farmasi', $detail->nama_obat . ' - ' . $detail->aturan_pakai, $detail->jumlah, $detail->harga_satuan];
                }
            }

            if ($encounter->jenis_kunjungan === 'rawat_inap') {
                $days = max(1, (int) $encounter->waktu_masuk->diffInDays($encounter->waktu_keluar ?: now()) + 1);
                $items[] = ['Rawat Inap', 'Akomodasi kamar ' . ($encounter->kelas_rawat ?: 'Kelas II'), $days, 450000];
            }

            $subtotal = 0;
            foreach ($items as [$kategori, $deskripsi, $qty, $harga]) {
                $lineSubtotal = (float) $qty * (float) $harga;
                $subtotal += $lineSubtotal;
                $invoice->billingDetails()->create([
                    'kategori' => $kategori,
                    'deskripsi' => $deskripsi,
                    'qty' => $qty,
                    'harga_satuan' => $harga,
                    'subtotal' => $lineSubtotal,
                ]);
            }

            $tariff = app(INACBGCalculatorService::class)->findTariff($encounter);
            $utilization = $tariff ? app(INACBGCalculatorService::class)->statusFor($subtotal, (float) $tariff->tarif_total) : null;

            $invoice->update([
                'subtotal' => $subtotal,
                'diskon' => 0,
                'total_tagihan' => $subtotal,
                'tarif_ina_cbg' => $tariff?->tarif_total,
                'status_utilisasi' => $utilization['status'] ?? null,
            ]);

            return $invoice->refresh()->load(['billingDetails', 'payments', 'encounter.patient']);
        });
    }

    private function consultationTariff(Encounter $encounter): int
    {
        return match ($encounter->department?->jenis) {
            'igd' => 175000,
            'rawat_inap' => 200000,
            default => 125000,
        };
    }
}
