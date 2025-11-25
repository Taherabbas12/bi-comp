<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        // صلاحية: view_permissions
        // middleware: permission:view_permissions
        $permissions = Permission::paginate(20); // أو get()

        return view('admin.permissions.index', compact('permissions'));
    }

    // في هذه الحالة، نفترض أن الصلاحيات تُعرف فقط في Seeder (AdminUserSeeder).
    // يمكنك إضافة create/store/edit/update/destroy إذا كنت ترغب في إدارة الصلاحيات بشكل ديناميكي.
    // ولكن، من الأفضل تعريفها في Seeder لضمان التناسق.
    // دعنا نعرضها فقط.
}
