<?php

namespace App\Http\Controllers\Laboratory;

use App\Http\Controllers\Controller;
use App\Models\LabOrder;
use App\Models\Notification;
use App\Services\BillingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LabResultController extends Controller
{
    public function edit(LabOrder $labOrder): View
    {
        $labOrder->load(['encounter.patient', 'doctor', 'results']);

        return view('laboratory.result', compact('labOrder'));
    }

    public function update(Request $request, LabOrder $labOrder, BillingService $billingService): RedirectResponse
    {
        $data = $request->validate([
            'parameter' => ['required', 'array', 'min:1'],
            'parameter.*' => ['required', 'string', 'max:120'],
            'nilai' => ['required', 'array', 'min:1'],
            'satuan' => ['nullable', 'array'],
            'nilai_rujukan' => ['nullable', 'array'],
            'flag' => ['nullable', 'array'],
            'is_critical' => ['nullable', 'array'],
        ]);

        DB::transaction(function () use ($data, $labOrder) {
            $labOrder->results()->delete();

            foreach ($data['parameter'] as $index => $parameter) {
                $isCritical = in_array((string) $index, $data['is_critical'] ?? [], true);
                $labOrder->results()->create([
                    'parameter' => $parameter,
                    'nilai' => $data['nilai'][$index] ?? '',
                    'satuan' => $data['satuan'][$index] ?? null,
                    'nilai_rujukan' => $data['nilai_rujukan'][$index] ?? null,
                    'flag' => $data['flag'][$index] ?? 'normal',
                    'is_critical' => $isCritical,
                    'verified_by' => auth('staff')->id(),
                    'verified_at' => now(),
                ]);

                if ($isCritical && $labOrder->doctor_id) {
                    Notification::create([
                        'user_id' => $labOrder->doctor_id,
                        'tipe' => 'lab_kritis',
                        'judul' => 'Nilai kritis laboratorium',
                        'pesan' => "{$parameter}: " . ($data['nilai'][$index] ?? '-') . ' perlu konfirmasi segera.',
                        'url' => route('lab.hasil.edit', $labOrder),
                    ]);
                }
            }

            $labOrder->update([
                'analyst_id' => auth('staff')->id(),
                'status' => 'selesai',
                'sample_received_at' => $labOrder->sample_received_at ?: now(),
                'completed_at' => now(),
            ]);

            $labOrder->encounter->update(['status_antrian' => 'menunggu_kasir']);
        });

        $billingService->ensureInvoice($labOrder->encounter->refresh());

        return redirect()->route('lab.antrian')->with('swal_success', 'Hasil laboratorium berhasil diverifikasi.');
    }
}
