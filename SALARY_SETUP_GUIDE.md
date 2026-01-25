# 🔧 دليل إصلاح بيانات الراتب والتوظيف

## ✅ ما تم التحقق منه

البيانات موجودة في:

- ✅ User Model - الحقول موجودة في `fillable` و `casts`
- ✅ Controller - يمرر البيانات إلى الـ View
- ✅ View - يعرض البيانات بشكل صحيح

---

## 🔍 المشكلة المحتملة

الحقول الجديدة موجودة في الـ migrations لكن قد لم تُضف إلى قاعدة البيانات بعد.

---

## 🚀 الحل الأول: تشغيل الـ Migrations (الطريقة الموصى به)

### خطوة 1: تشغيل الـ Migrations

```bash
php artisan migrate
```

### خطوة 2: تشغيل الـ Seeder

```bash
php artisan db:seed --class=WorkScheduleSeeder
```

---

## 📝 الحل الثاني: إضافة البيانات يدويًا (SQL)

إذا كنت تريد إضافة الحقول يدويًا بدون migrations:

### خطوة 1: إضافة الحقول إلى جدول users

```sql
ALTER TABLE users ADD COLUMN salary DECIMAL(12, 2) NULL AFTER notes;
ALTER TABLE users ADD COLUMN salary_currency VARCHAR(10) DEFAULT 'IQD' AFTER salary;
ALTER TABLE users ADD COLUMN employment_type ENUM('full-time', 'part-time', 'contract', 'temporary') NULL AFTER salary_currency;
ALTER TABLE users ADD COLUMN department VARCHAR(255) NULL AFTER employment_type;
ALTER TABLE users ADD COLUMN position VARCHAR(255) NULL AFTER department;
ALTER TABLE users ADD COLUMN hire_date DATE NULL AFTER position;
```

### خطوة 2: إنشاء جدول work_schedules

```sql
CREATE TABLE work_schedules (
    id CHAR(36) PRIMARY KEY,
    user_id CHAR(36) NOT NULL,
    day_of_week INT NOT NULL COMMENT '1=Mon, 2=Tue, ..., 7=Sun',
    official_check_in TIME NOT NULL COMMENT 'وقت الدخول الرسمي',
    official_check_out TIME NOT NULL COMMENT 'وقت الخروج الرسمي',
    working_hours DECIMAL(4, 2) DEFAULT 8 COMMENT 'عدد ساعات العمل اليومية',
    is_day_off BOOLEAN DEFAULT 0 COMMENT 'هل هذا اليوم عطلة؟',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_day (user_id, day_of_week)
);
```

### خطوة 3: إضافة بيانات افتراضية للموظفين الموجودين

```sql
-- إضافة جدول عمل افتراضي لكل موظف
-- الاثنين إلى الجمعة من 8:00 إلى 17:00
-- السبت والأحد عطلة

INSERT INTO work_schedules (id, user_id, day_of_week, official_check_in, official_check_out, working_hours, is_day_off)
SELECT
    UUID(),
    u.id,
    1,
    '08:00',
    '17:00',
    8,
    0
FROM users u
WHERE NOT EXISTS (
    SELECT 1 FROM work_schedules ws WHERE ws.user_id = u.id
);

-- كرر لكل يوم من 1 إلى 7
-- استخدم is_day_off = 1 للسبت والأحد
```

---

## 👤 مثال: إضافة بيانات للموظف

```sql
-- تحديث بيانات موظف معين
UPDATE users SET
    salary = 1500000,
    salary_currency = 'IQD',
    employment_type = 'full-time',
    department = 'تطوير التطبيقات',
    position = 'مهندس برمجيات',
    hire_date = '2023-01-15'
WHERE id = 'USER_ID_HERE';
```

---

## 🧪 اختبار البيانات

### 1. تحقق من أن الحقول موجودة:

```bash
php artisan tinker
>>> Schema::getColumnListing('users')
```

يجب أن تشاهد:

```
[..., "salary", "salary_currency", "employment_type", "department", "position", "hire_date", ...]
```

### 2. تحقق من بيانات موظف:

```bash
php artisan tinker
>>> User::first()->toArray()
```

يجب أن تشاهد الحقول الجديدة مع قيمهم.

### 3. زر موقعك:

قم بزيارة `/admin/attendance` واختر موظف
يجب أن تشاهد:

- ✅ المسمى الوظيفي
- ✅ القسم
- ✅ نوع التوظيف
- ✅ الراتب
- ✅ تاريخ التعيين

---

## 📊 ملخص الحقول

### في جدول users:

```
salary              (DECIMAL 12,2)    - الراتب الشهري
salary_currency     (VARCHAR 10)      - عملة الراتب (IQD افتراضياً)
employment_type     (ENUM)            - نوع التوظيف
department          (VARCHAR 255)     - القسم
position            (VARCHAR 255)     - المسمى الوظيفي
hire_date           (DATE)            - تاريخ التعيين
```

### في جدول work_schedules:

```
id                  (UUID)            - معرّف الصف
user_id             (UUID)            - معرّف الموظف
day_of_week         (INT 1-7)         - يوم الأسبوع
official_check_in   (TIME)            - وقت الدخول الرسمي
official_check_out  (TIME)            - وقت الخروج الرسمي
working_hours       (DECIMAL 4,2)     - ساعات العمل اليومية
is_day_off          (BOOLEAN)         - هل اليوم عطلة
```

---

## 🔗 رابط الاختبار السريع

```
GET /admin/test-attendance/user-data     - اختبار بيانات المستخدم
GET /admin/test-attendance/database      - اختبار schema قاعدة البيانات
```

---

## ⚠️ الأخطاء الشائعة

### خطأ 1: الحقول لا تظهر

**الحل**: تأكد من تشغيل الـ migrations أو إضافة الحقول يدويًا

### خطأ 2: خطأ في Foreign Key

**الحل**: تأكد من أن user_id موجود في جدول users

### خطأ 3: البيانات فارغة

**الحل**:

1. تأكد من إضافة بيانات للموظفين
2. أضف جداول عمل للموظفين
3. امسح الـ cache: `php artisan cache:clear`

---

**تم التحديث**: 2026-01-25
