<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم المشرف - متجر BI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #34495e;
            --accent: #3498db;
            --light: #ecf0f1;
            --dark: #212529;
            --success: #2ecc71;
            --warning: #f39c12;
            --danger: #e74c3c;
        }

        .laptop-card:hover {
            transform: scale(1.02);
            transition: 0.3s ease-in-out;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.2);
        }

        body {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: white;
            padding: 0;
            margin: 0;
            overflow-x: hidden;
        }

        .admin-container {
            padding: 0;
            display: flex;
            min-height: 100vh;
        }

        /* ================== 侧边栏导航 (Sidebar) ================== */
        .sidebar {
            width: 260px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-right: 1px solid rgba(0, 0, 0, 0.1);
            box-shadow: 4px 0 12px rgba(0, 0, 0, 0.1);
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
            transform: translateX(-100%);
        }

        .sidebar .logo {
            padding: 20px 15px;
            color: white;
            font-weight: 800;
            font-size: 1.8em;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .sidebar .logo i {
            color: var(--accent);
            font-size: 1.8em;
        }

        .sidebar-menu {
            list-style: none;
            padding: 15px 0;
            margin: 0;
            flex-grow: 1;
        }

        .sidebar-menu .nav-item {
            margin: 0;
        }

        .sidebar-menu .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            text-decoration: none !important;
            padding: 12px 20px !important;
            border-radius: 0 12px 12px 0 !important;
            font-weight: 600 !important;
            transition: all 0.3s ease !important;
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 0 10px 5px 10px;
            border-left: 4px solid transparent;
        }

        .sidebar-menu .nav-link:hover,
        .sidebar-menu .nav-link.active {
            background: rgba(52, 152, 219, 0.2) !important;
            color: white !important;
            border-left-color: var(--accent);
        }

        .sidebar-menu .nav-link i {
            font-size: 1.2em;
            width: 24px;
            text-align: center;
        }

        .sidebar-footer {
            padding: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        /* ================== 顶部栏 (Top Bar) ================== */
        .top-bar-container {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 999;
            padding: 0 15px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .menu-toggle-btn {
            background: none;
            border: none;
            color: white;
            font-size: 1.8em;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            transition: background 0.3s ease;
        }

        .menu-toggle-btn:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .top-bar-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .top-bar-info .info-item {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            border-radius: 12px;
            padding: 6px 12px;
            font-size: 0.85em;
            border: 1px solid rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .top-bar-info .info-item i {
            font-size: 1em;
            color: var(--accent);
        }

        .top-bar .logout-btn {
            border-radius: 12px;
            padding: 8px 16px;
            font-size: 0.9em;
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
            margin-top: 60px;
            margin-left: 260px;
            padding: 20px;
            transition: margin-left 0.3s ease;
        }

        .content-wrapper.shifted {
            margin-left: 0;
        }

        .content {
            padding-top: 20px;
        }

        /* ================== 卡片样式 ================== */
        .header {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 30px;
            margin: 20px 0 30px 0;
            text-align: center;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .header::before {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(52, 152, 219, 0.1), transparent);
            transform: rotate(30deg);
            z-index: -1;
        }

        h1 {
            color: white;
            font-weight: 800;
            font-size: 2.8em;
            margin-bottom: 5px;
            letter-spacing: -1px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .subtitle {
            color: #ecf0f1;
            font-size: 1.3em;
            font-weight: 500;
        }

        .card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: white;
            border-radius: 18px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
        }

        .card-header {
            background: rgba(255, 255, 255, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 18px 18px 0 0 !important;
        }

        .btn-primary {
            background: var(--accent);
            border: none;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(52, 152, 219, 0.4);
        }

        .btn-outline-primary {
            color: white;
            border-color: rgba(255, 255, 255, 0.5);
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background: white;
            color: var(--accent);
        }

        .btn-danger {
            background: var(--danger);
            border: none;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-danger:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            padding: 12px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
            background: rgba(255, 255, 255, 0.15);
            color: white;
        }

        /* --- إضافة هذه القاعدة لحل مشكلة لون النص في القائمة المنسدلة --- */
        .form-select option {
            color: #212529;
            /* لون النص للخيارات داخل القائمة المنسدلة */
            background-color: white;
            /* لون خلفية الخيارات (اختياري، عادةً ما يكون افتراضيًا) */
        }

        /* --- النهاية --- */

        .table {
            color: white;
        }

        .table th,
        .table td {
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .table thead th {
            border-bottom: 2px solid rgba(255, 255, 255, 0.3);
        }

        /* ================== 响应式设计：移动设备 (Max 768px) ================== */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 260px;
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
                {{-- <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.task_statuses.*') ? 'active' : '' }}">
                        href="{{ route('admin.task_statuses.index') }}"> <!-- تأكد من استخدام snake_case -->
                        <i class="bi bi-clipboard-check"></i> <span>إدارة الحالات</span>
                    </a>
                </li> --}}
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

    <!-- Bootstrap JS -->
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
</body>

</html>
