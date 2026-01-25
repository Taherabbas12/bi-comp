# ✅ Checklist التحديث على السيرفر

## قبل التحديث

- [ ] عمل backup لقاعدة البيانات
- [ ] عمل backup للملفات الموجودة

---

## ملفات التحديث المطلوب رفعها

### 📁 Migrations (يجب رفعها)

```
database/migrations/2025_12_05_182151_create_personal_access_tokens_table.php ✅ Fixed
database/migrations/2026_01_25_000001_add_employment_fields_to_users_table.php ✅
database/migrations/2026_01_25_000002_create_work_schedules_table.php ✅
```

### 📁 Models (يجب رفعها)

```
app/Models/WorkSchedule.php ✅ New
app/Models/User.php ✅ Updated
app/Models/Attendance.php ✅ No changes
```

### 📁 Controllers (يجب رفعها)

```
app/Http/Controllers/Admin/AttendanceAdminController.php ✅ Updated
```

### 📁 Views (يجب رفعها)

```
resources/views/admin/attendance/index.blade.php ✅ Updated
```

### 📁 Seeders (اختياري)

```
database/seeders/WorkScheduleSeeder.php ✅ New
```

### 📁 Documentation (للمرجعية فقط)

```
ATTENDANCE_SYSTEM.md ✅
SERVER_UPDATE_GUIDE.md ✅
```

---

## خطوات التحديث على السيرفر

### الخطوة 1: رفع الملفات via FTP/SFTP

1. رفع جميع الملفات المدرجة أعلاه
2. التأكد من أن الأذونات صحيحة (644 للملفات، 755 للمجلدات)

### الخطوة 2: تشغيل الـ migrations

```bash
cd /path/to/project
php artisan migrate
```

### الخطوة 3: تحديث الـ cache (اختياري لكن مُوصى به)

```bash
php artisan cache:clear
php artisan config:cache
```

### الخطوة 4: إضافة جداول العمل (اختياري)

```bash
php artisan db:seed --class=WorkScheduleSeeder
```

---

## ✨ الميزات الجديدة بعد التحديث

✅ **حقول الراتب والتوظيف**

- salary (الراتب الشهري)
- salary_currency (عملة الراتب)
- employment_type (نوع التوظيف)
- department (القسم)
- position (المسمى الوظيفي)
- hire_date (تاريخ التعيين)

✅ **جدول أوقات العمل الرسمية**

- official_check_in / official_check_out
- working_hours (ساعات العمل اليومية)
- is_day_off (تحديد العطل)

✅ **واجهة محسّنة للحضور**

- عرض أيام الحضور والغياب
- إحصائيات أسبوعية مفصلة
- عرض الراتب والقسم والمسمى الوظيفي
- أوقات العمل الرسمية لكل موظف

---

## 🔐 الأمان والأداء

✅ Migrations آمنة (تتحقق من وجود الجداول)
✅ Foreign keys محمية (cascadeOnDelete)
✅ Unique indexes (user_id, day_of_week)
✅ Queries محسّنة مع eager loading

---

## 📋 التحقق من النجاح

بعد التحديث، تحقق من:

```bash
# 1. عرض حالة الـ migrations
php artisan migrate:status

# 2. التحقق من جدول users
php artisan tinker
>>> User::first()->salary;

# 3. التحقق من جدول work_schedules
>>> WorkSchedule::count();

# 4. التحقق من الواجهة
# زيارة /admin/attendance
```

---

## 🔄 إذا حدث خطأ

### خطأ: Table already exists

**الحل**: الكود مُحدث بـ `Schema::hasTable()` - لن يحدث هذا الخطأ

### خطأ: Foreign key constraint

**الحل**: تأكد من تشغيل migrations بالترتيب الصحيح

### خطأ: Column already exists

**الحل**: تحقق من أن الـ migration لم تُشغّل قبلاً

### التراجع عن التحديث

```bash
php artisan migrate:rollback
```

---

## 📞 دعم فني

إذا واجهت أي مشكلة:

1. تحقق من `storage/logs/laravel.log`
2. شغّل `php artisan optimize:clear`
3. تأكد من أذونات المجلدات (storage, bootstrap/cache)
4. تحقق من إصدار PHP (7.4+ مطلوب)

---

## ✅ تم بنجاح!

بعد إكمال الخطوات، يجب أن يعمل كل شيء بسلاسة.

- ✅ جميع الحقول الجديدة موجودة
- ✅ جداول جديدة تم إنشاؤها
- ✅ الواجهة محسّنة
- ✅ لا توجد أخطاء في الـ logs

**التاريخ**: 2026-01-25
**الإصدار**: 1.0.0
