<?php

namespace App\Http\Controllers;

use App\Models\BillingInvoice;
use App\Models\Encounter;
use App\Models\InventoryMedicine;
use App\Models\LabResult;
use App\Models\Patient;
use App\Models\Prescription;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = now()->toDateString();
        $labels = collect(range(6, 0))->map(fn ($day) => now()->subDays($day)->format('d M'));
        $visitRows = Encounter::query()
            ->selectRaw('DATE(waktu_masuk) as tanggal, COUNT(*) as total')
            ->where('waktu_masuk', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('tanggal')
            ->pluck('total', 'tanggal');

        $visitSeries = collect(range(6, 0))->map(fn ($day) => (int) ($visitRows[now()->subDays($day)->toDateString()] ?? 0));

        return view('dashboard', [
            'metrics' => [
                'patients' => Patient::count(),
                'visits_today' => Encounter::whereDate('waktu_masuk', $today)->count(),
                'active_encounters' => Encounter::whereNotIn('status_encounter', ['selesai', 'batal'])->count(),
                'revenue_today' => BillingInvoice::whereDate('paid_at', $today)->sum('total_dibayar'),
            ],
            'queue' => Encounter::with(['patient', 'department', 'doctor'])
                ->whereNotIn('status_encounter', ['selesai', 'batal'])
                ->latest('waktu_masuk')
                ->limit(8)
                ->get(),
            'pendingPrescriptions' => Prescription::with(['encounter.patient', 'doctor'])
                ->whereIn('status', ['baru', 'diverifikasi'])
                ->latest()
                ->limit(6)
                ->get(),
            'lowStock' => InventoryMedicine::whereColumn('stok', '<=', 'stok_minimum')->orderBy('stok')->limit(6)->get(),
            'criticalLabs' => LabResult::with('order.encounter.patient')->where('is_critical', true)->latest()->limit(5)->get(),
            'visitLabels' => $labels,
            'visitSeries' => $visitSeries,
        ]);
    }
}
