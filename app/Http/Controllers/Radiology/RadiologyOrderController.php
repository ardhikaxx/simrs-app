<?php

namespace App\Http\Controllers\Radiology;

use App\Http\Controllers\Controller;
use App\Models\RadiologyOrder;
use Illuminate\View\View;

class RadiologyOrderController extends Controller
{
    public function index(): View
    {
        return view('radiology.index', [
            'orders' => RadiologyOrder::with(['encounter.patient', 'encounter.department', 'doctor', 'radiographer', 'result'])
                ->latest('ordered_at')
                ->paginate(20),
        ]);
    }
}
