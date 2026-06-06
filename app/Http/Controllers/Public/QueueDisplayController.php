<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Encounter;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QueueDisplayController extends Controller
{
    public function index(): View
    {
        $departments = Department::whereIn('jenis', ['rawat_jalan', 'igd'])
            ->where('is_active', true)
            ->get();

        $activeQueues = Encounter::with(['patient', 'department', 'doctor'])
            ->whereDate('waktu_masuk', now()->toDateString())
            ->whereIn('status_antrian', ['pemeriksaan_dokter', 'asesmen_perawat', 'menunggu'])
            ->latest('waktu_masuk')
            ->get();

        return view('public.queue-display', compact('departments', 'activeQueues'));
    }
}
