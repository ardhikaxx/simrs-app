<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class BIAnalyticsController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('dashboard');
    }
}
