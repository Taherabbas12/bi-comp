<?php

namespace App\Http\Controllers\Preparation;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        // صلاحية: view_preparation_dashboard
        // middleware: permission:view_preparation_dashboard
        return view('preparation.dashboard.index');
    }
}
