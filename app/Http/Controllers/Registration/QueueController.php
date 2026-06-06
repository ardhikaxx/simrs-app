<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Models\Encounter;
use Illuminate\View\View;

use Illuminate\Http\Request;

class QueueController extends Controller
{
    public function index(Request $request): View
    {
        $encounters = Encounter::with(['patient', 'department', 'doctor'])
            ->whereNotIn('status_encounter', ['selesai', 'batal'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%' . $request->q . '%';
                $query->where('no_registrasi', 'like', $term)
                    ->orWhere('no_antrian', 'like', $term)
                    ->orWhereHas('patient', function ($q) use ($term) {
                        $q->where('nama_pasien', 'like', $term)
                            ->orWhere('no_rkm_medis', 'like', $term);
                    });
            })
            ->latest('waktu_masuk')
            ->paginate(20)
            ->withQueryString();

        return view('registration.queue.index', compact('encounters'));
    }
}
