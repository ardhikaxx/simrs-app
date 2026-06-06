<?php

namespace App\Http\Controllers\Clinical;

use App\Http\Controllers\Controller;
use App\Models\Encounter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NursingAssessmentController extends Controller
{
    public function queue(): View
    {
        return view('clinical.nursing-queue', [
            'encounters' => Encounter::with(['patient', 'department', 'doctor', 'nursingAssessment'])
                ->whereNotIn('status_encounter', ['selesai', 'batal'])
                ->latest('waktu_masuk')
                ->paginate(20),
        ]);
    }

    public function edit(Encounter $encounter): View
    {
        $encounter->load(['patient', 'department', 'doctor', 'nursingAssessment']);

        return view('clinical.nursing-assessment', compact('encounter'));
    }

    public function store(Request $request, Encounter $encounter): RedirectResponse
    {
        $data = $request->validate([
            'tekanan_darah_sistolik' => ['nullable', 'integer', 'between:50,300'],
            'tekanan_darah_diastolik' => ['nullable', 'integer', 'between:30,200'],
            'nadi' => ['nullable', 'integer', 'between:20,300'],
            'suhu_tubuh' => ['nullable', 'numeric', 'between:30,45'],
            'pernapasan' => ['nullable', 'integer', 'between:5,80'],
            'saturasi_oksigen' => ['nullable', 'integer', 'between:0,100'],
            'skala_nyeri' => ['nullable', 'integer', 'between:0,10'],
            'berat_badan' => ['nullable', 'numeric', 'between:0.5,500'],
            'tinggi_badan' => ['nullable', 'numeric', 'between:20,300'],
            'triase' => ['nullable', 'in:hijau,kuning,merah,hitam'],
            'catatan_keperawatan' => ['nullable', 'string'],
        ]);

        $encounter->nursingAssessment()->updateOrCreate(
            ['encounter_id' => $encounter->id],
            $data + ['nurse_id' => auth('staff')->id(), 'assessed_at' => now()]
        );

        $encounter->update([
            'status_antrian' => 'pemeriksaan_dokter',
            'status_encounter' => 'dalam_perawatan',
        ]);

        return redirect()->route('keperawatan.antrian')
            ->with('swal_success', 'Asesmen keperawatan berhasil disimpan.');
    }
}
