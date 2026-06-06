<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\BillingInvoice;
use App\Models\Encounter;
use App\Services\BillingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function queue(Request $request): View
    {
        $invoices = BillingInvoice::with(['encounter.patient', 'encounter.department', 'payments'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%' . $request->q . '%';
                $query->where('no_invoice', 'like', $term)
                    ->orWhereHas('encounter.patient', function ($q) use ($term) {
                        $q->where('nama_pasien', 'like', $term)
                            ->orWhere('no_rkm_medis', 'like', $term);
                    });
            })
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest('issued_at')
            ->paginate(20)
            ->withQueryString();

        return view('billing.index', compact('invoices'));
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
