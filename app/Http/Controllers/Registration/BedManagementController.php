<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Models\Bed;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BedManagementController extends Controller
{
    public function index(Request $request): View
    {
        $departments = Department::where('jenis', 'rawat_inap')->with('beds')->get();
        
        $stats = [
            'total' => Bed::count(),
            'available' => Bed::where('status', 'available')->count(),
            'occupied' => Bed::where('status', 'occupied')->count(),
            'cleaning' => Bed::where('status', 'cleaning')->count(),
        ];

        return view('registration.beds.index', compact('departments', 'stats'));
    }
}
