<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\LaptopController as AdminLaptopController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PermissionController as AdminPermissionController;
use App\Http\Controllers\Admin\PriorityController;
use App\Http\Controllers\Admin\RoleController as AdminRoleController;
use App\Http\Controllers\Admin\TaskController as AdminTaskController;
use App\Http\Controllers\Admin\TaskStatusController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Customer\LaptopController as CustomerLaptopController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Employee\EmployeeDashboardController;
use App\Http\Controllers\Employee\TaskController as EmployeeTaskController;
use App\Http\Controllers\Preparation\DashboardController as PreparationDashboardController;
use App\Http\Controllers\Preparation\OrderController as PreparationOrderController;
use App\Http\Controllers\Response\DashboardController as ResponseDashboardController;
use App\Http\Controllers\Response\OrderController as ResponseOrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Admin\AttendanceAdminController;
use App\Http\Controllers\Admin\AttendanceQrController;

/*
|--------------------------------------------------------------------------
| Login / Logout
|--------------------------------------------------------------------------
*/

Route::get('/login', fn () => view('auth.login'))
    ->name('login')
    ->middleware('guest');

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
        }
        if ($user->hasPermission('view_response_dashboard')) {
            return redirect()->intended('/response/dashboard');
        }
        if ($user->hasPermission('view_preparation_dashboard')) {
            return redirect()->intended('/preparation/dashboard');
        }
        if ($user->hasPermission('employee_dashboard')) {
            return redirect()->intended('/employee/dashboard');
        }

        return redirect('/');
    }

    return back()
        ->withErrors(['email' => 'البيانات المدخلة غير صحيحة.'])
        ->withInput($request->only('email'));
})->name('login.attempt')->middleware('guest');

Route::post('/logout', function (Request $request) {

    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
})->name('logout');

/*
|--------------------------------------------------------------------------
| Public Customer Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [CustomerLaptopController::class, 'index'])->name('home');
Route::get('/laptops', [CustomerLaptopController::class, 'index'])->name('laptops.index');
Route::get('/laptops/{barcode}', [CustomerLaptopController::class, 'show'])->name('laptops.show');

Route::post('/orders', [CustomerOrderController::class, 'store'])
    ->name('orders.store')
    ->middleware('auth');

Route::get('/orders/{order}/success', [CustomerOrderController::class, 'success'])
    ->name('orders.success')
    ->middleware('auth');

/*
|--------------------------------------------------------------------------
| Admin Panel Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {


Route::get('/attendance/calendar-data',
    [AttendanceController::class,'getCalendarData']
)->name('attendance.calendar.data');
    Route::get('/attendance/dashboard', [AttendanceController::class, 'dashboard'])
        ->name('attendance.dashboard');

    Route::post('/attendance/check-in-qr', [AttendanceController::class, 'checkInByQr'])
        ->name('attendance.checkin.qr');

    Route::post('/attendance/check-out-qr', [AttendanceController::class, 'checkOutByQr'])
        ->name('attendance.checkout.qr');

    Route::post('/attendance/handle-forgotten', [AttendanceController::class, 'handleForgottenSession'])->name('attendance.handle_forgotten');

        // صفحة عرض QR
        Route::get('/attendance/qr', [AttendanceQrController::class, 'show'])
            ->name('admin.attendance.qr');

        // API للتحقق من الوكيشن
        Route::post('/attendance/qr/verify-location', [AttendanceQrController::class, 'verifyLocation'])
            ->name('admin.attendance.qr.verify-location');

//         Route::get('/attendance/qr', function () {
//     return view('admin.attendance.qr');
// })->name('admin.attendance.qr');
});
Route::prefix('admin')
    ->middleware(['auth', 'permission:view_dashboard'])
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('admin.dashboard');

    Route::get('attendance/day/{date}', [AdminDashboardController::class, 'day'])
        ->name('admin.attendance.day');




    Route::get('/attendance', [AttendanceAdminController::class, 'index'])
        ->name('admin.attendance.index');


    Route::get('/attendance/user/{id}', [AttendanceAdminController::class, 'userAttendance'])
        ->name('admin.attendance.user');


        /*
        |--------------------------------------------------------------------------
        | Laptops (Admin)
        |--------------------------------------------------------------------------
        */

        Route::get('/laptops', [AdminLaptopController::class, 'index'])
            ->name('admin.laptops.index')
            ->middleware('permission:view_laptops');

        Route::get('/laptops/create', [AdminLaptopController::class, 'create'])
            ->name('admin.laptops.create')
            ->middleware('permission:create_laptops');

        Route::post('/laptops', [AdminLaptopController::class, 'store'])
            ->name('admin.laptops.store')
            ->middleware('permission:create_laptops');

        Route::get('/laptops/{laptop}', [AdminLaptopController::class, 'show'])
            ->name('admin.laptops.show')
            ->middleware('permission:view_laptops');

        Route::get('/laptops/{laptop}/edit', [AdminLaptopController::class, 'edit'])
            ->name('admin.laptops.edit')
            ->middleware('permission:edit_laptops');

        Route::put('/laptops/{laptop}', [AdminLaptopController::class, 'update'])
            ->name('admin.laptops.update')
            ->middleware('permission:edit_laptops');

        Route::delete('/laptops/{laptop}', [AdminLaptopController::class, 'destroy'])
            ->name('admin.laptops.destroy')
            ->middleware('permission:delete_laptops');

        /*
        |--------------------------------------------------------------------------
        | Orders (Admin)
        |--------------------------------------------------------------------------
        */

        Route::get('/orders', [AdminOrderController::class, 'index'])
            ->name('admin.orders.index')
            ->middleware('permission:view_orders');

        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])
            ->name('admin.orders.show')
            ->middleware('permission:view_orders');

        Route::put('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])
            ->name('admin.orders.updateStatus')
            ->middleware('permission:update_order_status');

        /*
        |--------------------------------------------------------------------------
        | Users (Admin)
        |--------------------------------------------------------------------------
        */

        Route::get('/users', [AdminUserController::class, 'index'])
            ->name('admin.users.index')
            ->middleware('permission:view_users');

        Route::get('/users/create', [AdminUserController::class, 'create'])
            ->name('admin.users.create')
            ->middleware('permission:create_users');

        Route::post('/users', [AdminUserController::class, 'store'])
            ->name('admin.users.store')
            ->middleware('permission:create_users');

        Route::get('/users/{user}', [AdminUserController::class, 'show'])
            ->name('admin.users.show')
            ->middleware('permission:view_users');

        Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])
            ->name('admin.users.edit')
            ->middleware('permission:edit_users');

        Route::put('/users/{user}', [AdminUserController::class, 'update'])
            ->name('admin.users.update')
            ->middleware('permission:edit_users');

        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])
            ->name('admin.users.destroy')
            ->middleware('permission:delete_users');

        /*
        |--------------------------------------------------------------------------
        | Roles & Permissions (Admin)
        |--------------------------------------------------------------------------
        */

        Route::get('/roles', [AdminRoleController::class, 'index'])
            ->name('admin.roles.index')
            ->middleware('permission:view_roles');

        Route::get('/roles/create', [AdminRoleController::class, 'create'])
            ->name('admin.roles.create')
            ->middleware('permission:create_roles');

        Route::post('/roles', [AdminRoleController::class, 'store'])
            ->name('admin.roles.store')
            ->middleware('permission:create_roles');

        Route::get('/roles/{role}', [AdminRoleController::class, 'show'])
            ->name('admin.roles.show')
            ->middleware('permission:view_roles');

        Route::get('/roles/{role}/edit', [AdminRoleController::class, 'edit'])
            ->name('admin.roles.edit')
            ->middleware('permission:edit_roles');

        Route::put('/roles/{role}', [AdminRoleController::class, 'update'])
            ->name('admin.roles.update')
            ->middleware('permission:edit_roles');

        Route::delete('/roles/{role}', [AdminRoleController::class, 'destroy'])
            ->name('admin.roles.destroy')
            ->middleware('permission:delete_roles');

        Route::get('/permissions', [AdminPermissionController::class, 'index'])
            ->name('admin.permissions.index')
            ->middleware('permission:view_permissions');

        /*
        |--------------------------------------------------------------------------
        | Task System (Admin)
        |--------------------------------------------------------------------------
        */

        Route::resource('tasks', AdminTaskController::class)
            ->middleware('permission:manage_tasks')
            ->names('admin.tasks');

        Route::resource('priorities', PriorityController::class)
            ->middleware('permission:manage_priorities')
            ->names('admin.priorities');

        Route::resource('task-statuses', TaskStatusController::class)
            ->middleware('permission:manage_task_statuses')
            ->names('admin.task_statuses');
    });

/*
|--------------------------------------------------------------------------
| Response Employee
|--------------------------------------------------------------------------
*/

Route::prefix('response')
    ->middleware(['auth', 'permission:view_orders_for_response'])
    ->group(function () {
Route::get('/laptops/filter', [\App\Http\Controllers\Response\LaptopController::class, 'filter'])
    ->name('response.laptops.filter');

        Route::get('/dashboard', [ResponseDashboardController::class, 'index'])
            ->name('response.dashboard')
            ->middleware('permission:view_response_dashboard');

        Route::get('/orders', [ResponseOrderController::class, 'index'])
            ->name('response.orders.index')
            ->middleware('permission:view_orders_for_response');

        Route::get('/orders/create', [ResponseOrderController::class, 'create'])
            ->name('response.orders.create')
            ->middleware('permission:create_orders_for_customers_as_response');

        Route::post('/orders', [ResponseOrderController::class, 'store'])
            ->name('response.orders.store')
            ->middleware('permission:create_orders_for_customers_as_response');

        Route::get('/orders/{order}', [ResponseOrderController::class, 'show'])
            ->name('response.orders.show')
            ->middleware('permission:view_orders_for_response');

        Route::post('/orders/{order}/confirm', [ResponseOrderController::class, 'confirmOrder'])
            ->name('response.orders.confirm')
            ->middleware('permission:update_order_status_to_confirmed_by_response');

        Route::put('/orders/{order}/assign-preparation', [ResponseOrderController::class, 'assignPreparation'])
            ->name('response.orders.assignPreparation')
            ->middleware('permission:assign_preparation_employee');

        // عرض قائمة المنتجات لاختيار منتج لإنشاء طلب
        // Route::get('/products', [ResponseOrderController::class, 'products'])
        //     ->name('response.orders.products');

        // صفحة إنشاء طلب لمنتج معين
        Route::get('/orders/create/{laptop}', [ResponseOrderController::class, 'create'])
            ->name('response.orders.createProduct');

        // حفظ الطلب
        Route::post('/orders/store', [ResponseOrderController::class, 'store'])
            ->name('response.orders.storeProduct');
        // ------------------- المنتجات لموظف الردود -------------------
        // ------------------- المنتجات لموظف الردود -------------------

        // Route::get('/laptops', [LaptopController::class, 'index'])->name('response.laptops.index');
        // Route::get('/laptops/filter', [LaptopController::class, 'filter'])->name('response.laptops.filter');
        Route::get('/laptops', [\App\Http\Controllers\Response\LaptopController::class, 'index'])
            ->name('response.laptops.index');
// Route::get('/response/orders/{order}', [\App\Http\Controllers\Response\OrderController::class, 'show'])
//     ->name('response.orders.show');
        Route::get('/laptops/{laptop}/create-order', [\App\Http\Controllers\Response\LaptopController::class, 'createOrder'])
            ->name('response.laptops.createOrder');

        Route::post('/laptops/{laptop}/store-order', [\App\Http\Controllers\Response\LaptopController::class, 'storeOrder'])
            ->name('response.laptops.storeOrder');
    });

/*
|--------------------------------------------------------------------------
| Preparation Employee
|--------------------------------------------------------------------------
*/

Route::prefix('preparation')
    ->middleware(['auth', 'permission:view_preparation_dashboard'])
    ->group(function () {

        Route::get('/dashboard', [PreparationDashboardController::class, 'index'])
            ->name('preparation.dashboard');



    Route::resource('orders', PreparationOrderController::class)
    ->names('preparation.orders')
    ->middleware('permission:view_orders');

        Route::post('/orders/{order}/mark-preparing', [PreparationOrderController::class, 'markAsPreparing'])
            ->name('preparation.orders.markPreparing')
            ->middleware('permission:update_order_status_to_preparing');

        Route::post('/orders/{order}/mark-ready', [PreparationOrderController::class, 'markAsReady'])
            ->name('preparation.orders.markReady')
            ->middleware('permission:update_order_status_to_ready');
    });

/*
|--------------------------------------------------------------------------
| Employee Tasks System
|--------------------------------------------------------------------------
*/

Route::prefix('employee')
    ->middleware(['auth', 'permission:employee_dashboard'])
    ->group(function () {

        Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])
            ->name('employee.dashboard');

        Route::get('/tasks', [EmployeeTaskController::class, 'index'])
            ->name('employee.tasks.index')
            ->middleware('permission:view_employee_tasks');

        Route::get('/tasks/{task}', [EmployeeTaskController::class, 'show'])
            ->name('employee.tasks.show')
            ->middleware('permission:view_employee_tasks');

        Route::put('/tasks/{task}/progress', [EmployeeTaskController::class, 'updateProgress'])
            ->name('employee.tasks.updateProgress')
            ->middleware('permission:update_employee_task_progress');
    });
