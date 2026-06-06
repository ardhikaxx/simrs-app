<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Encounter;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClinicalReportController extends Controller
{
    public function visits(Request $request): View
    {
        $from = $request->input('from', now()->subDays(30)->toDateString());
        $to = $request->input('to', now()->toDateString());

        return view('reports.visits', [
            'from' => $from,
            'to' => $to,
            'rows' => Encounter::with(['patient', 'department', 'doctor'])
                ->whereDate('waktu_masuk', '>=', $from)
                ->whereDate('waktu_masuk', '<=', $to)
                ->latest('waktu_masuk')
                ->paginate(25)
                ->withQueryString(),
            'summary' => Encounter::selectRaw('jenis_kunjungan, cara_bayar, COUNT(*) as total')
                ->whereDate('waktu_masuk', '>=', $from)
                ->whereDate('waktu_masuk', '<=', $to)
                ->groupBy('jenis_kunjungan', 'cara_bayar')
                ->get(),
        ]);
    }

    public function morbidity(Request $request): View
    {
        $from = $request->input('from', now()->subDays(30)->toDateString());
        $to = $request->input('to', now()->toDateString());

        return view('reports.morbidity', [
            'from' => $from,
            'to' => $to,
            'rows' => MedicalRecord::query()
                ->selectRaw('icd10_primer, diagnosis_kerja, COUNT(*) as total')
                ->whereDate('created_at', '>=', $from)
                ->whereDate('created_at', '<=', $to)
                ->groupBy('icd10_primer', 'diagnosis_kerja')
                ->orderByDesc('total')
                ->paginate(25)
                ->withQueryString(),
        ]);
    }

    public function export(string $type)
    {
        abort_unless(in_array($type, ['kunjungan', 'morbiditas'], true), 404);

        return response()->streamDownload(function () use ($type) {
            echo "jenis,periode,total\n";
            if ($type === 'kunjungan') {
                Encounter::selectRaw('jenis_kunjungan as jenis, DATE(waktu_masuk) as periode, COUNT(*) as total')
                    ->groupBy('jenis', 'periode')
                    ->orderBy('periode')
                    ->each(fn ($row) => print "{$row->jenis},{$row->periode},{$row->total}\n");
            } else {
                MedicalRecord::selectRaw('icd10_primer as jenis, DATE(created_at) as periode, COUNT(*) as total')
                    ->groupBy('jenis', 'periode')
                    ->orderBy('periode')
                    ->each(fn ($row) => print "{$row->jenis},{$row->periode},{$row->total}\n");
            }
        }, "laporan-{$type}.csv");
    }
}
