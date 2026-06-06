<?php

namespace App\Http\Controllers\Clinical;

use App\Http\Controllers\Controller;
use App\Models\Encounter;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DischargeController extends Controller
{
    public function resume(Encounter $encounter): View
    {
        $encounter->load(['patient', 'medicalRecord.bhps', 'prescriptions.details', 'labOrders.results', 'radiologyOrders.result', 'doctor', 'department']);
        
        return view('clinical.resume-medis', compact('encounter'));
    }
}
