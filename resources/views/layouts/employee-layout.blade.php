@php
    $employeePermissions = [
        'employee_dashboard',
        'view_employee_tasks',
        'employee_tasks',
        'update_employee_task_progress',
        'view_tasks',
        'edit_tasks',
        'manage_tasks',
        'manage_priorities',
        'create_users',
        'view_users',
        'delete_users',
        'edit_roles',
        'view_roles',
        'delete_roles',
    ];

    $responsePermissions = [
        'view_response_dashboard',
        'view_orders_for_response',
        'update_order_status_to_confirmed_by_response',
        'create_orders_for_customers_as_response',
        'update_order_status',
        'update_order_status_to_preparing',
        'update_order_status_to_ready',
        'update_order_status_to_confirmed',
        'view_orders',
        'delete_laptops',
    ];

    if (!function_exists('hasAnySidebarPermission')) {
        function hasAnySidebarPermission(array $permissions): bool
        {
            $user = auth()->user();
            if (!$user) {
                return false;
            }
            foreach ($permissions as $perm) {
                if ($user->hasPermission($perm)) {
                    return true;
                }
            }
            return false;
        }
    }
@endphp

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'لوحة الموظف')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --dark1: #020617;
            --dark2: #0b1120;
            --neon-green: #22c55e;
            --neon-blue: #3b82f6;
            --text-main: #e2e8f0;
            --text-muted: #94a3b8;
            --glass-bg: rgba(15, 23, 42, 0.55);
            --glass-border: rgba(255, 255, 255, 0.08);
            --glow-green: 0 0 14px rgba(34, 197, 94, .6);
            --glow-blue: 0 0 20px rgba(59, 130, 246, .6);
            --bottom-bg: rgba(2, 6, 23, 0.75);
            --sidebar-width: 330px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: radial-gradient(circle at top left, #1e293b, var(--dark1) 60%);
            color: var(--text-main);
            font-family: "Tajawal", sans-serif;
            min-height: 100vh;
            width: 100vw;
            overflow-x: hidden;
        }

        /* =============================
           🖥️ DESKTOP LAYOUT (≥992px)
        ============================= */
        @media (min-width: 992px) {
            body {
                padding-bottom: 0;
            }

            .desktop-sidebar {
                position: fixed;
                top: 0;
                right: 0;
                width: var(--sidebar-width);
                height: 100vh;
                padding: 28px;
                background: linear-gradient(155deg, var(--dark1), var(--dark2));
                box-shadow: var(--glow-green), var(--glow-blue);
                border-left: 1px solid rgba(255, 255, 255, .1);
                overflow-y: auto;
                z-index: 1000;
                backdrop-filter: blur(12px);
            }

            .desktop-sidebar h4 {
                font-size: 1.25rem;
                font-weight: 700;
                margin-bottom: 24px;
                text-align: center;
                color: var(--text-main);
            }

            .content-wrapper {
                margin-right: var(--sidebar-width);
                padding: 28px;
                min-height: 100vh;
            }

            .page-shell {
                padding: 24px;
                border-radius: 22px;
                border: 1px solid var(--glass-border);
                background: var(--glass-bg);
                box-shadow: 0 0 22px rgba(0, 0, 0, .55);
                backdrop-filter: blur(16px);
            }

            .top-navbar,
            .bottom-nav,
            #openDrawer {
                display: none !important;
            }
        }

        /* =============================
           📱 MOBILE LAYOUT (<992px)
        ============================= */
        @media (max-width: 991.98px) {
            body {
                padding-bottom: 92px;
            }

            .top-navbar {
                padding: 22px;
                backdrop-filter: blur(16px);
                background: rgba(15, 23, 42, 0.65);
                border-bottom: 1px solid var(--glass-border);
                position: sticky;
                top: 0;
                z-index: 900;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .top-navbar-title {
                font-size: 27px;
                font-weight: 800;
            }

            #drawerOverlay {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, .55);
                display: none;
                z-index: 1499;
            }

            #drawerOverlay.show {
                display: block;
            }

            #drawer {
                width: var(--sidebar-width);
                height: 100vh;
                position: fixed;
                right: -350px;
                top: 0;
                padding: 28px;
                background: linear-gradient(155deg, var(--dark1), var(--dark2));
                box-shadow: var(--glow-green), var(--glow-blue);
                border-left: 1px solid rgba(255, 255, 255, .1);
                transition: .35s ease-in-out;
                overflow-y: auto;
                z-index: 1500;
            }

            #drawer.open {
                right: 0;
            }

            .content-wrapper {
                padding: 5px;
                padding-bottom: 95px;
            }

            .page-shell {
                padding: 10px;
                border-radius: 0;
                border: 1px solid rgba(255, 255, 255, 0.08);
                background: rgba(15, 23, 42, .65);
                box-shadow: 0 0 22px rgba(0, 0, 0, .55);
            }

            .bottom-nav {
                position: fixed;
                bottom: 0;
                inset-inline: 0;
                height: 92px;
                background: var(--bottom-bg);
                backdrop-filter: blur(14px);
                border-top: 1px solid rgba(255, 255, 255, .15);
                display: flex;
                justify-content: space-around;
                align-items: center;
                z-index: 2000;
            }

            .bottom-nav-item {
                text-align: center;
                color: var(--text-muted);
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 4px;
                padding: 8px 12px;
                border-radius: 10px;
                transition: all 0.2s ease;
            }

            .bottom-nav-item i {
                font-size: 22px;
            }

            .bottom-nav-item span {
                font-size: 10px;
            }

            .bottom-nav-item:hover,
            .bottom-nav-item.active {
                background: rgba(34, 197, 94, 0.1);
                color: var(--neon-green);
                text-shadow: var(--glow-green);
            }

            .bottom-nav-item.active {
                background: linear-gradient(135deg, rgba(34, 197, 94, .2), rgba(59, 130, 246, .2));
            }
        }

        /* =============================
           🔗 SHARED DRAWER LINK STYLE (Mobile + Desktop)
        ============================= */
        .drawer-link {
            display: flex;
            align-items: center;
            padding: 20px;
            margin-bottom: 14px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-main);
            text-decoration: none;
            gap: 18px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: .25s;
        }

        .drawer-link:hover {
            background: rgba(34, 197, 94, .22);
            border-color: rgba(34, 197, 94, .45);
        }

        .drawer-link.active {
            background: linear-gradient(135deg, rgba(34, 197, 94, .3), rgba(59, 130, 246, .3));
            box-shadow: var(--glow-green), var(--glow-blue);
            border-color: rgba(34, 197, 94, .6);
        }

        .drawer-icon {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.08);
        }
    </style>
</head>

<body>

    {{-- DESKTOP SIDEBAR - نفس دزاين الموبايل تمامًا --}}
    <aside class="desktop-sidebar d-none d-lg-block">
        <h4 class="mb-4 text-center">قائمة الموظف</h4>

        @if (hasAnySidebarPermission($employeePermissions))
            <a href="{{ route('employee.dashboard') }}"
                class="drawer-link {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
                <div class="drawer-icon"><i class="bi bi-speedometer2"></i></div>
                الرئيسية
            </a>
            <a href="{{ route('employee.tasks.index') }}"
                class="drawer-link {{ request()->routeIs('employee.tasks.*') ? 'active' : '' }}">
                <div class="drawer-icon"><i class="bi bi-list-check"></i></div>
                مهامي
            </a>
        @endif

        @if (hasAnySidebarPermission($responsePermissions))
            <a href="{{ route('response.dashboard') }}"
                class="drawer-link {{ request()->routeIs('response.dashboard') ? 'active' : '' }}">
                <div class="drawer-icon"><i class="bi bi-chat-dots"></i></div>
                لوحة الردود
            </a>
            <a href="{{ route('response.orders.index') }}"
                class="drawer-link {{ request()->routeIs('response.orders.*') ? 'active' : '' }}">
                <div class="drawer-icon"><i class="bi bi-bag-check"></i></div>
                الطلبات
            </a>
            <a href="{{ route('response.laptops.index') }}"
                class="drawer-link {{ request()->routeIs('response.laptops.*') ? 'active' : '' }}">
                <div class="drawer-icon"><i class="bi bi-laptop"></i></div>
                المنتجات
            </a>
        @endif

        <a href="{{ route('attendance.dashboard') }}"
            class="drawer-link {{ request()->routeIs('attendance.dashboard') ? 'active' : '' }}">
            <div class="drawer-icon"><i class="bi bi-calendar-check"></i></div>
            الحضور والانصراف
        </a>
    </aside>

    {{-- MOBILE NAVBAR --}}
    <header class="top-navbar d-lg-none">
        <div class="top-navbar-title">@yield('title')</div>
        <button id="openDrawer" class="btn btn-outline-light d-lg-none">
            <i class="bi bi-list fs-3"></i>
        </button>
    </header>

    {{-- MOBILE DRAWER --}}
    <div id="drawerOverlay"></div>
    <aside id="drawer" class="d-lg-none">
        <h4 class="mb-4 text-center">قائمة الموظف</h4>

        @if (hasAnySidebarPermission($employeePermissions))
            <a href="{{ route('employee.dashboard') }}"
                class="drawer-link {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
                <div class="drawer-icon"><i class="bi bi-speedometer2"></i></div>
                الرئيسية
            </a>
            <a href="{{ route('employee.tasks.index') }}"
                class="drawer-link {{ request()->routeIs('employee.tasks.*') ? 'active' : '' }}">
                <div class="drawer-icon"><i class="bi bi-list-check"></i></div>
                مهامي
            </a>
        @endif

        @if (hasAnySidebarPermission($responsePermissions))
            <a href="{{ route('response.dashboard') }}"
                class="drawer-link {{ request()->routeIs('response.dashboard') ? 'active' : '' }}">
                <div class="drawer-icon"><i class="bi bi-chat-dots"></i></div>
                لوحة الردود
            </a>
            <a href="{{ route('response.orders.index') }}"
                class="drawer-link {{ request()->routeIs('response.orders.*') ? 'active' : '' }}">
                <div class="drawer-icon"><i class="bi bi-bag-check"></i></div>
                الطلبات
            </a>
            <a href="{{ route('response.laptops.index') }}"
                class="drawer-link {{ request()->routeIs('response.laptops.*') ? 'active' : '' }}">
                <div class="drawer-icon"><i class="bi bi-laptop"></i></div>
                المنتجات
            </a>
        @endif

        <a href="{{ route('attendance.dashboard') }}"
            class="drawer-link {{ request()->routeIs('attendance.dashboard') ? 'active' : '' }}">
            <div class="drawer-icon"><i class="bi bi-calendar-check"></i></div>
            الحضور والانصراف
        </a>
    </aside>

    {{-- MAIN CONTENT --}}
    <div class="content-wrapper">
        <div class="page-shell">
            @yield('content')
        </div>
    </div>

    {{-- MOBILE BOTTOM NAV --}}
    <div class="bottom-nav d-lg-none">
        @if (hasAnySidebarPermission($employeePermissions) || hasAnySidebarPermission($responsePermissions))
            <a href="{{ route('employee.dashboard') }}"
                class="bottom-nav-item {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>الرئيسية</span>
            </a>
        @endif

        @if (hasAnySidebarPermission($employeePermissions))
            <a href="{{ route('employee.tasks.index') }}"
                class="bottom-nav-item {{ request()->routeIs('employee.tasks.*') ? 'active' : '' }}">
                <i class="bi bi-list-check"></i>
                <span>المهام</span>
            </a>
        @endif

        @if (hasAnySidebarPermission($responsePermissions))
            <a href="{{ route('response.orders.index') }}"
                class="bottom-nav-item {{ request()->routeIs('response.orders.*') ? 'active' : '' }}">
                <i class="bi bi-bag-check"></i>
                <span>الطلبات</span>
            </a>
        @endif

        <a href="{{ route('attendance.dashboard') }}"
            class="bottom-nav-item {{ request()->routeIs('attendance.dashboard') ? 'active' : '' }}">
            <i class="bi bi-calendar-check"></i>
            <span>الحضور</span>
        </a>

        @if (hasAnySidebarPermission($responsePermissions))
            <a href="{{ route('response.laptops.index') }}"
                class="bottom-nav-item {{ request()->routeIs('response.laptops.*') ? 'active' : '' }}">
                <i class="bi bi-laptop"></i>
                <span>المنتجات</span>
            </a>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const drawer = document.getElementById("drawer");
            const overlay = document.getElementById("drawerOverlay");
            const openBtn = document.getElementById("openDrawer");

            if (openBtn) {
                openBtn.addEventListener("click", () => {
                    drawer.classList.add("open");
                    overlay.classList.add("show");
                });
            }

            if (overlay) {
                overlay.addEventListener("click", () => {
                    drawer.classList.remove("open");
                    overlay.classList.remove("show");
                });
            }
        });
    </script>

    @yield('scripts')
</body>

</html>
