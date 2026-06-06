<?php

namespace App\Http\Controllers\BPJS;

use App\Http\Controllers\Controller;
use App\Models\BPJSSepDocument;
use App\Models\Encounter;
use App\Services\BPJSVClaimService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VClaimController extends Controller
{
    public function index(Request $request, BPJSVClaimService $service): View
    {
        $participant = null;
        if ($request->filled('no_kartu')) {
            $participant = $service->cekPeserta($request->no_kartu, now()->toDateString());
        }

        return view('bpjs.index', [
            'participant' => $participant,
            'encounters' => Encounter::with(['patient', 'department', 'medicalRecord', 'sepDocument'])
                ->where('cara_bayar', 'bpjs')
                ->latest('waktu_masuk')
                ->paginate(20),
        ]);
    }

    public function createSep(Request $request, Encounter $encounter, BPJSVClaimService $service): RedirectResponse
    {
        $data = $request->validate([
            'diagnosis_awal' => ['required', 'exists:icd10,kode'],
        ]);

        abort_if(! $encounter->patient->no_bpjs, 422, 'Pasien belum memiliki nomor BPJS.');

        $response = $service->buatSEP($encounter, $data['diagnosis_awal']);

        BPJSSepDocument::updateOrCreate(
            ['encounter_id' => $encounter->id],
            [
                'no_sep' => $response['response']['noSep'],
                'no_kartu_bpjs' => $encounter->patient->no_bpjs,
                'diagnosis_awal' => $data['diagnosis_awal'],
                'status' => 'aktif',
                'request_payload' => $data,
                'response_payload' => $response,
                'issued_at' => now(),
            ]
        );

        return back()->with('swal_success', 'SEP BPJS simulasi berhasil dibuat.');
    }
}
