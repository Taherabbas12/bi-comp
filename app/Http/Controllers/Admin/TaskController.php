<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Priority;
use App\Models\Task; // لجلب الموظفين
use App\Models\TaskStatus; // لجلب الأولويات
use App\Models\User; // لجلب الحالات
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        // Query Builder with Filters
        $query = Task::with(['assignedTo', 'supervisor', 'createdBy', 'priority', 'status']);

        // Filter by Assigned User
        if ($request->filled('assigned_to')) {
            $query->where('assigned_to_user_id', $request->assigned_to);
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status_id', $request->status);
        }

        // Filter by Priority
        if ($request->filled('priority')) {
            $query->where('priority_id', $request->priority);
        }

        // Filter by Date Range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('due_date', [$request->start_date, $request->end_date]);
        } elseif ($request->filled('start_date')) {
            $query->where('due_date', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $query->where('due_date', '<=', $request->end_date);
        }

        $tasks = $query->orderBy('due_date', 'asc')->orderBy('created_at', 'desc')->get();

        $users = User::orderBy('name')->get();
        $priorities = Priority::orderBy('name')->get();
        $statuses = TaskStatus::orderBy('name')->get();

        return view('admin.tasks.index', compact('tasks', 'users', 'priorities', 'statuses'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        $priorities = Priority::orderBy('name')->get();
        $statuses = TaskStatus::orderBy('name')->get();

        return view('admin.tasks.create', compact('users', 'priorities', 'statuses'));
    }

    public function store(StoreTaskRequest $request) // <-- تأكد من استخدام Form Request
    {

          $data = $request->validated();

        //   dd($data);
        // $data['created_by_user_id'] = auth()->id();
        // Task::create($data);
        // return redirect()->route('admin.tasks.index')->with('success', 'Task created successfully.');

        // أو مباشرة (كما في الكود الأصلي)
        // $request->validate([
        //     'title' => 'required|string|max:255',
        //     'description' => 'nullable|string',
        //     'start_date' => 'nullable|date|before_or_equal:due_date',
        //     'due_date' => 'nullable|date|after_or_equal:start_date',
        //     'assigned_to_user_id' => 'required|exists:users,id',
        //     'supervisor_user_id' => 'nullable|exists:users,id',
        //     'priority_id' => 'required|exists:priorities,id', // <-- هل هذا موجود؟
        //     'status_id' => 'required|exists:task_statuses,id', // <-- هل هذا موجود؟
        // ]);

    // $data[ 'created_by_user_id']=           auth()->id();

   $task = Task::create( $data);
        // $task = Task::create([
        //     'title' => $request->title,
        //     'description' => $request->description,
        //     'start_date' => $request->start_date,
        //     'due_date' => $request->due_date,
        //     'assigned_to_user_id' => $request->assigned_to_user_id,
        //     'supervisor_user_id' => $request->supervisor_user_id,
        //     'priority_id' => $request->priority_id,
        //     'status_id' => $request->status_id,
        //     'created_by_user_id' => auth()->id(),
        // ]);

        return redirect()->route('admin.tasks.index')->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        $task->load(['assignedTo', 'supervisor', 'createdBy', 'priority', 'status']);

        return view('admin.tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $users = User::orderBy('name')->get();
        $priorities = Priority::orderBy('name')->get();
        $statuses = TaskStatus::orderBy('name')->get();

        return view('admin.tasks.edit', compact('task', 'users', 'priorities', 'statuses'));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $data = $request->validated();
        $task->update($data);

        return redirect()->route('admin.tasks.index')->with('success', 'تم تحديث المهمة بنجاح.');
    }

    public function destroy(Task $task)
    {
        $task->delete(); // Soft Delete

        return redirect()->route('admin.tasks.index')->with('success', 'تم حذف المهمة بنجاح.');
    }
}
