#!/bin/bash

# 🚀 سكريبت سريع لتشغيل نظام الحضور والراتب
# Quick Setup Script for Attendance & Salary System

echo "╔═══════════════════════════════════════════════════════════════╗"
echo "║  نظام الحضور والراتب - سكريبت التشغيل                       ║"
echo "║  Attendance & Salary System - Setup Script                   ║"
echo "╚═══════════════════════════════════════════════════════════════╝"
echo ""

# ألوان للإخراج
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# التحقق من وجود Laravel
if [ ! -f "artisan" ]; then
    echo -e "${RED}✗ خطأ: لم يتم العثور على Laravel (artisan غير موجود)${NC}"
    echo -e "${RED}✗ Error: Laravel not found (artisan file missing)${NC}"
    exit 1
fi

echo -e "${GREEN}✓ تم التحقق من Laravel${NC}"
echo ""

# الخطوة 1: التحقق من الملفات
echo -e "${BLUE}[1/4] التحقق من وجود جميع الملفات...${NC}"
echo -e "${BLUE}[1/4] Checking for required files...${NC}"
echo ""

FILES_TO_CHECK=(
    "database/migrations/2026_01_25_000001_add_employment_fields_to_users_table.php"
    "database/migrations/2026_01_25_000002_create_work_schedules_table.php"
    "app/Models/WorkSchedule.php"
    "database/seeders/WorkScheduleSeeder.php"
)

ALL_FILES_EXIST=true
for file in "${FILES_TO_CHECK[@]}"; do
    if [ -f "$file" ]; then
        echo -e "${GREEN}✓${NC} $file"
    else
        echo -e "${RED}✗${NC} $file"
        ALL_FILES_EXIST=false
    fi
done

echo ""

if [ "$ALL_FILES_EXIST" = false ]; then
    echo -e "${RED}✗ بعض الملفات مفقودة. الرجاء التحقق من Git pull${NC}"
    echo -e "${RED}✗ Some files are missing. Please check git pull${NC}"
    exit 1
fi

echo -e "${GREEN}✓ جميع الملفات موجودة${NC}"
echo -e "${GREEN}✓ All files found${NC}"
echo ""

# الخطوة 2: تشغيل Migrations
echo -e "${BLUE}[2/4] تشغيل Migrations...${NC}"
echo -e "${BLUE}[2/4] Running Migrations...${NC}"
echo ""

if php artisan migrate; then
    echo ""
    echo -e "${GREEN}✓ تم تشغيل Migrations بنجاح${NC}"
    echo -e "${GREEN}✓ Migrations completed successfully${NC}"
else
    echo ""
    echo -e "${RED}✗ فشل تشغيل Migrations${NC}"
    echo -e "${RED}✗ Migrations failed${NC}"
    echo ""
    echo -e "${YELLOW}تلميح: إذا كان الخطأ بشأن جدول موجود، اقرأ SALARY_SETUP_GUIDE.md${NC}"
    echo -e "${YELLOW}Tip: If error is about existing table, read SALARY_SETUP_GUIDE.md${NC}"
    exit 1
fi

echo ""

# الخطوة 3: تشغيل Seeder (اختياري)
echo -e "${BLUE}[3/4] هل تريد إضافة جداول عمل افتراضية؟${NC}"
echo -e "${BLUE}[3/4] Do you want to add default work schedules?${NC}"
echo -e "${YELLOW}(الأحد-الخميس: 08:00-17:00, الجمعة-السبت: عطلة)${NC}"
echo -e "${YELLOW}(Sunday-Thursday: 08:00-17:00, Friday-Saturday: Off)${NC}"
echo ""
read -p "نعم/أي (y/n): " -n 1 -r
echo ""

if [[ $REPLY =~ ^[Yy]$ ]]; then
    if php artisan db:seed --class=WorkScheduleSeeder; then
        echo -e "${GREEN}✓ تم إضافة جداول العمل بنجاح${NC}"
        echo -e "${GREEN}✓ Work schedules added successfully${NC}"
    else
        echo -e "${RED}✗ فشل إضافة جداول العمل${NC}"
        echo -e "${RED}✗ Failed to add work schedules${NC}"
        exit 1
    fi
else
    echo -e "${YELLOW}⊘ تم تخطي إضافة جداول العمل${NC}"
    echo -e "${YELLOW}⊘ Skipped adding work schedules${NC}"
fi

echo ""

# الخطوة 4: مسح الـ Cache
echo -e "${BLUE}[4/4] مسح الـ Cache...${NC}"
echo -e "${BLUE}[4/4] Clearing cache...${NC}"
echo ""

php artisan cache:clear
php artisan config:cache

echo ""
echo -e "${GREEN}✓ تم مسح الـ Cache${NC}"
echo -e "${GREEN}✓ Cache cleared${NC}"
echo ""

# الإنهاء الناجح
echo "╔═══════════════════════════════════════════════════════════════╗"
echo -e "${GREEN}║  ✓ تم إعداد النظام بنجاح!                              ║${NC}"
echo -e "${GREEN}║  ✓ System setup completed successfully!               ║${NC}"
echo "╚═══════════════════════════════════════════════════════════════╝"
echo ""
echo -e "${YELLOW}الخطوات التالية:${NC}"
echo -e "${YELLOW}Next steps:${NC}"
echo "  1. زر الموقع / Visit the website:"
echo "     http://localhost:8000/admin/attendance"
echo ""
echo "  2. اختر موظف وتحقق من البيانات / Select an employee and verify data:"
echo "     - الراتب والتوظيف / Salary and employment"
echo "     - أوقات العمل الرسمية / Official work times"
echo "     - الإحصائيات / Statistics"
echo ""
echo "  3. للمساعدة / For help:"
echo "     - اقرأ: README_AR.md (للعربية)"
echo "     - اقرأ: QUICK_FIX.md (للخطوات السريعة)"
echo ""
