<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Models\Encounter;
use Illuminate\View\View;

class QueueController extends Controller
{
    public function index(): View
    {
        return view('registration.queue.index', [
            'encounters' => Encounter::with(['patient', 'department', 'doctor'])
                ->whereNotIn('status_encounter', ['selesai', 'batal'])
                ->latest('waktu_masuk')
                ->paginate(20),
        ]);
    }
}
