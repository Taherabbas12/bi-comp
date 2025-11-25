<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePriorityRequest;
use App\Http\Requests\UpdatePriorityRequest;
use App\Models\Priority;

class PriorityController extends Controller
{
    public function index()
    {
        $priorities = Priority::orderBy('name')->get();

        return view('admin.priorities.index', compact('priorities'));
    }

    public function create()
    {
        return view('admin.priorities.create');
    }

    public function store(StorePriorityRequest $request)
    {
        Priority::create($request->validated());

        return redirect()->route('admin.priorities.index')->with('success', 'تم إنشاء الأولوية بنجاح.');
    }

    public function show(Priority $priority)
    {
        return view('admin.priorities.show', compact('priority'));
    }

    public function edit(Priority $priority)
    {
        return view('admin.priorities.edit', compact('priority'));
    }

    public function update(UpdatePriorityRequest $request, Priority $priority)
    {
        $priority->update($request->validated());

        return redirect()->route('admin.priorities.index')->with('success', 'تم تحديث الأولوية بنجاح.');
    }

    public function destroy(Priority $priority)
    {
        $priority->delete(); // Soft Delete

        return redirect()->route('admin.priorities.index')->with('success', 'تم حذف الأولوية بنجاح.');
    }
}
