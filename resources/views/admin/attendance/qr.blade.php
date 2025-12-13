@extends('admin.layouts.app')

@section('content')

<div class="container">

    {{-- صندوق التحقق --}}
    <div id="location-box" class="card p-4 text-center"
         style="background: rgba(0,0,0,0.5); border-radius: 20px;">

        <h4 class="mb-3">التحقق لعرض رمز الحضور</h4>

        <p class="text-white-50">اضغط على الزر للسماح للمتصفح بطلب موقعك.</p>

        <button class="btn btn-primary mt-2" id="startCheckBtn">
            <i class="bi bi-geo"></i> التحقق من الموقع
        </button>

        <p id="location-status" class="text-warning mt-3" style="display:none;">
            جاري التحقق من موقعك...
        </p>
    </div>

    {{-- صندوق QR --}}
    <div id="qr-box" class="card p-4 text-center mt-4"
         style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px);
                border-radius: 20px; display:none;">

        <h2 class="mb-3" style="font-weight:700;">رمز QR لتسجيل الحضور</h2>

        <p class="mb-4 text-white-50">قم بطباعة هذا الرمز وتعليقه عند مدخل الشركة</p>

        <div class="p-4 d-flex justify-content-center">
            @php
                $qrCodeValue = $qr->code ?? 'BI-COMPANY-QR-ATTENDANCE-2025';
            @endphp
            <img id="qr-image"
                 src=""
                 alt="QR Code"
                 style="border-radius: 12px; box-shadow: 0 0 20px rgba(255,255,255,0.25);">
        </div>

        {{-- زر الطباعة المخصص --}}
        <button class="btn btn-primary mt-3" onclick="printQR()">
            <i class="bi bi-printer"></i> طباعة الرمز
        </button>

    </div>

</div>

@endsection


@section('scripts')
<script>

document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("startCheckBtn").addEventListener("click", startLocationCheck);
});

// ============= دالة بدء التحقق =================
function startLocationCheck() {

    const status = document.getElementById('location-status');
    status.style.display = 'block';
    status.innerText = 'جاري التحقق من موقعك...';

    if (!navigator.geolocation) {
        status.innerText = 'المتصفح لا يدعم تحديد الموقع.';
        return;
    }

    navigator.geolocation.getCurrentPosition(
        (pos) => {
            verifyLocation(pos.coords.latitude, pos.coords.longitude);
        },
        () => {
            status.innerText = 'تعذر الحصول على الموقع. يرجى تفعيل GPS.';
        }
    );
}

// ============= دالة إرسال الموقع إلى Laravel =================
function verifyLocation(lat, lng) {

    fetch("{{ route('admin.attendance.qr.verify-location') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ lat, lng })
    })
    .then(res => res.json().then(data => ({ status: res.status, body: data })))
    .then(({ status, body }) => {

        if (status !== 200 || !body.allowed) {
            document.getElementById('location-status').innerText =
                body.message || "لا يمكنك عرض الرمز خارج الشركة.";
            return;
        }

        // السماح ✓
        document.getElementById('location-box').style.display = 'none';
        document.getElementById('qr-box').style.display = 'block';

        // عرض QR
        const qrValue = "{{ $qrCodeValue }}";
        document.getElementById('qr-image').src =
            "https://api.qrserver.com/v1/create-qr-code/?size=1000x1000&data=" +
            encodeURIComponent(qrValue);
    })
    .catch(() => {
        document.getElementById('location-status').innerText =
            'حدث خطأ أثناء التحقق.';
    });
}


// ============= دالة الطباعة ==============
function printQR() {
    const imgSrc = document.getElementById("qr-image").src;

    // نافذة للطباعة فقط تحتوي الصورة
    const printWindow = window.open('', '_blank', 'width=1100,height=1100');

    printWindow.document.write(`
        <html>
        <head>
            <title>طباعة QR</title>
            <style>
                @page { margin: 0; }

                body {
                    margin: 0;
                    padding: 0;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    background: #fff;
                }
                img {
                    width: 1000px;
                    height: 1000px;
                    object-fit: contain;
                }
            </style>
        </head>
        <body>
            <img src="${imgSrc}" />
        </body>
        </html>
    `);

    printWindow.document.close();

    // الانتظار حتى تحميل الصورة ثم الطباعة
    printWindow.onload = function () {
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    };
}

</script>
@endsection