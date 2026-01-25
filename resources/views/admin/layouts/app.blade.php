<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>لوحة تحكم المشرف - متجر BI</title>
    <!-- ✅ إزالة المسافات الزائدة من روابط CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        /* =====================================================
           🌌 NeoDark Ultra Pro — Hybrid Neon XXL Mobile Edition (100% Size)
           (Same theme variables as employee-layout)
           ===================================================== */

        :root {
            --dark1: #020617;
            --dark2: #0b1120;
            --neon-green: #22c55e;
            --neon-blue: #3b82f6;
            --text-main: #e2e8f0;
            --text-muted: #94a3b8;

            --glass-bg: rgba(15,23,42,0.55);
            --glass-border: rgba(255,255,255,0.08);

            --glow-green: 0 0 14px rgba(34,197,94,.6);
            --glow-blue: 0 0 20px rgba(59,130,246,.6);

            --bottom-bg: rgba(2,6,23,0.75);
            
            /* --- 💥 تم حذف --scale-factor --- */
        }

        body {
            background: radial-gradient(circle at top left, #1e293b, var(--dark1) 60%);
            color: var(--text-main);
            font-family: "Tajawal", sans-serif;
            padding: 0;
            margin: 0;
            /* 💥 overflow-x: auto محذوف */
            /* 💥 transform: scale(...) محذوف */
            min-height: 100vh;
            width: 100vw;
            overflow-x: hidden; /* للسماح بالتمرير الأفقي */
        }

        .admin-container {
            padding: 0;
            display: flex;
            min-height: 100vh; /* 💥 حذف calc و --scale-factor */
        }

        /* ================== 侧边栏导航 (Sidebar) ================== */
        .sidebar {
            width: 260px; /* 💥 حذف calc و --scale-factor */
            background: var(--glass-bg); /* 💥 استخدام لون من theme */
            backdrop-filter: blur(10px); /* 💥 تأثير زجاجي */
            -webkit-backdrop-filter: blur(10px);
            border-right: 1px solid var(--glass-border); /* 💥 استخدام لون من theme */
            box-shadow: 4px 0 12px rgba(0, 0, 0, 0.1); /* 💥 حذف calc و --scale-factor */
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
            overflow-y: auto;
        }

        .sidebar.hidden {
            transform: translateX(-100%); /* 💥 حذف calc و --scale-factor */
        }

        .sidebar .logo {
            padding: 20px 15px; /* 💥 حذف calc و --scale-factor */
            color: white;
            font-weight: 800;
            font-size: 1.8em; /* 💥 حذف calc و --scale-factor */
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3); /* 💥 حذف calc و --scale-factor */
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px; /* 💥 حذف calc و --scale-factor */
            border-bottom: 1px solid rgba(255, 255, 255, 0.2); /* 💥 حذف calc و --scale-factor */
        }

        .sidebar .logo i {
            color: var(--neon-blue); /* 💥 استخدام لون من theme */
            font-size: 1.8em; /* 💥 حذف calc و --scale-factor */
        }

        .sidebar-menu {
            list-style: none;
            padding: 15px 0; /* 💥 حذف calc و --scale-factor */
            margin: 0;
            flex-grow: 1;
        }

        .sidebar-menu .nav-item {
            margin: 0;
        }

        .sidebar-menu .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            text-decoration: none !important;
            padding: 12px 20px !important; /* 💥 حذف calc و --scale-factor */
            border-radius: 0 12px 12px 0 !important; /* 💥 حذف calc و --scale-factor */
            font-weight: 600 !important;
            transition: all 0.3s ease !important;
            display: flex;
            align-items: center;
            gap: 12px; /* 💥 حذف calc و --scale-factor */
            margin: 0 10px 5px 10px; /* 💥 حذف calc و --scale-factor */
            border-left: 4px solid transparent;
        }

        .sidebar-menu .nav-link:hover,
        .sidebar-menu .nav-link.active {
            background: rgba(52, 152, 219, 0.2) !important; /* 💥 استخدام لون من theme */
            color: white !important;
            border-left-color: var(--neon-blue); /* 💥 استخدام لون من theme */
        }

        .sidebar-menu .nav-link i {
            font-size: 1.2em; /* 💥 حذف calc و --scale-factor */
            width: 24px; /* 💥 حذف calc و --scale-factor */
            text-align: center;
        }

        .sidebar-footer {
            padding: 15px; /* 💥 حذف calc و --scale-factor */
            border-top: 1px solid rgba(255, 255, 255, 0.2); /* 💥 حذف calc و --scale-factor */
            display: flex;
            flex-direction: column;
            gap: 10px; /* 💥 حذف calc و --scale-factor */
        }

        /* ================== 顶部栏 (Top Bar) ================== */
        .top-bar-container {
            background: var(--glass-bg); /* 💥 استخدام لون من theme */
            backdrop-filter: blur(10px); /* 💥 تأثير زجاجي */
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--glass-border); /* 💥 استخدام لون من theme */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* 💥 حذف calc و --scale-factor */
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 999;
            padding: 0 15px; /* 💥 حذف calc و --scale-factor */
            height: 60px; /* 💥 حذف calc و --scale-factor */
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .menu-toggle-btn {
            background: none;
            border: none;
            color: white;
            font-size: 1.8em; /* 💥 حذف calc و --scale-factor */
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px; /* 💥 حذف calc و --scale-factor */
            height: 40px; /* 💥 حذف calc و --scale-factor */
            border-radius: 50%;
            transition: background 0.3s ease;
        }

        .menu-toggle-btn:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .top-bar-info {
            display: flex;
            align-items: center;
            gap: 15px; /* 💥 حذف calc و --scale-factor */
        }

        .top-bar-info .info-item {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px); /* 💥 حذف calc و --scale-factor */
            border-radius: 12px; /* 💥 حذف calc و --scale-factor */
            padding: 6px 12px; /* 💥 حذف calc و --scale-factor */
            font-size: 0.85em; /* 💥 حذف calc و --scale-factor */
            border: 1px solid rgba(255, 255, 255, 0.2); /* 💥 حذف calc و --scale-factor */
            display: flex;
            align-items: center;
            gap: 6px; /* 💥 حذف calc و --scale-factor */
        }

        .top-bar-info .info-item i {
            font-size: 1em; /* 💥 حذف calc و --scale-factor */
            color: var(--neon-blue); /* 💥 استخدام لون من theme */
        }

        .top-bar .logout-btn {
            border-radius: 12px; /* 💥 حذف calc و --scale-factor */
            padding: 8px 16px; /* 💥 حذف calc و --scale-factor */
            font-size: 0.9em; /* 💥 حذف calc و --scale-factor */
            color: #e74c3c !important;
            border-color: #e74c3c !important;
            background: rgba(231, 76, 60, 0.1) !important;
        }

        .top-bar .logout-btn:hover {
            background: rgba(231, 76, 60, 0.2) !important;
        }

        /* ================== 主要内容区域 ================== */
        .content-wrapper {
            flex: 1;
            margin-top: 60px; /* 💥 حذف calc و --scale-factor */
            margin-left: 260px; /* 💥 حذف calc و --scale-factor */
            padding: 20px; /* 💥 حذف calc و --scale-factor */
            transition: margin-left 0.3s ease;
            padding-bottom: 92px; /* 💥 إضافة مساحة من الأسفل لتفادي تغطية الـ Bottom Nav */
        }

        .content-wrapper.shifted {
            margin-left: 0;
        }

        .content {
            padding-top: 20px; /* 💥 حذف calc و --scale-factor */
        }

        /* ================== 卡片样式 ================== */
        .header {
            background: var(--glass-bg); /* 💥 استخدام لون من theme */
            backdrop-filter: blur(10px); /* 💥 تأثير زجاجي */
            border-radius: 20px; /* 💥 حذف calc و --scale-factor */
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2); /* 💥 حذف calc و --scale-factor */
            padding: 30px; /* 💥 حذف calc و --scale-factor */
            margin: 20px 0 30px 0; /* 💥 حذف calc و --scale-factor */
            text-align: center;
            position: relative;
            overflow: hidden;
            border: 1px solid var(--glass-border); /* 💥 استخدام لون من theme */
        }

        .header::before {
            content: "";
            position: absolute;
            top: -50%; /* 💥 حذف calc و --scale-factor */
            left: -50%; /* 💥 حذف calc و --scale-factor */
            width: 200%; /* 💥 حذف calc و --scale-factor */
            height: 200%; /* 💥 حذف calc و --scale-factor */
            background: linear-gradient(45deg, transparent, rgba(52, 152, 219, 0.1), transparent);
            transform: rotate(30deg);
            z-index: -1;
        }

        h1 {
            color: white;
            font-weight: 800;
            font-size: 2.8em; /* 💥 حذف calc و --scale-factor */
            margin-bottom: 5px; /* 💥 حذف calc و --scale-factor */
            letter-spacing: -1px; /* 💥 حذف calc و --scale-factor */
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3); /* 💥 حذف calc و --scale-factor */
        }

        .subtitle {
            color: #ecf0f1;
            font-size: 1.3em; /* 💥 حذف calc و --scale-factor */
            font-weight: 500;
        }

        .card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px); /* 💥 تأثير زجاجي */
            border: 1px solid var(--glass-border); /* 💥 استخدام لون من theme */
            color: white;
            border-radius: 18px; /* 💥 حذف calc و --scale-factor */
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2); /* 💥 حذف calc و --scale-factor */
        }

        .card-header {
            background: rgba(255, 255, 255, 0.1);
            border-bottom: 1px solid var(--glass-border); /* 💥 استخدام لون من theme */
            border-radius: 18px 18px 0 0 !important; /* 💥 حذف calc و --scale-factor */
        }

        .btn-primary {
            background: var(--neon-blue); /* 💥 استخدام لون من theme */
            border: none;
            border-radius: 12px; /* 💥 حذف calc و --scale-factor */
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(52, 152, 219, 0.4); /* 💥 حذف calc و --scale-factor */
        }

        .btn-outline-primary {
            color: white;
            border-color: rgba(255, 255, 255, 0.5);
            border-radius: 12px; /* 💥 حذف calc و --scale-factor */
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background: white;
            color: var(--neon-blue); /* 💥 استخدام لون من theme */
        }

        .btn-danger {
            background: var(--danger); /* 💥 استخدام متغير عام */
            border: none;
            border-radius: 12px; /* 💥 حذف calc و --scale-factor */
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-danger:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }

        .form-control,
        .form-select {
            border-radius: 12px; /* 💥 حذف calc و --scale-factor */
            padding: 12px; /* 💥 حذف calc و --scale-factor */
            border: 2px solid rgba(255, 255, 255, 0.3); /* 💥 حذف calc و --scale-factor */
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--neon-blue); /* 💥 استخدام لون من theme */
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25); /* 💥 حذف calc و --scale-factor */
            background: rgba(255, 255, 255, 0.15);
            color: white;
        }

        .form-select option {
            color: #212529;
            background-color: white;
        }

        .table {
            color: white;
        }

        .table th,
        .table td {
            border-top: 1px solid rgba(255, 255, 255, 0.2); /* 💥 حذف calc و --scale-factor */
        }

        .table thead th {
            border-bottom: 2px solid rgba(255, 255, 255, 0.3); /* 💥 حذف calc و --scale-factor */
        }

        /* ================== MOBILE HIDE (Added) ================== */
        @media (max-width: 768px) { /* 💥 أو أي حجم تراه مناسب */
            .top-bar-container {
                display: none; /* 💥 إخفاء الشريط العلوي */
            }
            #menuToggleBtn {
                display: none; /* 💥 إخفاء زر فتح القائمة */
            }
            .content-wrapper {
                margin-top: 5px; /* 💥 تقليل مساحة الأعلى */
                padding-top: 5px; /* 💥 تقليل مساحة الأعلى */
            }
            /* 💥 إظهار Bottom Nav على الموبايل فقط */
            .bottom-nav {
                display: block !important; /* 💥 تم التغيير من flex إلى block */
            }
            .sidebar {
                padding-bottom: 92px; /* 💥 إضافة مساحة للـ Sidebar لتفادي تغطية الـ Bottom Nav */
            }
        }

        /* ================== BOTTOM NAV (Added & Modified for Horizontal Scroll) ================== */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 92px; /* 💥 نفس الارتفاع المستخدم في padding */
            background: var(--bottom-bg);
            backdrop-filter: blur(14px);
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            display: none; /* 💥 مخفي افتراضيًا */
            /* 💥 تم حذف justify-content */
            z-index: 2000; /* 💥 أعلى من الـ sidebar */
            padding: 0 10px; /* 💥 مسافة داخلية */
            /* 💥 إضافة خصائص جديدة لتمكين التمرير الأفقي */
            overflow-x: auto; /* 💥 تمكين التمرير الأفقي */
            -ms-overflow-style: auto;  /* IE and Edge */
            scrollbar-width: auto;  /* Firefox */
            /* 💥 إزالة التفاف العناصر */
            white-space: nowrap; /* 💥 جعل العناصر في خط واحد */
        }

        .bottom-nav-item {
            display: inline-flex; /* 💥 تغيير إلى inline-flex */
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 10px;
            transition: all 0.2s ease;
            gap: 4px; /* 💥 فراغ بين الأيقونة والنص */
            flex: 0 0 auto; /* 💥 لا يمتد أو ين co */
            min-width: fit-content; /* 💥 الحد الأدنى من العرض */
            margin: 0 5px; /* 💥 مسافة بين العناصر */
        }

        .bottom-nav-item:hover,
        .bottom-nav-item.active {
            background: rgba(52, 152, 219, 0.2); /* 💥 لون الخلفية عند التحوم أو النشاط */
            color: white; /* 💥 لون النص عند التحوم أو النشاط */
        }

        .bottom-nav-item i {
            font-size: 1.4rem; /* 💥 حجم الأيقونة */
        }

        .bottom-nav-item span {
            font-size: 0.75rem; /* 💥 حجم النص */
        }

        /* ================== 响应式设计：移动设备 (Max 768px) ================== */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%); /* 💥 حذف calc و --scale-factor */
                width: 260px; /* 💥 حذف calc و --scale-factor */
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .content-wrapper {
                margin-left: 0;
                transition: margin-left 0.3s ease;
            }

            .content-wrapper.shifted {
                margin-left: 0;
            }

            .content-wrapper.dimmed::before {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 998;
                display: block;
            }
        }
    </style>
</head>

<body>
    <div class="admin-container">
        <!-- 侧边栏 -->
        <div class="sidebar" id="sidebar">
            <a href="{{ route('admin.dashboard') }}" class="logo">
                <i class="bi bi-speedometer2"></i> متجر BI
            </a>
            <ul class="sidebar-menu">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                        href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2"></i> <span>لوحة التحكم</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}"
                        href="{{ route('admin.attendance.index') }}">
                        <i class="bi bi-calendar-check"></i>
                        <span>إدارة الحضور</span>
                    </a>
                </li>
                <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('admin.attendance.qr') ? 'active' : '' }}"
        href="{{ route('admin.attendance.qr') }}">
        <i class="bi bi-qr-code"></i>
        <span>رمز حضور الموظفين</span>
    </a>
</li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('work-schedule-settings.edit') ? 'active' : '' }}"
                        href="{{ route('work-schedule-settings.edit') }}">
                        <i class="bi bi-clock-history"></i>
                        <span>⚙️ إعدادات أوقات العمل</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.laptops.*') ? 'active' : '' }}"
                        href="{{ route('admin.laptops.index') }}">
                        <i class="bi bi-laptop"></i> <span>إدارة الأجهزة</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"
                        href="{{ route('admin.orders.index') }}">
                        <i class="bi bi-cart-check"></i> <span>إدارة الطلبات</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                        href="{{ route('admin.users.index') }}">
                        <i class="bi bi-people"></i> <span>إدارة المستخدمين</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}"
                        href="{{ route('admin.roles.index') }}">
                        <i class="bi bi-person-badge"></i> <span>إدارة الأدوار</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}"
                        href="{{ route('admin.permissions.index') }}">
                        <i class="bi bi-shield-lock"></i> <span>إدارة الصلاحيات</span>
                    </a>
                </li>
                <!-- إضافة روابط المهام والأولويات والحالات -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.priorities.*') ? 'active' : '' }}"
                        href="{{ route('admin.priorities.index') }}">
                        <i class="bi bi-exclamation-triangle"></i> <span>إدارة الأولويات</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.task_statuses.*') ? 'active' : '' }}"
                        href="{{ route('admin.task_statuses.index') }}">
                        <i class="bi bi-clipboard-check"></i> <span>إدارة الحالات</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.tasks.*') ? 'active' : '' }}"
                        href="{{ route('admin.tasks.index') }}">
                        <i class="bi bi-list-check"></i> <span>إدارة المهام</span>
                    </a>
                </li>
            </ul>
            <div class="sidebar-footer">
                <a href="{{ route('logout') }}" class="btn btn-danger w-100"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-right"></i> <span>تسجيل الخروج</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>

        <!-- محتوى الصفحة -->
        <div class="content-wrapper" id="contentWrapper">
            <!-- الشريط العلوي -->
            <div class="top-bar-container">
                <button class="menu-toggle-btn" id="menuToggleBtn">
                    <i class="bi bi-list"></i>
                </button>
                <div class="top-bar-info">
                    <!-- يمكنك إضافة معلومات إضافية هنا -->
                </div>
                <a href="{{ route('logout') }}" class="btn btn-danger logout-btn d-none d-md-inline-block">
                    <i class="bi bi-box-arrow-right"></i> تسجيل الخروج
                </a>
            </div>

            <div class="content">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bottom Navigation Bar -->
    <div class="bottom-nav">
        <a href="{{ route('admin.dashboard') }}" class="bottom-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            <span>الرئيسية</span>
        </a>
        <a href="{{ route('admin.attendance.index') }}" class="bottom-nav-item {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-check"></i>
            <span>الحضور</span>
        </a>
        <a href="{{ route('admin.laptops.index') }}" class="bottom-nav-item {{ request()->routeIs('admin.laptops.*') ? 'active' : '' }}">
            <i class="bi bi-laptop"></i>
            <span>الأجهزة</span>
        </a>
        <a href="{{ route('admin.orders.index') }}" class="bottom-nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <i class="bi bi-cart-check"></i>
            <span>الطلبات</span>
        </a>
        <a href="{{ route('admin.users.index') }}" class="bottom-nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i>
            <span>المستخدمين</span>
        </a>
        <!-- 💥 مثال على عنصر إضافي -->
        <a href="{{ route('admin.roles.index') }}" class="bottom-nav-item {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
            <i class="bi bi-person-badge"></i>
            <span>الأدوار</span>
        </a>
        <!-- 💥 مثال على عنصر إضافي -->
        <a href="{{ route('admin.tasks.index') }}" class="bottom-nav-item {{ request()->routeIs('admin.tasks.*') ? 'active' : '' }}">
            <i class="bi bi-list-check"></i>
            <span>المهام</span>
        </a>
        <!-- 💥 مثال على عنصر إضافي -->
        <a href="{{ route('admin.permissions.index') }}" class="bottom-nav-item {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
            <i class="bi bi-shield-lock"></i>
            <span>الصلاحيات</span>
        </a>
        <!-- 💥 مثال على عنصر إضافي -->
        <a href="{{ route('admin.priorities.index') }}" class="bottom-nav-item {{ request()->routeIs('admin.priorities.*') ? 'active' : '' }}">
            <i class="bi bi-exclamation-triangle"></i>
            <span>الأولويات</span>
        </a>
        <!-- 💥 مثال على عنصر إضافي -->
        <a href="{{ route('admin.task_statuses.index') }}" class="bottom-nav-item {{ request()->routeIs('admin.task_statuses.*') ? 'active' : '' }}">
            <i class="bi bi-clipboard-check"></i>
            <span>الحالات</span>
        </a>
    </div>

    <!-- Bootstrap JS -->
    <!-- ✅ إزالة المسافات الزائدة من رابط JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const contentWrapper = document.getElementById('contentWrapper');
            const menuToggleBtn = document.getElementById('menuToggleBtn');

            menuToggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('show');
                contentWrapper.classList.toggle('dimmed');
            });

            document.querySelectorAll('.sidebar-menu .nav-link').forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        sidebar.classList.remove('show');
                        contentWrapper.classList.remove('dimmed');
                    }
                });
            });
        });
    </script>
    @yield('scripts')
</body>

</html>