<!--<!DOCTYPE html>-->
<!--<html lang="ar" dir="rtl">-->

<!--<head>-->
<!--    <meta charset="utf-8"/>-->
<!--    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">-->
<!--    <title>@yield('title', 'لوحة الموظف')</title>-->

    <!-- Bootstrap RTL -->
<!--    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">-->

    <!-- Icons -->
<!--    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">-->

<!--    <style>-->
        /* =====================================================
           🌌 NeoDark Ultra Pro — Hybrid Neon XXL Mobile Edition
           ===================================================== */

<!--        :root {-->
<!--            --dark1: #020617;-->
<!--            --dark2: #0b1120;-->
<!--            --neon-green: #22c55e;-->
<!--            --neon-blue: #3b82f6;-->
<!--            --text-main: #e2e8f0;-->
<!--            --text-muted: #94a3b8;-->

<!--            --glass-bg: rgba(15,23,42,0.55);-->
<!--            --glass-border: rgba(255,255,255,0.08);-->

<!--            --glow-green: 0 0 14px rgba(34,197,94,.6);-->
<!--            --glow-blue: 0 0 20px rgba(59,130,246,.6);-->

<!--            --bottom-bg: rgba(2,6,23,0.75);-->
<!--        }-->

<!--        body {-->
<!--            background: radial-gradient(circle at top left, #1e293b, var(--dark1) 60%);-->
<!--            color: var(--text-main);-->
<!--            font-family: "Tajawal", sans-serif;-->
            padding-bottom: 92px; /* مساحة للـ Bottom Nav */
            margin: 0; /* إزالة الهامش الافتراضي */
            /* لا توجد تكبيرات أو تصغيرات */
<!--        }-->

        /* =============================
           🧊 NAVBAR XXL
        ============================= */
<!--        .top-navbar {-->
            padding: 15px 12px; /* تصغير الـ Padding */
            font-size: 1.6rem; /* تصغير الحجم */
<!--            backdrop-filter: blur(16px);-->
<!--            background: rgba(15,23,42,0.65);-->
<!--            border-bottom: 1px solid var(--glass-border);-->
<!--            position: sticky;-->
<!--            top: 0;-->
<!--            z-index: 900;-->
<!--            display: flex;-->
<!--            justify-content: space-between;-->
<!--            align-items: center;-->
<!--        }-->
<!--        .top-navbar-title {-->
            font-size: 1.8rem; /* تصغير الحجم */
<!--            font-weight: 800;-->
<!--            white-space: nowrap;-->
<!--            overflow: hidden;-->
<!--            text-overflow: ellipsis;-->
<!--        }-->

        /* =============================
           📱 DRAWER XXL
        ============================= */
<!--        #drawerOverlay {-->
<!--            position: fixed;-->
<!--            inset: 0;-->
<!--            background: rgba(0,0,0,.55);-->
<!--            display: none;-->
<!--            z-index: 2000;-->
<!--        }-->
<!--        #drawerOverlay.show { display:block; }-->

<!--        #drawer {-->
            width: 300px; /* تصغير العرض */
<!--            height: 100vh;-->
<!--            position: fixed;-->
            right: -320px; /* تعديل الحركة */
<!--            top: 0;-->
            padding: 20px 16px; /* تصغير الـ Padding */
<!--            background: linear-gradient(155deg, var(--dark1), var(--dark2));-->
<!--            box-shadow: var(--glow-green), var(--glow-blue);-->
<!--            border-left: 1px solid rgba(255,255,255,.1);-->
<!--            transition: .35s ease-in-out;-->
<!--            overflow-y: auto;-->
<!--            z-index: 2100;-->
<!--        }-->
<!--        #drawer.open { right: 0; }-->

<!--        .drawer-link {-->
<!--            display: flex;-->
<!--            align-items: center;-->
            padding: 14px; /* تصغير الـ Padding */
            margin-bottom: 10px; /* تصغير المسافة */
            border-radius: 12px; /* تصغير الحواف */
            font-size: 1.3rem; /* تصغير الحجم */
<!--            background: rgba(255,255,255,0.05);-->
<!--            color: var(--text-main);-->
<!--            text-decoration:none;-->
            gap: 12px; /* تصغير الفراغ */
<!--            border: 1px solid rgba(255,255,255,0.08);-->
<!--            transition:.25s;-->
<!--        }-->
<!--        .drawer-link:hover {-->
<!--            background: rgba(34,197,94,.22);-->
<!--            border-color: rgba(34,197,94,.45);-->
<!--        }-->
<!--        .drawer-link.active {-->
<!--            background: linear-gradient(135deg, rgba(34,197,94,.3), rgba(59,130,246,.3));-->
<!--            box-shadow: var(--glow-green), var(--glow-blue);-->
<!--            border-color: rgba(34,197,94,.6);-->
<!--        }-->
<!--        .drawer-icon {-->
            width: 36px; /* تصغير الأيقونة */
<!--            height: 36px;-->
            font-size: 1.4rem; /* تصغير حجم الأيقونة */
            border-radius: 10px; /* تصغير الحواف */
<!--            display:flex;-->
<!--            align-items:center;-->
<!--            justify-content:center;-->
<!--            background: rgba(255,255,255,0.08);-->
<!--        }-->

        /* =============================
           📦 CONTENT XXL
        ============================= */
        .content-wrapper { padding: 14px; } /* تصغير الـ Padding */
<!--        .page-shell {-->
            padding: 18px; /* تصغير الـ Padding */
            border-radius: 16px; /* تصغير الحواف */
<!--            border:1px solid rgba(255,255,255,0.08);-->
<!--            background: rgba(15,23,42,.65);-->
<!--            box-shadow: 0 0 22px rgba(0,0,0,.55);-->
<!--        }-->

        /* =============================
           🔘 BUTTON XXL
        ============================= */
<!--        .btn, button {-->
            padding: 12px 18px !important; /* تصغير الـ Padding */
            font-size: 1.2rem !important; /* تصغير الحجم */
            border-radius: 12px !important; /* تصغير الحواف */
<!--        }-->
<!--        .btn i {-->
            font-size: 1.4rem !important; /* تصغير حجم الأيقونة */
<!--        }-->

        /* =============================
           ✍ TEXT XXL
        ============================= */
        h1, h2, h3 { font-size: 1.6rem !important; } /* تصغير الحجم */
        p, span, td, th { font-size: 1rem !important; } /* تصغير الحجم */

        /* =============================
           📱 BOTTOM NAV XXL
        ============================= */
<!--        .bottom-nav {-->
<!--            position: fixed;-->
<!--            bottom: 0;-->
<!--            inset-inline: 0;-->
            height: 70px; /* تصغير الارتفاع */
<!--            background: var(--bottom-bg);-->
<!--            backdrop-filter: blur(14px);-->
<!--            border-top: 1px solid rgba(255,255,255,.15);-->
<!--            display:flex;-->
<!--            justify-content: space-around;-->
<!--            align-items:center;-->
<!--            z-index:1500;-->
<!--        }-->
<!--        .bottom-nav-item {-->
<!--            text-align:center;-->
<!--            color: var(--text-muted);-->
            font-size: 0.9rem; /* تصغير الحجم */
<!--        }-->
<!--        .bottom-nav-item i {-->
            font-size: 1.8rem; /* تصغير حجم الأيقونة */
<!--        }-->
<!--        .bottom-nav-item.active {-->
<!--            color: var(--neon-green);-->
<!--            text-shadow: var(--glow-green);-->
<!--        }-->

<!--        @media(min-width:992px) {-->
<!--            .bottom-nav { display:none; }-->
<!--            #drawer { position: static; width: auto; height: auto; box-shadow: none; border-left: none; }-->
<!--            .drawer-link { display: inline-block; padding: 8px 12px; margin: 0 2px; border-radius: 6px; font-size: 0.9rem; }-->
<!--            .drawer-icon { display: none; }-->
<!--        }-->

<!--    </style>-->
<!--</head>-->

<!--<body>-->

<!--{{-- NAVBAR --}}-->
<!--<header class="top-navbar">-->
<!--    <div class="top-navbar-title">@yield('title')</div>-->

<!--    <button id="openDrawer" class="btn btn-outline-light d-lg-none">-->
<!--        <i class="bi bi-list fs-3"></i>-->
<!--    </button>-->
<!--</header>-->

<!--{{-- DRAWER --}}-->
<!--<div id="drawerOverlay"></div>-->

<!--<aside id="drawer">-->

<!--    <h4 class="mb-4 text-center">قائمة الموظف</h4>-->

<!--    @if(hasAnySidebarPermission($employeePermissions))-->
<!--        <a href="{{ route('employee.dashboard') }}" class="drawer-link {{ request()->routeIs('employee.dashboard') ? 'active':'' }}">-->
<!--            <div class="drawer-icon"><i class="bi bi-speedometer2"></i></div>-->
<!--            الرئيسية-->
<!--        </a>-->

<!--        <a href="{{ route('employee.tasks.index') }}" class="drawer-link {{ request()->routeIs('employee.tasks.*') ? 'active':'' }}">-->
<!--            <div class="drawer-icon"><i class="bi bi-list-check"></i></div>-->
<!--            مهامي-->
<!--        </a>-->
<!--    @endif-->

<!--    @if(hasAnySidebarPermission($responsePermissions))-->
<!--        <a href="{{ route('response.dashboard') }}" class="drawer-link {{ request()->routeIs('response.dashboard') ? 'active':'' }}">-->
<!--            <div class="drawer-icon"><i class="bi bi-chat-dots"></i></div>-->
<!--            لوحة الردود-->
<!--        </a>-->

<!--        <a href="{{ route('response.orders.index') }}" class="drawer-link {{ request()->routeIs('response.orders.*') ? 'active':'' }}">-->
<!--            <div class="drawer-icon"><i class="bi bi-bag-check"></i></div>-->
<!--            الطلبات-->
<!--        </a>-->

<!--        <a href="{{ route('response.laptops.index') }}" class="drawer-link {{ request()->routeIs('response.laptops.*') ? 'active':'' }}">-->
<!--            <div class="drawer-icon"><i class="bi bi-laptop"></i></div>-->
<!--            المنتجات-->
<!--        </a>-->
<!--    @endif-->

<!--    <a href="{{ route('attendance.dashboard') }}" class="drawer-link {{ request()->routeIs('attendance.dashboard') ? 'active':'' }}">-->
<!--        <div class="drawer-icon"><i class="bi bi-calendar-check"></i></div>-->
<!--        الحضور والانصراف-->
<!--    </a>-->
<!--</aside>-->

<!--{{-- CONTENT --}}-->
<!--<div class="content-wrapper">-->
<!--    <div class="page-shell">-->
<!--        @yield('content')-->
<!--    </div>-->
<!--</div>-->

<!--{{-- BOTTOM NAV --}}-->
<!--<div class="bottom-nav d-lg-none">-->

<!--    <a href="{{ route('employee.dashboard') }}" class="bottom-nav-item {{ request()->routeIs('employee.dashboard') ? 'active':'' }}">-->
<!--        <i class="bi bi-speedometer2"></i>-->
<!--        <div>الرئيسية</div>-->
<!--    </a>-->

<!--    <a href="{{ route('employee.tasks.index') }}" class="bottom-nav-item {{ request()->routeIs('employee.tasks.*') ? 'active':'' }}">-->
<!--        <i class="bi bi-list-check"></i>-->
<!--        <div>المهام</div>-->
<!--    </a>-->

<!--    <a href="{{ route('attendance.dashboard') }}" class="bottom-nav-item {{ request()->routeIs('attendance.dashboard') ? 'active':'' }}">-->
<!--        <i class="bi bi-calendar-check"></i>-->
<!--        <div>الحضور</div>-->
<!--    </a>-->

<!--</div>-->

<!--<script>-->
<!--    let drawer = document.getElementById("drawer");-->
<!--    let overlay = document.getElementById("drawerOverlay");-->
<!--    let openBtn = document.getElementById("openDrawer");-->

<!--    openBtn.addEventListener("click", () => {-->
<!--        drawer.classList.add("open");-->
<!--        overlay.classList.add("show");-->
<!--    });-->

<!--    overlay.addEventListener("click", () => {-->
<!--        drawer.classList.remove("open");-->
<!--        overlay.classList.remove("show");-->
<!--    });-->
<!--</script>-->

<!--@yield('scripts')-->

<!--</body>-->
<!--</html>-->