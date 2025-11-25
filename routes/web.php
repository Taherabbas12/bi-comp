<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\LaptopController as AdminLaptopController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PermissionController as AdminPermissionController;
use App\Http\Controllers\Admin\PriorityController; // <-- تأكد من تعريفها إذا كنت تستخدمها
use App\Http\Controllers\Admin\RoleController as AdminRoleController;
use App\Http\Controllers\Admin\TaskController; // <-- تأكد من تعريفها إذا كنت تستخدمها
use App\Http\Controllers\Admin\TaskStatusController; // <-- تأكد من تعريفها إذا كنت تستخدمها
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Customer\LaptopController as CustomerLaptopController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Preparation\DashboardController as PreparationDashboardController;
use App\Http\Controllers\Preparation\OrderController as PreparationOrderController;
use App\Http\Controllers\Response\DashboardController as ResponseDashboardController;
use App\Http\Controllers\Response\OrderController as ResponseOrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// --- تسجيل الدخول ---
Route::get('/login', fn () => view('auth.login'))->name('login')->middleware('guest');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials, $request->filled('remember'))) {
        $request->session()->regenerate();
        $user = Auth::user();

        if ($user->hasPermission('view_dashboard')) {
            return redirect()->intended('/admin/dashboard');
        } elseif ($user->hasPermission('view_response_dashboard')) {
            return redirect()->intended('/response/dashboard');
        } elseif ($user->hasPermission('view_preparation_dashboard')) {
            return redirect()->intended('/preparation/dashboard');
        }

        return redirect()->intended('/');
    }

    return back()->withErrors([
        'email' => 'البيانات المدخلة غير صحيحة.',
    ])->withInput($request->only('email'));
})->name('login.attempt')->middleware('guest');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
})->name('logout');

// --- واجهة العميل بدون تسجيل دخول ---
Route::get('/', [CustomerLaptopController::class, 'index'])->name('home');
Route::get('/laptops', [CustomerLaptopController::class, 'index'])->name('laptops.index');
Route::get('/laptops/{barcode}', [CustomerLaptopController::class, 'show'])->name('laptops.show');

// الطلبات تتطلب تسجيل دخول
Route::post('/orders', [CustomerOrderController::class, 'store'])->name('orders.store')->middleware('auth');
Route::get('/orders/{order}/success', [CustomerOrderController::class, 'success'])->name('orders.success')->middleware('auth');

// --- واجهة المشرف ---
// Route::prefix('admin')->middleware(['auth', 'permission:view_dashboard'])->group(function () {

Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // --- تعديل: فصل Routes المطلوبة ---
    // هذه routes يمكن الوصول إليها إذا كان المستخدم مسجّل دخول وله صلاحية view_dashboard فقط
    Route::get('/laptops', [AdminLaptopController::class, 'index'])->name('admin.laptops.index');
    Route::get('/laptops/create', [AdminLaptopController::class, 'create'])->name('admin.laptops.create');

    // --- تعديل: إضافة Route مُعرفة يدويًا لـ admin.orders.index ---
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index'); // <-- هذا هو السطر المهم

    // --- باقي Routes المشرف ---
    // هذه المجموعة تتطلب صلاحيات إضافية
    Route::group(['middleware' => 'permission:view_laptops'], function () { // Middleware على المجموعة
        Route::get('/laptops/{laptop}', [AdminLaptopController::class, 'show'])->name('admin.laptops.show');
        Route::get('/laptops/{laptop}/edit', [AdminLaptopController::class, 'edit'])->name('admin.laptops.edit');
        Route::put('/laptops/{laptop}', [AdminLaptopController::class, 'update'])->name('admin.laptops.update');
        Route::delete('/laptops/{laptop}', [AdminLaptopController::class, 'destroy'])->name('admin.laptops.destroy');
        // Route::get('/laptops/filter', [LaptopController::class, 'filter'])->name('admin.laptops.filter'); // <-- تأكد من تعريفها
    });

    // Route خاصة بـ store تحتاج صلاحية create_laptops
    Route::post('/laptops', [AdminLaptopController::class, 'store'])->name('admin.laptops.store')->middleware('permission:create_laptops');

    // --- Routes خاصة بالطلبات ---
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('admin.orders.show')->middleware('permission:view_orders');
    Route::put('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.updateStatus')->middleware('permission:update_order_status');

    // --- Routes خاصة بالمستخدمين ---
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');

    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('admin.users.show');
    Route::post('/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit')->middleware('permission:edit_users');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update')->middleware('permission:edit_users');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy')->middleware('permission:delete_users');

    // --- Routes خاصة بالأدوار ---
    Route::get('/roles', [AdminRoleController::class, 'index'])->name('admin.roles.index')->middleware('permission:view_roles');
    Route::get('/roles/{role}', [AdminRoleController::class, 'show'])->name('admin.roles.show')->middleware('permission:view_roles');
    Route::get('/roles/create', [AdminRoleController::class, 'create'])->name('admin.roles.create')->middleware('permission:create_roles');
    Route::post('/roles', [AdminRoleController::class, 'store'])->name('admin.roles.store')->middleware('permission:create_roles');
    Route::get('/roles/{role}/edit', [AdminRoleController::class, 'edit'])->name('admin.roles.edit')->middleware('permission:edit_roles');
    Route::put('/roles/{role}', [AdminRoleController::class, 'update'])->name('admin.roles.update')->middleware('permission:edit_roles');
    Route::delete('/roles/{role}', [AdminRoleController::class, 'destroy'])->name('admin.roles.destroy')->middleware('permission:delete_roles');

    // --- Routes خاصة بالصلاحيات ---
    Route::get('/permissions', [AdminPermissionController::class, 'index'])->name('admin.permissions.index');

    // --- Routes خاصة بالمهام (Tasks) (إذا كنت تستخدمها) ---
    Route::resource('tasks', TaskController::class)
        // ->middleware('permission:manage_tasks')
        ->names([
            'index' => 'admin.tasks.index', // <-- إضافة 'admin.' يدويًا
            'create' => 'admin.tasks.create',
            'store' => 'admin.tasks.store',
            'show' => 'admin.tasks.show',
            'edit' => 'admin.tasks.edit',
            'update' => 'admin.tasks.update',
            'destroy' => 'admin.tasks.destroy',
        ]);

    Route::resource('priorities', PriorityController::class)
        // ->middleware('permission:manage_priorities')
        ->names([
            'index' => 'admin.priorities.index', // <-- إضافة 'admin.' يدويًا
            'create' => 'admin.priorities.create',
            'store' => 'admin.priorities.store',
            'show' => 'admin.priorities.show',
            'edit' => 'admin.priorities.edit',
            'update' => 'admin.priorities.update',
            'destroy' => 'admin.priorities.destroy',
        ]);

    Route::resource('task-statuses', TaskStatusController::class)
        // ->middleware('permission:manage_task_statuses')
        ->names([
            'index' => 'admin.task_statuses.index', // <-- إضافة 'admin.' يدويًا، واستخدام snake_case
            'create' => 'admin.task_statuses.create',
            'store' => 'admin.task_statuses.store',
            'show' => 'admin.task_statuses.show',
            'edit' => 'admin.task_statuses.edit',
            'update' => 'admin.task_statuses.update',
            'destroy' => 'admin.task_statuses.destroy',
        ]);

    // إذا كنت تستخدم Route::resource، فقم بفصلها مثلما فعلنا مع 'laptops' و 'orders'
    // Route::get('/tasks', [TaskController::class, 'index'])->name('admin.tasks.index')->middleware('permission:view_tasks');
    // Route::get('/tasks/create', [TaskController::class, 'create'])->name('admin.tasks.create')->middleware('permission:create_tasks');
    // Route::post('/tasks', [TaskController::class, 'store'])->name('admin.tasks.store')->middleware('permission:create_tasks');
    // ... وظائف أخرى ...
});

// --- واجهة موظف الردود ---
Route::prefix('response')->middleware(['auth', 'permission:view_response_dashboard'])->group(function () {
    Route::get('/dashboard', [ResponseDashboardController::class, 'index'])->name('response.dashboard');

    // --- إدارة الطلبات لموظف الردود ---
    Route::get('/orders', [ResponseOrderController::class, 'index'])->name('response.orders.index')->middleware('permission:view_orders_for_response');
    Route::get('/orders/create', [ResponseOrderController::class, 'create'])->name('response.orders.create')->middleware('permission:create_orders_for_customers_as_response');
    Route::post('/orders', [ResponseOrderController::class, 'store'])->name('response.orders.store')->middleware('permission:create_orders_for_customers_as_response');
    Route::get('/orders/{order}/success', [ResponseOrderController::class, 'success'])->name('response.orders.success')->middleware('permission:create_orders_for_customers_as_response');
    Route::get('/orders/{order}', [ResponseOrderController::class, 'show'])->name('response.orders.show')->middleware('permission:view_orders_for_response');
    Route::post('/orders/{order}/confirm', [ResponseOrderController::class, 'confirmOrder'])->name('response.orders.confirm')->middleware('permission:update_order_status_to_confirmed_by_response');
    Route::put('/orders/{order}/assign-preparation', [ResponseOrderController::class, 'assignPreparation'])->name('response.orders.assignPreparation')->middleware('permission:assign_preparation_employee');
    // ... وظائف أخرى ...
});

// --- واجهة موظف التجهيز ---
Route::prefix('preparation')->middleware(['auth', 'permission:view_preparation_dashboard'])->group(function () {
    Route::get('/dashboard', [PreparationDashboardController::class, 'index'])->name('preparation.dashboard');
    Route::resource('orders', PreparationOrderController::class)->middleware('permission:view_orders');
    Route::post('/orders/{order}/mark-preparing', [PreparationOrderController::class, 'markAsPreparing'])->name('preparation.orders.markPreparing')->middleware('permission:update_order_status_to_preparing');
    Route::post('/orders/{order}/mark-ready', [PreparationOrderController::class, 'markAsReady'])->name('preparation.orders.markReady')->middleware('permission:update_order_status_to_ready');
});
