# 🚀 تعليمات التحديث على السيرفر

## الخطوة 1: رفع الملفات الجديدة

رفع الملفات التالية إلى السيرفر:

### Migrations

```
database/migrations/2026_01_25_000001_add_employment_fields_to_users_table.php
database/migrations/2026_01_25_000002_create_work_schedules_table.php
```

### Models

```
app/Models/WorkSchedule.php
```

### Controllers

```
app/Http/Controllers/Admin/AttendanceAdminController.php
```

### Views

```
resources/views/admin/attendance/index.blade.php
```

### Seeders

```
database/seeders/WorkScheduleSeeder.php
```

### Fixed Migrations

```
database/migrations/2025_12_05_182151_create_personal_access_tokens_table.php
```

---

## الخطوة 2: تحديث Model User

تحديث `app/Models/User.php` بإضافة الحقول الجديدة والعلاقات

---

## الخطوة 3: تشغيل الـ Migrations على السيرفر

### تشغيل الـ migrations الجديدة فقط:

```bash
php artisan migrate
```

هذا سيشغل فقط الـ migrations التي لم تُشغّل بعد (المسجلة في جدول `migrations`).

---

## الخطوة 4: تشغيل الـ Seeder (اختياري)

لإضافة جداول العمل الافتراضية لكل الموظفين:

```bash
php artisan db:seed --class=WorkScheduleSeeder
```

---

## ✅ ملخص التغييرات

### جدول users - حقول جديدة:

- `salary` - الراتب الشهري
- `salary_currency` - عملة الراتب (IQD افتراضياً)
- `employment_type` - نوع التوظيف
- `department` - القسم
- `position` - المسمى الوظيفي
- `hire_date` - تاريخ التعيين

### جدول جديد: work_schedules

- `user_id` - معرّف الموظف
- `day_of_week` - يوم الأسبوع (1-7)
- `official_check_in` - وقت الدخول الرسمي
- `official_check_out` - وقت الخروج الرسمي
- `working_hours` - ساعات العمل اليومية
- `is_day_off` - هل اليوم عطلة

---

## 🔍 التحقق من التحديث

بعد تشغيل الـ migrations، تحقق من:

1. **الحقول الجديدة في جدول users:**

```sql
DESC users;
```

2. **جدول work_schedules:**

```sql
DESC work_schedules;
```

3. **جدول migrations:**

```sql
SELECT * FROM migrations WHERE batch = (SELECT MAX(batch) FROM migrations);
```

---

## ⚠️ ملاحظات مهمة

1. **لا تشغّل migrations قديمة**: الكود آمن ويتحقق من وجود الجداول قبل الإنشاء
2. **الـ Seeder اختياري**: يمكنك إضافة جداول العمل يدويًا إذا أردت
3. **التراجع آمن**: يمكن التراجع عن أي migration بـ `php artisan migrate:rollback`

---

## 🛠️ استكشاف الأخطاء

إذا حدث خطأ:

```bash
# عرض حالة الـ migrations
php artisan migrate:status

# تشغيل migration معينة
php artisan migrate --path=database/migrations/2026_01_25_000001_add_employment_fields_to_users_table.php

# التراجع عن آخر batch
php artisan migrate:rollback
```

---

التاريخ: 2026-01-25
