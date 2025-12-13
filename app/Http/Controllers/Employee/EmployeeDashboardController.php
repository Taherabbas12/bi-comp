<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class EmployeeDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $tasks = Task::where('assigned_to_user_id', $user->id)->get();

        return view('employee.dashboard.index', [
            'total' => $tasks->count(),
            'completed' => $tasks->where('progress_percentage', 100)->count(),
            'inProgress' => $tasks->whereBetween('progress_percentage', [1, 99])->count(),
            'delayed' => $tasks->where('due_date', '<', now())->where('progress_percentage', '<', 100)->count(),
            'lastUpdates' => $tasks->sortByDesc('updated_at')->take(5),
        ]);
    }
}
