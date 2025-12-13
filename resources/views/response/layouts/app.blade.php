<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'لوحة التحكم')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            background: #f9fafb;
            font-family: "Tajawal", sans-serif;
        }

        .sidebar {
            width: 260px;
            background: #111827;
            height: 100vh;
            position: fixed;
            top: 0;
            right: 0;
            padding-top: 20px;
            color: white;
        }

        .sidebar a {
            display: block;
            padding: 12px 20px;
            color: #d1d5db;
            text-decoration: none;
            margin-bottom: 5px;
            border-radius: 6px;
            font-size: 15px;
        }

        .sidebar a:hover,
        .sidebar .active {
            background: #1f2937;
            color: white;
        }

        .content {
            margin-right: 270px;
            padding: 30px;
        }

        .navbar {
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 25px;
        }

        .navbar-brand {
            font-weight: bold;
            color: #111827;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-center mb-4">👨‍💼 موظف الردود</h4>

        <a href="{{ route('response.dashboard') }}"
            class="{{ request()->routeIs('response.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> لوحة التحكم
        </a>
<a href="{{ route('response.laptops.index') }}"
   class="{{ request()->routeIs('response.laptops.*') ? 'active' : '' }}">
    <i class="bi bi-laptop"></i> المنتجات
</a>
        <a href="{{ route('response.orders.index') }}"
            class="{{ request()->routeIs('response.orders.*') ? 'active' : '' }}">
            <i class="bi bi-bag-check"></i> الطلبات
        </a>

        <a href="#" onclick="document.getElementById('logout-form').submit();">
            <i class="bi bi-box-arrow-left"></i> تسجيل الخروج
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST">
            @csrf
        </form>
    </div>

    <!-- Main Content -->
    <div class="content">

        <!-- Navbar -->
        <nav class="navbar px-4 mb-4">
            <span class="navbar-brand">مرحبا، {{ auth()->user()->name }}</span>
        </nav>

        @yield('content')
    </div>

</body>

</html>
