<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\BillingInvoice;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FinancialReportController extends Controller
{
    public function revenue(Request $request): View
    {
        $from = $request->input('from', now()->subDays(30)->toDateString());
        $to = $request->input('to', now()->toDateString());

        return view('reports.revenue', [
            'from' => $from,
            'to' => $to,
            'invoices' => BillingInvoice::with(['encounter.patient', 'encounter.department'])
                ->whereDate('created_at', '>=', $from)
                ->whereDate('created_at', '<=', $to)
                ->latest()
                ->paginate(25)
                ->withQueryString(),
            'summary' => BillingInvoice::selectRaw('metode_penjamin, status, SUM(total_tagihan) as total_tagihan, SUM(total_dibayar) as total_dibayar, COUNT(*) as total_invoice')
                ->whereDate('created_at', '>=', $from)
                ->whereDate('created_at', '<=', $to)
                ->groupBy('metode_penjamin', 'status')
                ->get(),
        ]);
    }
}
