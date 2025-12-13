@php
    // صلاحيات موظف النظام
    $employeePermissions = [
        'employee_dashboard', 'view_employee_tasks', 'employee_tasks',
        'update_employee_task_progress', 'view_tasks', 'edit_tasks',
        'manage_tasks', 'manage_priorities', 'create_users',
        'view_users', 'delete_users', 'edit_roles', 'view_roles',
        'delete_roles',
    ];

    // صلاحيات موظف الردود
    $responsePermissions = [
        'view_response_dashboard', 'view_orders_for_response',
        'update_order_status_to_confirmed_by_response',
        'create_orders_for_customers_as_response', 'update_order_status',
        'update_order_status_to_preparing', 'update_order_status_to_ready',
        'update_order_status_to_confirmed', 'view_orders', 'delete_laptops',
    ];

    if (!function_exists('hasAnySidebarPermission')) {
        function hasAnySidebarPermission(array $permissions): bool {
            $user = auth()->user();
            if (!$user) return false;
            foreach ($permissions as $perm) if ($user->hasPermission($perm)) return true;
            return false;
        }
    }
@endphp

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'لوحة الموظف')</title>

    <!-- Bootstrap RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* =====================================================
           🌌 NeoDark Ultra Pro — Hybrid Neon XXL Mobile Edition (100% Size)
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
            /* 💥 تم حذف transform: scale و overflow-x:auto */
            padding-bottom: 92px; /* 💥 حذف calc و --scale-factor */
            min-height: 100vh;
            width: 100vw;
            /* 💥 overflow-x: auto محذوف */
            box-sizing: border-box;
        }

        /* =============================
           🧊 NAVBAR XXL
        ============================= */
        .top-navbar {
            padding: 22px; /* 💥 حذف calc و --scale-factor */
            backdrop-filter: blur(16px); /* 💥 حذف calc و --scale-factor */
            background: rgba(15,23,42,0.65);
            border-bottom: 1px solid var(--glass-border); /* 💥 حذف calc و --scale-factor */
            position: sticky;
            top: 0;
            z-index: 900;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .top-navbar-title {
            font-size: 27px; /* 💥 حذف calc و --scale-factor */
            font-weight: 800;
        }

        /* =============================
           📱 DRAWER XXL
        ============================= */
        #drawerOverlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.55);
            display: none;
            z-index: 1499;
        }
        #drawerOverlay.show { display:block; }

        #drawer {
            width: 330px; /* 💥 حذف calc و --scale-factor */
            height: 100vh;
            position: fixed;
            right: -350px; /* 💥 حذف calc و --scale-factor */
            top: 0;
            padding: 28px; /* 💥 حذف calc و --scale-factor */
            background: linear-gradient(155deg, var(--dark1), var(--dark2));
            box-shadow: var(--glow-green), var(--glow-blue);
            border-left: 1px solid rgba(255,255,255,.1); /* 💥 حذف calc و --scale-factor */
            transition: .35s ease-in-out;
            overflow-y: auto;
            z-index: 1500;
        }
        #drawer.open { right: 0; }

        .drawer-link {
            display: flex;
            align-items: center;
            padding: 20px; /* 💥 حذف calc و --scale-factor */
            margin-bottom: 14px; /* 💥 حذف calc و --scale-factor */
            border-radius: 16px; /* 💥 حذف calc و --scale-factor */
            background: rgba(255,255,255,0.05);
            color: var(--text-main);
            text-decoration:none;
            gap: 18px; /* 💥 حذف calc و --scale-factor */
            border: 1px solid rgba(255,255,255,0.08); /* 💥 حذف calc و --scale-factor */
            transition:.25s;
        }
        .drawer-link:hover {
            background: rgba(34,197,94,.22);
            border-color: rgba(34,197,94,.45);
        }
        .drawer-link.active {
            background: linear-gradient(135deg, rgba(34,197,94,.3), rgba(59,130,246,.3));
            box-shadow: var(--glow-green), var(--glow-blue);
            border-color: rgba(34,197,94,.6);
        }
        .drawer-icon {
            width: 46px; /* 💥 حذف calc و --scale-factor */
            height: 46px; /* 💥 حذف calc و --scale-factor */
            border-radius: 14px; /* 💥 حذف calc و --scale-factor */
            display:flex;
            align-items:center;
            justify-content:center;
            background: rgba(255,255,255,0.08);
        }

        /* =============================
           📦 CONTENT XXL
        ============================= */
        .content-wrapper { 
            padding: 5px; /* 💥 حذف calc و --scale-factor */
            padding-bottom: 95px; /* 💥 حذف calc و --scale-factor */
            transform: translateX(0); 
        }
        .page-shell {
            padding: 10px; /* 💥 حذف calc و --scale-factor */
            border-radius: 22px; /* 💥 حذف calc و --scale-factor */
            border: 1px solid rgba(255,255,255,0.08); /* 💥 حذف calc و --scale-factor */
            background: rgba(15,23,42,.65);
            box-shadow: 0 0 22px rgba(0,0,0,.55); /* 💥 حذف calc و --scale-factor */
        }

        /* =============================
           🔘 BUTTON XXL
        ============================= */
        .btn, button {
            padding: 18px 26px !important; /* 💥 حذف calc و --scale-factor */
            border-radius: 14px !important; /* 💥 حذف calc و --scale-factor */
        }
        .btn i {
            /* font-size is now controlled by body scale */
        }

        /* =============================
           ✍ TEXT XXL
        ============================= */
        h1, h2, h3 { 
            /* font-size is now controlled by body scale */
        }
        p, span, td, th { 
            /* font-size is now controlled by body scale */
        }

        /* =============================
           📱 BOTTOM NAV XXL
        ============================= */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            inset-inline: 0;
            height: 92px; /* 💥 حذف calc و --scale-factor */
            background: var(--bottom-bg);
            backdrop-filter: blur(14px); /* 💥 حذف calc و --scale-factor */
            border-top: 1px solid rgba(255,255,255,.15); /* 💥 حذف calc و --scale-factor */
            display:flex;
            justify-content: space-around;
            align-items:center;
            z-index:2000;
            /* 💥 transform: scale و transform-origin محذوفان */
            transition: none; 
        }
        .bottom-nav-item {
            text-align:center;
            color: var(--text-muted);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px; /* 💥 حذف calc و --scale-factor */
            padding: 8px 12px; /* 💥 حذف calc و --scale-factor */
            border-radius: 10px; /* 💥 حذف calc و --scale-factor */
            transition: all 0.2s ease;
        }
        .bottom-nav-item i {
            font-size: 22px; /* 💥 حذف calc و --scale-factor */
        }
        .bottom-nav-item span {
            font-size: 10px; /* 💥 حذف calc و --scale-factor */
        }
        .bottom-nav-item:hover,
        .bottom-nav-item.active {
            background: rgba(34,197,94, 0.1);
            color: var(--neon-green);
            text-shadow: var(--glow-green);
        }
        .bottom-nav-item.active {
            background: linear-gradient(135deg, rgba(34,197,94,.2), rgba(59,130,246,.2));
        }

        /* =============================
           📱 MOBILE HIDE (Added)
        ============================= */
        @media (max-width: 768px) { /* 💥 أو أي حجم تراه مناسب */
            .top-navbar {
                display: none; /* 💥 إخفاء الشريط العلوي */
            }
            #openDrawer {
                display: none; /* 💥 إخفاء زر فتح القائمة */
            }
            .page-shell {
                padding-top: 5px; /* 💥 تقليل المساحة من الأعلى */
                border-radius: 0; /* 💥 إزالة الزوايا المدوّرة من الأعلى */
            }
            .content-wrapper {
                padding-top: 5px; /* 💥 تقليل مساحة الأعلى */
            }
        }

        @media(min-width:992px) {
            .bottom-nav { display:none; }
        }

    </style>
</head>

<body>

{{-- NAVBAR --}}
<header class="top-navbar">
    <div class="top-navbar-title">@yield('title')</div>

    <button id="openDrawer" class="btn btn-outline-light d-lg-none">
        <i class="bi bi-list fs-3"></i>
    </button>
</header>

{{-- DRAWER --}}
<div id="drawerOverlay"></div>

<aside id="drawer">

    <h4 class="mb-4 text-center">قائمة الموظف</h4>

    @if(hasAnySidebarPermission($employeePermissions))
        <a href="{{ route('employee.dashboard') }}" class="drawer-link {{ request()->routeIs('employee.dashboard') ? 'active':'' }}">
            <div class="drawer-icon"><i class="bi bi-speedometer2"></i></div>
            الرئيسية
        </a>

        <a href="{{ route('employee.tasks.index') }}" class="drawer-link {{ request()->routeIs('employee.tasks.*') ? 'active':'' }}">
            <div class="drawer-icon"><i class="bi bi-list-check"></i></div>
            مهامي
        </a>
    @endif

    @if(hasAnySidebarPermission($responsePermissions))
        <a href="{{ route('response.dashboard') }}" class="drawer-link {{ request()->routeIs('response.dashboard') ? 'active':'' }}">
            <div class="drawer-icon"><i class="bi bi-chat-dots"></i></div>
            لوحة الردود
        </a>

        <a href="{{ route('response.orders.index') }}" class="drawer-link {{ request()->routeIs('response.orders.*') ? 'active':'' }}">
            <div class="drawer-icon"><i class="bi bi-bag-check"></i></div>
            الطلبات
        </a>

        <a href="{{ route('response.laptops.index') }}" class="drawer-link {{ request()->routeIs('response.laptops.*') ? 'active':'' }}">
            <div class="drawer-icon"><i class="bi bi-laptop"></i></div>
            المنتجات
        </a>
    @endif

    <a href="{{ route('attendance.dashboard') }}" class="drawer-link {{ request()->routeIs('attendance.dashboard') ? 'active':'' }}">
        <div class="drawer-icon"><i class="bi bi-calendar-check"></i></div>
        الحضور والانصراف
    </a>
</aside>

{{-- CONTENT --}}
<div class="content-wrapper">
    <div class="page-shell">
        @yield('content')
    </div>
</div>

{{-- BOTTOM NAV (Outside main body scaling context) - مُحدث لاحتواء روابط مميزة --}}
<div class="bottom-nav d-lg-none">
    <!-- الرئيسية -->
    @if(hasAnySidebarPermission($employeePermissions) || hasAnySidebarPermission($responsePermissions))
        <a href="{{ route('employee.dashboard') }}" class="bottom-nav-item {{ request()->routeIs('employee.dashboard') ? 'active':'' }}">
            <i class="bi bi-speedometer2"></i>
            <span>الرئيسية</span>
        </a>
    @endif

    <!-- المهام -->
    @if(hasAnySidebarPermission($employeePermissions))
        <a href="{{ route('employee.tasks.index') }}" class="bottom-nav-item {{ request()->routeIs('employee.tasks.*') ? 'active':'' }}">
            <i class="bi bi-list-check"></i>
            <span>المهام</span>
        </a>
    @endif

    <!-- الطلبات -->
    @if(hasAnySidebarPermission($responsePermissions))
        <a href="{{ route('response.orders.index') }}" class="bottom-nav-item {{ request()->routeIs('response.orders.*') ? 'active':'' }}">
            <i class="bi bi-bag-check"></i>
            <span>الطلبات</span>
        </a>
    @endif

    <!-- الحضور -->
    <a href="{{ route('attendance.dashboard') }}" class="bottom-nav-item {{ request()->routeIs('attendance.dashboard') ? 'active':'' }}">
        <i class="bi bi-calendar-check"></i>
        <span>الحضور</span>
    </a>

    <!-- المنتجات (إذا كانت الصلاحية موجودة) -->
    @if(hasAnySidebarPermission($responsePermissions))
        <a href="{{ route('response.laptops.index') }}" class="bottom-nav-item {{ request()->routeIs('response.laptops.*') ? 'active':'' }}">
            <i class="bi bi-laptop"></i>
            <span>المنتجات</span>
        </a>
    @endif
</div>

<script>
    let drawer = document.getElementById("drawer");
    let overlay = document.getElementById("drawerOverlay");
    let openBtn = document.getElementById("openDrawer");

    openBtn.addEventListener("click", () => {
        drawer.classList.add("open");
        overlay.classList.add("show");
    });

    overlay.addEventListener("click", () => {
        drawer.classList.remove("open");
        overlay.classList.remove("show");
    });
</script>

@yield('scripts')

</body>
</html>