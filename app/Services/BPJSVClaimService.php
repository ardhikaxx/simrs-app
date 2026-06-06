<?php

namespace App\Services;

use App\Models\Encounter;

class BPJSVClaimService
{
    public function cekPeserta(string $noKartu, string $tanggal): array
    {
        return [
            'metadata' => ['code' => 200, 'message' => 'OK - simulasi lokal'],
            'response' => [
                'noKartu' => $noKartu,
                'tglSEP' => $tanggal,
                'statusPeserta' => 'AKTIF',
                'hakKelas' => 'Kelas 2',
                'jenisPeserta' => 'PBI APBN',
            ],
        ];
    }

    public function buatSEP(Encounter $encounter, string $diagnosisAwal): array
    {
        $encounter->loadMissing(['patient', 'department']);

        return [
            'metadata' => ['code' => 200, 'message' => 'SEP berhasil dibuat - simulasi lokal'],
            'response' => [
                'noSep' => 'SEP' . now()->format('ymd') . str_pad((string) $encounter->id, 6, '0', STR_PAD_LEFT),
                'noKartu' => $encounter->patient->no_bpjs,
                'noMR' => $encounter->patient->no_rkm_medis,
                'nama' => $encounter->patient->nama_pasien,
                'poli' => $encounter->department->nama_depart,
                'diagAwal' => $diagnosisAwal,
                'jnsPelayanan' => $encounter->jenis_kunjungan === 'rawat_inap' ? 'Rawat Inap' : 'Rawat Jalan',
            ],
        ];
    }
}
