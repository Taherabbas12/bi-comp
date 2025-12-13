@extends('layouts.employee-layout')

@section('title', 'سجل الحضور')

@section('styles')
<style>
    /* ===========================
       🎨 صفحة الحضور — Ultra Neon Pro
       =========================== */

    /* عنوان الصفحة */
    .attendance-title {
        font-size: 1.8rem;
        font-weight: 900;
        margin-bottom: .2rem;
    }

    .attendance-sub {
        font-size: 0.9rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }

    /* ========== أزرار الحضور ========== */
    .btn-checkin-xl,
    .btn-checkout-xl {
        width: 100%;
        padding: 1rem 1.2rem !important;
        border-radius: 1rem !important;
        font-size: 1.2rem !important;
        font-weight: 800 !important;
        display: flex;
        justify-content: center;
        gap: .5rem;
        border: none;
    }

    .btn-checkin-xl i,
    .btn-checkout-xl i {
        font-size: 1.4rem !important;
    }

    /* ========== كرت إجمالي الساعات ========== */
    .total-hours-card {
        background: rgba(15, 23, 42, .85);
        border-radius: 1.2rem;
        padding: 1rem 1.2rem;
        border: 2px solid rgba(59, 130, 246, .5);
        box-shadow: 0 18px 35px rgba(0, 0, 0, .55);
        margin-bottom: 1.2rem;
    }

    .total-hours-label {
        font-size: 1rem;
        margin-bottom: .2rem;
        color: #f1f5f9;
    }

    .total-hours-value {
        font-size: 1.6rem;
        font-weight: 900;
        color: #22c55e;
        text-shadow: 0 0 12px rgba(34, 197, 94, .6);
    }

    /* ========== كروت الجلسات ========== */
    /* --- 💥 لا حاجة لـ .sessions-grid و .attendance-session-card --- */

    /* ========== QR Modal ========== */
    #qrModal {
        position: fixed;
        inset: 0;
        background: rgba(2,6,23,0.88);
        display: none;
        align-items: center;
        justify-content: center;
        padding: 10px;
        z-index: 3000;
    }

    #qrModal.active { display: flex; }

    #qrModal .modal-content {
        background: #020617;
        border-radius: 1.2rem;
        padding: 1rem;
        width: 100%;
        max-width: 400px;
        border: 1px solid rgba(125, 180, 255, .5);
        box-shadow: 0 18px 45px rgba(0,0,0,.85);
    }

    #qr-reader {
        height: 280px;
        border-radius: 1rem;
        overflow: hidden;
    }
</style>
@endsection


@section('content')

{{-- عنوان الصفحة --}}
<div class="attendance-title">سجل الحضور لليوم</div>
<div class="attendance-sub">تاريخ: {{ now('Asia/Baghdad')->format('Y-m-d h:i A') }}</div>

{{-- أزرار الحضور --}}
<div class="row g-2 mb-3">
    <div class="col-12">
        <button class="btn-checkin-xl" onclick="openQrScanner('checkin')">
            <i class="bi bi-qr-code-scan"></i> تسجيل حضور
        </button>
    </div>
    <div class="col-12">
        <button class="btn-checkout-xl" onclick="openQrScanner('checkout')">
            <i class="bi bi-box-arrow-right"></i> تسجيل انصراف
        </button>
    </div>
</div>

{{-- إجمالي الساعات --}}
<div class="total-hours-card">
    <div class="total-hours-label">إجمالي ساعات اليوم</div>
    <div class="total-hours-value">{{ $totalHours }}</div>
</div>

{{-- شبكة جلسات الحضور --}}
<div class="row g-3"> <!-- 💥 استخدم Grid Bootstrap -->
    @forelse($sessions as $session)

        @php
            $isOpen  = !$session->check_out_at;
            $checkIn  = $session->check_in_at?->timezone('Asia/Baghdad');
            $checkOut = $session->check_out_at?->timezone('Asia/Baghdad');

            $diffMinutes = $session->check_out_at
                ? $session->check_in_at->diffInMinutes($session->check_out_at)
                : null;

            if ($isOpen && $checkIn) {
                $initialCurrent = now('Asia/Baghdad')->diff($checkIn)->format('%H:%I:%S');
            }
        @endphp

        <div class="col-12"> <!-- 💥 كل كرت يملأ العرض -->
            <div class="card text-white bg-dark border-light"> <!-- 💥 استخدم Bootstrap Card -->
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="card-title">الجلسة رقم {{ $loop->iteration }}</h5> <!-- 💥 عنوان الجلسة -->
                            <p class="card-text">
                                <i class="bi bi-calendar-event"></i>
                                <strong>التاريخ:</strong> {{ $checkIn?->format('Y/m/d') ?? '—' }} <!-- 💥 عرض التاريخ -->
                            </p>
                        </div>
                        <span class="badge {{ $isOpen ? 'bg-warning text-dark' : 'bg-success' }}"> <!-- 💥 شريط الحالة -->
                            {{ $isOpen ? 'قيد العمل' : 'مكتملة' }}
                        </span>
                    </div>

                    <div class="row mt-2">
                        <div class="col-6">
                            <p class="card-text">
                                <i class="bi bi-box-arrow-in-right text-success"></i>
                                <strong>الدخول:</strong> {{ $checkIn?->format('h:i A') ?? '—' }}
                            </p>
                        </div>
                        <div class="col-6">
                            <p class="card-text">
                                <i class="bi bi-box-arrow-right text-danger"></i>
                                <strong>الخروج:</strong> {{ $checkOut?->format('h:i A') ?? '—' }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-2">
                        <p class="card-text">
                            <i class="bi bi-clock-history text-info"></i>
                            <strong>المدة:</strong>
                            @if($diffMinutes)
                                @php
                                    $totalHours = $diffMinutes / 60;
                                    $hours = floor($totalHours);
                                    $minutes = $diffMinutes % 60;
                                @endphp
                                @if($hours >= 1)
                                    {{ $hours }} ساعة و {{ $minutes }} دقيقة
                                @else
                                    {{ $minutes }} دقيقة
                                @endif
                            @else
                                —
                            @endif
                        </p>
                    </div>

                    @if($isOpen && $checkIn)
                        <div class="mt-2">
                            <p class="card-text">
                                <i class="bi bi-fire text-warning"></i>
                                <strong>المدة الحالية:</strong>
                                <span id="live-{{ $session->id }}">{{ $initialCurrent }}</span>
                            </p>
                        </div>

                        <script>
                            (function(){
                                const el = document.getElementById("live-{{ $session->id }}");
                                const start = new Date("{{ $checkIn->format('Y-m-d H:i:s') }}".replace(" ", "T"));

                                setInterval(() => {
                                    const now = new Date();
                                    let diff = Math.floor((now - start) / 1000);
                                    let h = String(Math.floor(diff/3600)).padStart(2,'0');
                                    let m = String(Math.floor((diff % 3600)/60)).padStart(2,'0');
                                    let s = String(diff % 60).padStart(2,'0');
                                    el.textContent = `${h}:${m}:${s}`;
                                }, 1000);
                            })();
                        </script>
                    @endif

                </div>
            </div>
        </div>

    @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="bi bi-calendar-x" style="font-size: 3rem; color: #adb5bd;"></i>
                <p class="mt-3">لا توجد جلسات لهذا اليوم.</p>
            </div>
        </div>
    @endforelse
</div>

{{-- QR MODAL --}}
<div id="qrModal">
    <div class="modal-content text-white">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h4 id="scanTitle"></h4>
            <button class="btn btn-sm btn-danger" onclick="closeQrScanner()">إغلاق</button>
        </div>
        <div id="cameraLabel" class="text-muted mb-2">جارٍ تحميل الكاميرا...</div>
        <div id="qr-reader"></div>
    </div>
</div>

@endsection



@section('scripts')
<!-- ✅ تم إصلاح الرابط - إزالة المسافات الزائدة -->
<script src="https://unpkg.com/html5-qrcode"></script>
<script>

let html5QrCode = null;
let cameras = [];
let scanMode = "checkin";
let scanLock = false;

const qrModal = document.getElementById("qrModal");

function showQrModal(){ qrModal.classList.add('active'); }
function hideQrModal(){ qrModal.classList.remove('active'); }

function openQrScanner(mode){
    scanMode = mode;
    scanLock = false;

    document.getElementById("scanTitle").innerText =
        mode === "checkin" ? "مسح رمز الحضور" : "مسح رمز الانصراف";

    document.getElementById("cameraLabel").innerText = "جارٍ البحث عن الكاميرات...";

    showQrModal();

    if (!html5QrCode)
        html5QrCode = new Html5Qrcode("qr-reader");

    Html5Qrcode.getCameras().then(devices=>{
        cameras = devices;
        let back = devices.findIndex(d =>
            d.label.toLowerCase().includes("back") ||
            d.label.toLowerCase().includes("rear")
        );
        startCamera(devices[back !== -1 ? back : 0].id);
    }).catch(err => {
        console.error("خطأ في الوصول للكاميرات:", err);
        document.getElementById("cameraLabel").innerText = "فشل في الوصول للكاميرات.";
    });
}

function startCamera(id){
    html5QrCode.start(
        id,
        { fps:10, qrbox:260 },
        code => { if(!scanLock){ scanLock=true; handleQr(code); } }
    )
    .then(()=> document.getElementById("cameraLabel").innerText = "الكاميرا فعالة - قم بمسح الرمز")
    .catch(err => {
        console.error("خطأ في بدء الكاميرا:", err);
        document.getElementById("cameraLabel").innerText = "فشل في بدء الكاميرا";
    });
}

function closeQrScanner(){
    if(html5QrCode){
        html5QrCode.stop()
            .then(() => {
                hideQrModal();
                // إعادة تعيين الحالة للسماح بالمسح مرة أخرى
                scanLock = false;
            })
            .catch(hideQrModal);
    } else {
        hideQrModal();
    }
}

function handleQr(code){
    navigator.geolocation.getCurrentPosition(
        pos => send(code,pos.coords.latitude,pos.coords.longitude),
        ()  => send(code,null,null)
    );
}

function send(qr,lat,lng){
    const url = scanMode === "checkin"
        ? "{{ route('attendance.checkin.qr') }}"
        : "{{ route('attendance.checkout.qr') }}";

    fetch(url,{
        method:"POST",
        headers:{
            "Content-Type":"application/json",
            "X-CSRF-TOKEN":"{{ csrf_token() }}"
        },
        body:JSON.stringify({ qr_code:qr, lat, lng })
    })
    .then(r=>r.json())
    .then(res=>{
        alert(res.message);
        location.reload();
    })
    .catch(err => {
        console.error("خطأ في الإرسال:", err);
        alert("حدث خطأ أثناء إرسال البيانات.");
        scanLock = false; // فتح القفل للسماح بالمسح مجددًا
    });
}

</script>
@endsection