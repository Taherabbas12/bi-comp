# 🔧 تعليمات الإصلاح والتحديث على السيرفر

## 🚨 المشكلة الحالية

```
SQLSTATE[42S01]: Base table or view already exists: 1050
Table 'personal_access_tokens' already exists
```

### السبب

جدول `personal_access_tokens` موجود بالفعل في قاعدة البيانات، والـ migration تحاول إنشاؤه من جديد.

### الحل

الملف `2025_12_05_182151_create_personal_access_tokens_table.php` تم إصلاحه ليتحقق من وجود الجدول قبل محاولة إنشاؤه.

---

## 📝 التعديل الذي تم إجراؤه

### قبل (يسبب خطأ):

```php
public function up(): void
{
    Schema::create('personal_access_tokens', function (Blueprint $table) {
        // ...
    });
}
```

### بعد (آمن وسليم):

```php
public function up(): void
{
    if (!Schema::hasTable('personal_access_tokens')) {
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            // ...
        });
    }
}
```

---

## 🎯 خطوات التحديث على السيرفر

### الخطوة 1: رفع الملفات الجديدة والمعدلة

**الملفات المطلوب رفعها:**

1. **الـ Migrations:**
    - `database/migrations/2025_12_05_182151_create_personal_access_tokens_table.php` ✅ **معدّل - مهم جداً**
    - `database/migrations/2026_01_25_000001_add_employment_fields_to_users_table.php` ✅ **جديد**
    - `database/migrations/2026_01_25_000002_create_work_schedules_table.php` ✅ **جديد**

2. **الـ Models:**
    - `app/Models/User.php` ✅ **معدّل**
    - `app/Models/WorkSchedule.php` ✅ **جديد**

3. **الـ Controllers:**
    - `app/Http/Controllers/Admin/AttendanceAdminController.php` ✅ **معدّل**

4. **الـ Views:**
    - `resources/views/admin/attendance/index.blade.php` ✅ **معدّل**

5. **الـ Seeders:**
    - `database/seeders/WorkScheduleSeeder.php` ✅ **جديد**

### الخطوة 2: تشغيل الـ Migrations

```bash
cd /path/to/project
php artisan migrate
```

**النتيجة المتوقعة:**

```
Running migrations.

  2026_01_25_000001_add_employment_fields_to_users_table .......... 5.50ms DONE
  2026_01_25_000002_create_work_schedules_table .................. 3.20ms DONE
```

### الخطوة 3: تشغيل الـ Seeder (اختياري)

```bash
php artisan db:seed --class=WorkScheduleSeeder
```

### الخطوة 4: تحديث الـ Cache

```bash
php artisan cache:clear
php artisan config:cache
```

---

## ✅ التحقق من نجاح التحديث

### تحقق من أن الـ migrations نجحت:

```bash
php artisan migrate:status
```

**يجب أن ترى هذه الـ migrations مكتملة:**

- ✅ 2026_01_25_000001_add_employment_fields_to_users_table
- ✅ 2026_01_25_000002_create_work_schedules_table

### تحقق من الحقول الجديدة:

```bash
php artisan tinker
>>> Schema::getColumnListing('users')
>>> Schema::getColumnListing('work_schedules')
```

### تحقق من الواجهة:

زيارة `/admin/attendance` - يجب أن تشاهد:

- ✅ إحصائيات الحضور والغياب
- ✅ معلومات الراتب والتوظيف
- ✅ أوقات العمل الرسمية
- ✅ الإحصائيات الأسبوعية

---

## 🛡️ احتياطات الأمان

### قبل التحديث:

- [ ] عمل backup لقاعدة البيانات

    ```bash
    mysqldump -u username -p database_name > backup.sql
    ```

- [ ] عمل backup للملفات
    ```bash
    tar -czf backup.tar.gz /path/to/project
    ```

### بعد التحديث:

- [ ] التحقق من `storage/logs/laravel.log` للأخطاء
- [ ] التحقق من أن الموقع يعمل بشكل صحيح
- [ ] اختبار وظيفة الحضور والرواتب

---

## 🆘 استكشاف الأخطاء

### إذا فشلت migration معينة:

```bash
# عرض حالة التفصيلية
php artisan migrate:status

# التراجع عن آخر batch
php artisan migrate:rollback

# التراجع عن migration محددة
php artisan migrate:rollback --target=2026_01_25_000001
```

### إذا ظهر خطأ "Table already exists":

**تم الإصلاح بالفعل في الملف المُحدث - لن يحدث هذا الخطأ**

### إذا حدث خطأ في الـ Foreign Key:

```bash
# تحقق من أن جدول users موجود
php artisan tinker
>>> Schema::hasTable('users')
>>> Schema::hasTable('work_schedules')
```

### إذا لم تظهر البيانات الجديدة:

```bash
# امسح الـ cache
php artisan cache:clear
php artisan config:cache
php artisan view:cache
```

---

## 📋 ملخص التغييرات

### جدول users - حقول جديدة:

| الحقل           | النوع         | الوصف          |
| --------------- | ------------- | -------------- |
| salary          | decimal(12,2) | الراتب الشهري  |
| salary_currency | string        | عملة الراتب    |
| employment_type | enum          | نوع التوظيف    |
| department      | string        | القسم/الإدارة  |
| position        | string        | المسمى الوظيفي |
| hire_date       | date          | تاريخ التعيين  |

### جدول جديد: work_schedules

| الحقل              | النوع        | الوصف               |
| ------------------ | ------------ | ------------------- |
| id                 | UUID         | معرّف الصف          |
| user_id            | UUID         | معرّف الموظف        |
| day_of_week        | integer      | يوم الأسبوع (1-7)   |
| official_check_in  | time         | وقت الدخول الرسمي   |
| official_check_out | time         | وقت الخروج الرسمي   |
| working_hours      | decimal(4,2) | ساعات العمل اليومية |
| is_day_off         | boolean      | هل اليوم عطلة       |

---

## 🎉 النتيجة النهائية

بعد تطبيق هذه الخطوات، سيكون لديك:

✅ نظام إدارة حضور متقدم
✅ إحصائيات أيام الحضور والغياب
✅ إدارة الرواتب والتوظيف
✅ أوقات عمل رسمية قابلة للتخصيص
✅ واجهة مستخدم محسّنة وآمنة

---

**آخر تحديث**: 2026-01-25
**الإصدار**: 1.0.0
**حالة التحديث**: جاهز للتطبيق على السيرفر
