<?php

namespace App\Http\Controllers\Radiology;

use App\Http\Controllers\Controller;
use App\Models\RadiologyOrder;
use App\Services\BillingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RadiologyResultController extends Controller
{
    public function edit(RadiologyOrder $radiologyOrder): View
    {
        $radiologyOrder->load(['encounter.patient', 'doctor', 'result']);

        return view('radiology.result', compact('radiologyOrder'));
    }

    public function update(Request $request, RadiologyOrder $radiologyOrder, BillingService $billingService): RedirectResponse
    {
        $data = $request->validate([
            'temuan' => ['required', 'string', 'min:5'],
            'kesan' => ['required', 'string', 'min:5'],
            'image_path' => ['nullable', 'string', 'max:255'],
        ]);

        $radiologyOrder->result()->updateOrCreate(
            ['radiology_order_id' => $radiologyOrder->id],
            $data + ['verified_by' => auth('staff')->id(), 'verified_at' => now()]
        );

        $radiologyOrder->update([
            'radiographer_id' => auth('staff')->id(),
            'status' => 'selesai',
            'completed_at' => now(),
        ]);
        $radiologyOrder->encounter->update(['status_antrian' => 'menunggu_kasir']);

        $billingService->ensureInvoice($radiologyOrder->encounter->refresh());

        return redirect()->route('rad.antrian')->with('swal_success', 'Hasil radiologi berhasil disimpan.');
    }
}
