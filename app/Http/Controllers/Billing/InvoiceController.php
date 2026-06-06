<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\BillingInvoice;
use App\Models\Encounter;
use App\Services\BillingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function queue(): View
    {
        return view('billing.index', [
            'invoices' => BillingInvoice::with(['encounter.patient', 'encounter.department', 'payments'])
                ->latest('issued_at')
                ->paginate(20),
        ]);
    }

    public function show(BillingInvoice $invoice, BillingService $billingService): View
    {
        $invoice = $billingService->ensureInvoice($invoice->encounter);

        return view('billing.invoice', compact('invoice'));
    }

    public function generate(Encounter $encounter, BillingService $billingService): RedirectResponse
    {
        $invoice = $billingService->ensureInvoice($encounter);

        return redirect()->route('keuangan.invoice.show', $invoice)
            ->with('swal_success', 'Invoice berhasil dihitung ulang.');
    }
}
