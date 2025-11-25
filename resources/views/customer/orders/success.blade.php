<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نجاح الطلب - متجر BI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        /* نفس CSS من الكود الأصلي */
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
            color: white;
            padding: 20px 0;
        }

        .container {
            padding: 0 15px;
        }

        .success-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 40px 30px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        h1 {
            color: var(--success);
            font-weight: 800;
            font-size: 3em;
            margin-bottom: 20px;
        }

        .btn-primary {
            background: var(--accent);
            border: none;
            border-radius: 12px;
            font-weight: 600;
            padding: 12px 24px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(52, 152, 219, 0.4);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="success-card">
            <i class="bi bi-check-circle-fill" style="font-size: 5rem; color: var(--success);"></i>
            <h1>تم بنجاح!</h1>
            <p class="lead">تم إرسال طلبك بنجاح.</p>
            <p>رقم الطلب: <strong>#{{ $order->id }}</strong></p>
            <p>سيتم التواصل معك قريبًا.</p>
            <a href="{{ route('home') }}" class="btn btn-primary mt-3">العودة للقائمة</a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
