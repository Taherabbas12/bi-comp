<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>متجر BI للحاسبات</title>

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

        body {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            padding: 20px 0;
        }

        .container {
            padding: 0 15px;
        }

        .header {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 30px;
            margin-bottom: 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
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

        .filter-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            padding: 25px;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .filter-card h2 {
            color: white;
            font-weight: 700;
            font-size: 1.8em;
            margin-bottom: 25px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.3);
            padding-bottom: 10px;
        }

        .btn-primary {
            background: var(--accent);
            border: none;
            border-radius: 12px;
            font-weight: 600;
            padding: 12px 24px;
            transition: all .3s ease;
        }

        .btn-primary:hover {
            background: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(52, 152, 219, .4);
        }

        .results-info {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            font-size: 1.2em;
            font-weight: 600;
            color: #ecf0f1;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* product card styles (مهيأة للعرض) */
        .product-card {
            border: none;
            border-radius: 18px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            transition: all .4s ease;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: white;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 18px 50px rgba(0, 0, 0, 0.28);
        }

        .brand-badge {
            background: linear-gradient(135deg, var(--accent) 0%, #2c3e50 100%);
            color: white;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: .85em;
            font-weight: 700;
            text-transform: uppercase;
        }

        .quantity-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: .85em;
            font-weight: 600;
            color: white;
        }

        .quantity-badge.low {
            background: var(--warning);
        }

        .quantity-badge.out {
            background: var(--danger);
        }

        .product-model {
            font-size: 1.15em;
            font-weight: 700;
            color: white;
            margin: 12px 0;
            line-height: 1.3;
        }

        .specs-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin: 12px 0;
            padding: 12px;
            background: rgba(248, 249, 250, 0.06);
            border-radius: 10px;
            font-size: .9em;
            color: white;
        }

        .spec-label {
            font-size: .8em;
            color: #bdc3c7;
            margin-bottom: 3px;
        }

        .spec-value {
            font-weight: 700;
            color: white;
        }

        .features {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin: 12px 0;
        }

        .feature-tag {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: .8em;
            font-weight: 600;
            color: white;
        }

        .feature-touch {
            background: #e3f2fd;
            color: #1976d2;
        }

        .feature-convertible {
            background: #f3e5f5;
            color: #7b1fa2;
        }

        .feature-gpu {
            background: #fff3e0;
            color: #e65100;
        }

        .feature-gaming {
            background: #ffebee;
            color: #c62828;
        }

        .feature-editing {
            background: #e8f5e8;
            color: #2e7d32;
        }

        .price-section {
            margin-top: auto;
            padding-top: 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            text-align: center;
        }

        .price {
            font-size: 1.4em;
            font-weight: 800;
            color: var(--accent);
            margin-bottom: 4px;
        }

        .payment-info {
            font-size: .85em;
            color: #ecf0f1;
            margin-top: 6px;
        }

        .barcode {
            font-size: .85em;
            color: #bdc3c7;
            font-family: 'Courier New', monospace;
            margin-top: 6px;
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            padding: 10px;
            border: 2px solid rgba(255, 255, 255, 0.15);
            background: rgba(255, 255, 255, 0.06);
            color: white;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        label {
            color: white;
        }

        @media (max-width:768px) {
            h1 {
                font-size: 2.2em;
            }

            .filter-card {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <header class="header">
            <h1>🖥️ متجر BI للحاسبات</h1>
            <p class="subtitle">أفضل العروض على الأجهزة عالية الجودة</p>

            <a href="{{ route('logout') }}" class="btn btn-danger mt-3"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right"></i> تسجيل الخروج
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        </header>
