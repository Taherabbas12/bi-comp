<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskStatusRequest;
use App\Http\Requests\UpdateTaskStatusRequest;
use App\Models\TaskStatus;

class TaskStatusController extends Controller
{
    public function index()
    {
        $statuses = TaskStatus::orderBy('name')->get();

        return view('admin.task_statuses.index', compact('statuses'));
    }

    public function create()
    {
        return view('admin.task_statuses.create');
    }

    public function store(StoreTaskStatusRequest $request)
    {
        TaskStatus::create($request->validated());

        return redirect()->route('admin.task_statuses.index')->with('success', 'تم إنشاء الحالة بنجاح.');
    }

    public function show(TaskStatus $taskStatus)
    {
        return view('admin.task_statuses.show', compact('taskStatus'));
    }

    public function edit(TaskStatus $taskStatus)
    {
        return view('admin.task_statuses.edit', compact('taskStatus'));
    }

    public function update(UpdateTaskStatusRequest $request, TaskStatus $taskStatus)
    {
        $taskStatus->update($request->validated());

        return redirect()->route('admin.task_statuses.index')->with('success', 'تم تحديث الحالة بنجاح.');
    }

    public function destroy(TaskStatus $taskStatus)
    {
        $taskStatus->delete(); // Soft Delete

        return redirect()->route('admin.task_statuses.index')->with('success', 'تم حذف الحالة بنجاح.');
    }
}
