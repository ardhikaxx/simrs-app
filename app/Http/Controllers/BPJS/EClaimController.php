<?php

namespace App\Http\Controllers\BPJS;

use App\Http\Controllers\Controller;
use App\Models\Encounter;
use App\Services\INACBGCalculatorService;
use Illuminate\Http\RedirectResponse;

class EClaimController extends Controller
{
    public function simulate(Encounter $encounter, INACBGCalculatorService $calculator): RedirectResponse
    {
        $result = $calculator->calculateUtilization($encounter->id);
        $status = $result['status'] ?? 'no_tarif';

        return back()->with(
            $status === 'kritis' ? 'swal_warning' : 'swal_success',
            'Simulasi e-Claim: ' . ($result['pesan'] ?? $result['message'] ?? 'Data belum lengkap.')
        );
    }

    public function submit(Encounter $encounter): RedirectResponse
    {
        abort_if(! $encounter->sepDocument, 422, 'SEP wajib dibuat sebelum klaim diajukan.');

        return back()->with('swal_success', 'Klaim INA-CBG simulasi berhasil diajukan ke antrean verifikator.');
    }
}
