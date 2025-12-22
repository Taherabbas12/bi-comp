<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $users = User::with('role')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('telegram_id', 'like', "%{$search}%")
                        ->orWhere('national_id', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(9)
            ->withQueryString(); // مهم حتى لا يضيع البحث مع الصفحات

        return view('admin.users.index', compact('users', 'search'));
    }

    public function create()
    {
        // صلاحية: create_users
        // middleware: permission:create_users
        // dd('AAA');
        $roles = Role::all();

        return view('admin.users.create', compact('roles'));
    }

    public function show(User $user) // <-- تأكد من أن $user هو نموذج App\Models\User
    {
        // صلاحية: view_users (موجودة في AdminUserSeeder)
        // middleware: permission:view_users (مطبق في routes/web.php)

        // عادةً ما يتم تحميل العلاقة مع 'role' لعرضها في الواجهة
        $roles = Role::all();

        return view('admin.users.show', compact('user', 'roles'));
        // $user->load('role');

        // // تمرير المستخدم إلى واجهة Blade لعرض التفاصيل
        // return view('admin.users.show', compact('user'));
    }

    public function store(Request $request)
    {
        // صلاحية: create_users
        // middleware: permission:create_users

        // --- إضافة dd ---
        // \Log::info('Request Data for User Store:', $request->all()); // تسجيل البيانات
        // dd($request->all()); // طباعة البيانات وتعطيل التنفيذ مؤقتًا
        // // --- النهاية ---

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role_id' => 'nullable|exists:roles,id',

            'phone' => 'nullable|string|max:20',
            'telegram_id' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'national_id' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => $request->role_id,

            'phone' => $request->phone,
            'telegram_id' => $request->telegram_id,
            'address' => $request->address,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'national_id' => $request->national_id,
            'notes' => $request->notes,
        ]);
        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        // صلاحية: edit_users
        // middleware: permission:edit_users
        $roles = Role::all();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        // صلاحية: edit_users
        // middleware: permission:edit_users
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role_id' => 'nullable|exists:roles,id',

            'phone' => 'nullable|string|max:20',
            'telegram_id' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'national_id' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $user->update($request->only([
            'name',
            'email',
            'role_id',
            'phone',
            'telegram_id',
            'address',
            'birth_date',
            'gender',
            'national_id',
            'notes',
        ]));

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $user->update(['password' => bcrypt($request->password)]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        // صلاحية: delete_users
        // middleware: permission:delete_users
        $user->delete(); // soft delete

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
