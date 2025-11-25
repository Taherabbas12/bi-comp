<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str; // <-- import Str

class AdminUserSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1. إنشاء دور "admin" إذا لم يكن موجودًا
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
        ], [
            'id' => Str::uuid()->toString(), // <-- تعيين UUID يدويًا
        ]);

        // 2. ربط الدور بجميع الصلاحيات *التي تم إنشاؤها بالفعل*
        // جلب جميع معرفات الصلاحيات (من كلا الجدولين، الرئيسي وصلاحيات موظف الردود)
        $allPermissionIds = \App\Models\Permission::pluck('id')->toArray(); // <-- هذا يجلب *جميع* الصلاحيات المخزنة
        // تعيين جميع الصلاحيات لهذا الدور
        $adminRole->permissions()->sync($allPermissionIds); // 'sync' يربط فقط، 'attach' يضيف فقط

        // 3. إنشاء مستخدم مشرف مرتبط بهذا الدور (بشرط ألا يكون موجودًا)
        $adminEmail = 'taher12abbas@gmail.com'; // <-- البريد الإلكتروني الذي تستخدمه
        $adminPassword = 'password'; // <-- كلمة المرور التي تستخدمها

        // تحقق من وجود المستخدم أولاً
        $existingAdminUser = User::where('email', $adminEmail)->first();

        if (! $existingAdminUser) {
            // إذا لم يكن موجودًا، قم بإنشائه
            User::create([
                'id' => Str::uuid()->toString(), // <-- تعيين UUID يدويًا
                'name' => 'Taher Abbas', // <-- الاسم الذي تريده
                'email' => $adminEmail,
                'password' => Hash::make($adminPassword),
                'role_id' => $adminRole->id, // <-- ربط المستخدم بالدور
            ]);
            $this->command->info("Admin user '$adminEmail' created successfully with all available permissions.");
        } else {
            // إذا كان موجودًا، قم بتحديث دوره فقط (اختياري)
            $existingAdminUser->update(['role_id' => $adminRole->id]);
            // قم بربط جميع الصلاحيات بدوره (لتحديث الصلاحيات إذا تم إضافة صلاحيات جديدة)
            $adminRole->permissions()->sync($allPermissionIds);
            $this->command->info("Admin user '$adminEmail' already exists. Updated role and permissions.");
        }
    }
}
// namespace Database\Seeders;

// use App\Models\Permission;
// use App\Models\Role;
// use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
// use Illuminate\Database\Seeder;
// use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Str; // <-- import Str

// class AdminUserSeeder extends Seeder
// {
//     use WithoutModelEvents;

//     public function run(): void
//     {
//         // 1. إنشاء دور "admin" إذا لم يكن موجودًا
//         $adminRole = Role::firstOrCreate([
//             'name' => 'admin',
//         ], [
//             'id' => Str::uuid()->toString(), // <-- تعيين UUID يدويًا
//         ]);

//         // 2. ربط الدور بجميع الصلاحيات
//         // جلب جميع معرفات الصلاحيات
//         $allPermissionIds = Permission::pluck('id')->toArray();
//         // تعيين جميع الصلاحيات لهذا الدور
//         $adminRole->permissions()->sync($allPermissionIds); // 'sync' يربط فقط، 'attach' يضيف فقط

//         // 3. إنشاء مستخدم مشرف مرتبط بهذا الدور
//         User::create([
//             'id' => Str::uuid()->toString(), // <-- تعيين UUID يدويًا
//             'name' => 'Taher Abbas',
//             'email' => 'taher13abbas@gmail.com', // <-- استخدم البريد الإلكتروني الذي تريده
//             'password' => Hash::make('taherzzAA12!!'), // <-- استخدم كلمة المرور التي تريدها
//             'role_id' => $adminRole->id, // <-- ربط المستخدم بالدور
//         ]);

//         $this->command->info('Admin user created successfully with all permissions.');
//     }
// }
