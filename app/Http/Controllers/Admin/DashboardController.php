<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        // صلاحية: view_dashboard
        // middleware: permission:view_dashboard
        return view('admin.dashboard.index');
    }
}
