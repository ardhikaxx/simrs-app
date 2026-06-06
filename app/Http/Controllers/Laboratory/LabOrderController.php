<?php

namespace App\Http\Controllers\Laboratory;

use App\Http\Controllers\Controller;
use App\Models\LabOrder;
use Illuminate\View\View;

class LabOrderController extends Controller
{
    public function index(): View
    {
        return view('laboratory.index', [
            'orders' => LabOrder::with(['encounter.patient', 'encounter.department', 'doctor', 'analyst', 'results'])
                ->latest('ordered_at')
                ->paginate(20),
        ]);
    }
}
