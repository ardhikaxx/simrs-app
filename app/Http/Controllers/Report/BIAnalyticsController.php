<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\BillingInvoice;
use App\Models\Encounter;
use App\Models\Patient;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BIAnalyticsController extends Controller
{
    public function dashboard(): View
    {
        $stats = [
            'total_patients' => Patient::count(),
            'today_visits' => Encounter::whereDate('waktu_masuk', now()->toDateString())->count(),
            'monthly_revenue' => Payment::whereMonth('paid_at', now()->month)->sum('jumlah_bayar'),
            'bor' => $this->calculateBOR(),
        ];

        // Revenue Trend (Last 7 Days)
        $revenueTrend = Payment::select(DB::raw('DATE(paid_at) as date'), DB::raw('SUM(jumlah_bayar) as total'))
            ->where('paid_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Visits by Unit
        $unitStats = Encounter::select('department_id', DB::raw('count(*) as total'))
            ->with('department')
            ->groupBy('department_id')
            ->get();

        return view('reports.bi-dashboard', compact('stats', 'revenueTrend', 'unitStats'));
    }

    private function calculateBOR(): float
    {
        $totalBeds = \App\Models\Bed::count();
        $occupiedBeds = \App\Models\Bed::where('status', 'occupied')->count();
        return $totalBeds > 0 ? ($occupiedBeds / $totalBeds) * 100 : 0;
    }
}
