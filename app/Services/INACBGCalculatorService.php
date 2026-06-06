<?php

namespace App\Services;

use App\Models\Encounter;
use App\Models\INACBGTariff;

class INACBGCalculatorService
{
    public function calculateUtilization(int $encounterId): array
    {
        $encounter = Encounter::with(['medicalRecord', 'billingInvoice'])->findOrFail($encounterId);
        $invoice = app(BillingService::class)->ensureInvoice($encounter);
        $tariff = $this->findTariff($encounter);

        if (! $tariff) {
            return [
                'status' => 'no_tarif',
                'message' => 'Tarif INA-CBG belum tersedia untuk diagnosis utama ini.',
            ];
        }

        return $this->statusFor((float) $invoice->total_tagihan, (float) $tariff->tarif_total) + [
            'total_riil' => (float) $invoice->total_tagihan,
            'tarif_ina_cbg' => (float) $tariff->tarif_total,
            'selisih' => (float) $tariff->tarif_total - (float) $invoice->total_tagihan,
            'icd10' => $encounter->medicalRecord?->icd10_primer,
            'kode_inacbg' => $tariff->kode_inacbg,
        ];
    }

    public function findTariff(Encounter $encounter): ?INACBGTariff
    {
        $icd10 = $encounter->medicalRecord?->icd10_primer;

        if (! $icd10) {
            return null;
        }

        return INACBGTariff::query()
            ->where('icd10_kode', $icd10)
            ->where('kelas_rs', config('simrs.kelas_rs', 'B'))
            ->where('jenis_rawat', $encounter->jenis_kunjungan === 'rawat_inap' ? 'rawat_inap' : 'rawat_jalan')
            ->first();
    }

    public function statusFor(float $totalRiil, float $tarif): array
    {
        if ($tarif <= 0) {
            return ['status' => 'no_tarif', 'persen' => 0, 'pesan' => 'Tarif INA-CBG tidak valid.'];
        }

        $percent = round(($totalRiil / $tarif) * 100, 2);
        $status = match (true) {
            $percent >= 95 => 'kritis',
            $percent >= 80 => 'peringatan',
            default => 'aman',
        };

        return [
            'status' => $status,
            'persen' => $percent,
            'pesan' => match ($status) {
                'kritis' => 'KRITIS: biaya riil mendekati atau melebihi ceiling INA-CBG.',
                'peringatan' => 'PERINGATAN: utilisasi biaya sudah melewati 80% ceiling.',
                default => 'Aman: utilisasi biaya masih dalam batas.',
            },
        ];
    }
}
