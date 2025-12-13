<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Priority;
use App\Models\Task;
use App\Models\TaskStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * عرض قائمة المهام الخاصة بالموظف الحالي
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // جميع المهام المسندة لهذا الموظف
        $query = Task::with(['status', 'priority', 'createdBy', 'supervisor'])
            ->where('assigned_to_user_id', $user->id);

        // فلترة بالحالة
        if ($request->filled('status')) {
            $query->where('status_id', $request->status);
        }

        // فلترة بالأولوية
        if ($request->filled('priority')) {
            $query->where('priority_id', $request->priority);
        }

        // فلترة بتاريخ الاستحقاق
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('due_date', [$request->start_date, $request->end_date]);
        } elseif ($request->filled('start_date')) {
            $query->where('due_date', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $query->where('due_date', '<=', $request->end_date);
        }

        // فلترة بالبحث في العنوان أو الوصف
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
            });
        }

        $tasks = $query
            ->orderBy('due_date', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        $statuses = TaskStatus::orderBy('display_name')->get();
        $priorities = Priority::orderBy('display_name')->get();

        // إحصائيات بسيطة للواجهة
        $totalTasks = $tasks->count();
        $completedTasks = $tasks->where('progress_percentage', 100)->count();
        $inProgressTasks = $tasks->where('progress_percentage', '>', 0)->where('progress_percentage', '<', 100)->count();

        return view('employee.tasks.index', compact(
            'tasks',
            'statuses',
            'priorities',
            'user',
            'totalTasks',
            'completedTasks',
            'inProgressTasks'
        ));
    }

    /**
     * عرض تفاصيل مهمة واحدة للموظف
     */
    public function show(Task $task)
    {
        $user = Auth::user();

        // السماح فقط إذا كانت المهمة مسندة للموظف أو هو المشرف عليها
        if ($task->assigned_to_user_id !== $user->id && $task->supervisor_user_id !== $user->id) {
            abort(403, 'غير مصرح لك بعرض هذه المهمة.');
        }

        $task->load(['status', 'priority', 'assignedTo', 'supervisor', 'createdBy']);

        return view('employee.tasks.show', compact('task', 'user'));
    }

    /**
     * تحديث نسبة الإنجاز للمهمة
     */
    public function updateProgress(Request $request, Task $task)
    {
        $user = Auth::user();

        if ($task->assigned_to_user_id !== $user->id && $task->supervisor_user_id !== $user->id) {
            abort(403, 'غير مصرح لك بتعديل هذه المهمة.');
        }

        $data = $request->validate([
            'progress_percentage' => ['required', 'integer', 'min:0', 'max:100'],
        ], [
            'progress_percentage.required' => 'يرجى إدخال نسبة الإنجاز.',
            'progress_percentage.integer' => 'نسبة الإنجاز يجب أن تكون رقمًا صحيحًا.',
            'progress_percentage.min' => 'أقل نسبة إنجاز هي 0%.',
            'progress_percentage.max' => 'أعلى نسبة إنجاز هي 100%.',
        ]);

        $task->update([
            'progress_percentage' => $data['progress_percentage'],
        ]);

        return redirect()
            ->route('employee.tasks.show', $task)
            ->with('success', 'تم تحديث نسبة الإنجاز بنجاح.');
    }
}
