<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\BillingInvoice;
use App\Models\Encounter;
use App\Services\INACBGCalculatorService;
use Illuminate\View\View;

class CasemixController extends Controller
{
    public function index(): View
    {
        return view('casemix.index', [
            'invoices' => BillingInvoice::with(['encounter.patient', 'encounter.medicalRecord', 'encounter.sepDocument'])
                ->where('metode_penjamin', 'bpjs')
                ->latest()
                ->paginate(20),
        ]);
    }

    public function simulate(Encounter $encounter, INACBGCalculatorService $calculator): View
    {
        $result = $calculator->calculateUtilization($encounter->id);

        return view('casemix.simulate', [
            'encounter' => $encounter->load(['patient', 'medicalRecord', 'billingInvoice.billingDetails']),
            'result' => $result,
        ]);
    }
}
