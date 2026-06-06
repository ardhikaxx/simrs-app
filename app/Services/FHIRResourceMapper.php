<?php

namespace App\Services;

use App\Models\Encounter;
use App\Models\MedicalRecord;
use App\Models\Patient;

class FHIRResourceMapper
{
    public function mapPatient(Patient $patient): array
    {
        return [
            'resourceType' => 'Patient',
            'id' => $patient->no_rkm_medis,
            'identifier' => [
                ['system' => 'https://fhir.kemkes.go.id/id/nik', 'value' => $patient->nik],
                ['system' => 'https://fhir.kemkes.go.id/id/ihs-number', 'value' => $patient->no_bpjs],
            ],
            'name' => [['use' => 'official', 'text' => $patient->nama_pasien]],
            'birthDate' => $patient->tgl_lahir->format('Y-m-d'),
            'gender' => $patient->jenis_kelamin === 'L' ? 'male' : 'female',
            'address' => [[
                'use' => 'home',
                'text' => $patient->alamat_lengkap,
                'city' => $patient->kota,
                'state' => $patient->provinsi,
                'country' => 'ID',
            ]],
            'telecom' => $patient->no_telp_pasien ? [['system' => 'phone', 'value' => $patient->no_telp_pasien, 'use' => 'mobile']] : [],
        ];
    }

    public function mapEncounter(Encounter $encounter): array
    {
        $encounter->loadMissing(['patient', 'doctor', 'department']);

        return [
            'resourceType' => 'Encounter',
            'id' => $encounter->no_registrasi,
            'status' => $encounter->status_encounter === 'selesai' ? 'finished' : 'in-progress',
            'class' => [
                'system' => 'http://terminology.hl7.org/CodeSystem/v3-ActCode',
                'code' => $encounter->jenis_kunjungan === 'rawat_inap' ? 'IMP' : 'AMB',
            ],
            'subject' => [
                'reference' => 'Patient/' . $encounter->patient->no_rkm_medis,
                'display' => $encounter->patient->nama_pasien,
            ],
            'participant' => [[
                'individual' => [
                    'reference' => 'Practitioner/' . $encounter->doctor?->nip,
                    'display' => $encounter->doctor?->display_name,
                ],
            ]],
            'period' => [
                'start' => $encounter->waktu_masuk->toIso8601String(),
                'end' => $encounter->waktu_keluar?->toIso8601String(),
            ],
            'serviceProvider' => ['reference' => 'Organization/' . config('satusehat.organization_id', 'SIMRS-DEMO')],
        ];
    }

    public function mapCondition(MedicalRecord $record): array
    {
        $record->loadMissing('encounter.patient');

        return [
            'resourceType' => 'Condition',
            'clinicalStatus' => ['coding' => [['system' => 'http://terminology.hl7.org/CodeSystem/condition-clinical', 'code' => 'active']]],
            'code' => ['coding' => [[
                'system' => 'http://hl7.org/fhir/sid/icd-10',
                'code' => $record->icd10_primer,
                'display' => $record->diagnosis_kerja,
            ]]],
            'subject' => ['reference' => 'Patient/' . $record->encounter->patient->no_rkm_medis],
            'encounter' => ['reference' => 'Encounter/' . $record->encounter->no_registrasi],
        ];
    }
}
