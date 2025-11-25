<?php

namespace App\Http\Controllers\Response;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        // صلاحية: view_response_dashboard
        // middleware: permission:view_response_dashboard
        return view('response.dashboard.index');
    }
}
