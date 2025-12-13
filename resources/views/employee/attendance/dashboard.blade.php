@extends('layouts.employee-layout')
@section('title','سجل الحضور الشهري')

<style>
:root{
    --bg:#020617;
    --card:#0f172a;
    --border:rgba(148,163,184,.25);
    --green:#22c55e;
    --red:#ef4444;
    --blue:#60a5fa;
    --yellow:#facc15;
    --purple:#a78bfa;
    --text:#e5e7eb;
}

body{
    background:var(--bg);
    color:var(--text);
    font-family:'Segoe UI',Tahoma;
}

/* ===== Header ===== */
.page-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:24px;
}
.page-title{
    font-size:2rem;
    font-weight:900;
    background:linear-gradient(135deg,#60a5fa,#a78bfa);
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
}
.action-buttons{display:flex;gap:10px;}
.action-buttons button{
    padding:10px 18px;
    border-radius:12px;
    border:none;
    font-weight:800;
    cursor:pointer;
    color:#fff;
}
.btn-in{background:linear-gradient(135deg,#22c55e,#16a34a);}
.btn-out{background:linear-gradient(135deg,#ef4444,#dc2626);}

/* ===== Stats ===== */
.stats-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
    gap:16px;
    margin-bottom:30px;
}
.stat-card{
    background:var(--card);
    border:1px solid var(--border);
    border-radius:18px;
    padding:18px;
    text-align:center;
    box-shadow:0 20px 40px rgba(0,0,0,.6);
}
.stat-title{font-size:.9rem;color:#cbd5f5;}
.stat-value{font-size:2.1rem;font-weight:900;margin:6px 0;}
.green{color:var(--green);}
.blue{color:var(--blue);}
.red{color:var(--red);}

/* ===== Table ===== */
.calendar-wrapper{overflow-x:auto;}
.attendance-table{
    width:100%;
    border-collapse:collapse;
    table-layout:fixed;
}
.attendance-table th,
.attendance-table td{
    border:1px solid var(--border);
    padding:10px;
    text-align:center;
}
.attendance-table th{
    background:#020617;
    font-weight:900;
}
.attendance-table td{
    height:130px;
    background:var(--card);
    vertical-align:top;
}
.other{opacity:.35;}
.today{outline:2px solid var(--yellow);}
.has{outline:2px solid var(--green);}
.no{outline:2px solid rgba(239,68,68,.5);}
.day-number{font-size:1.4rem;font-weight:900;color:#93c5fd;}
.duration{color:var(--green);font-weight:800;margin-top:4px;}
.no-mark{font-size:1.6rem;color:var(--red);}
.live-session{margin-top:6px;font-size:.8rem;color:var(--yellow);font-weight:800;}

/* ===== QR MODAL ===== */
#qrModal{
    position:fixed;
    inset:0;
    background:rgba(2,6,23,.9);
    display:none;
    align-items:center;
    justify-content:center;
    z-index:9999;
}
#qrModal.active{display:flex;}
.qr-box{
    background:#020617;
    padding:20px;
    border-radius:20px;
    width:90%;
    max-width:420px;
    border:1px solid var(--border);
    position: relative; /* Needed for overlay positioning */
}
.qr-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:10px;
}
.switch-btn{
    background:#334155;
    border:none;
    color:#fff;
    padding:6px 10px;
    border-radius:8px;
    cursor:pointer;
}
#qr-reader{
    width: 100%;
    height: 300px; /* Increased height for better square aspect ratio */
    position: relative;
    overflow: hidden;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #000; /* Black background for video */
}
#qr-reader > div {
    width: 250px !important; /* Set fixed width for QR box */
    height: 250px !important; /* Set fixed height for QR box */
    position: relative; /* Relative for overlay positioning */
    margin: auto; /* Center the box */
}
#qr-reader video {
    object-fit: cover;
    width: 100%;
    height: 100%;
    display: block;
}
/* Overlay for scanning area */
#qr-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none; /* Allow clicks through to video */
    box-shadow: inset 0 0 0 1000px rgba(0, 0, 0, 0.5); /* Darken outside the scan area */
}
#qr-overlay::before,
#qr-overlay::after {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    border-color: var(--green);
    border-style: solid;
}
/* Top-left corner */
#qr-overlay::before {
    top: calc(50% - 125px); /* Half of 250px minus corner length */
    left: calc(50% - 125px);
    border-width: 0 0 3px 3px;
}
/* Top-right corner */
#qr-overlay::after {
    top: calc(50% - 125px);
    right: calc(50% - 125px);
    border-width: 0 3px 3px 0;
}
#qr-reader > div::before,
#qr-reader > div::after {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    border-color: var(--green);
    border-style: solid;
}
/* Bottom-right corner */
#qr-reader > div::after {
    bottom: 0;
    right: 0;
    border-width: 0 3px 3px 0;
}
/* Bottom-left corner */
#qr-reader > div::before {
    bottom: 0;
    left: 0;
    border-width: 3px 0 0 3px;
}
.close-btn{
    margin-top:12px;
    width:100%;
    padding:10px;
    border:none;
    border-radius:10px;
    background:#ef4444;
    color:#fff;
    font-weight:800;
    cursor:pointer;
}
</style>

@section('content')

<div class="page-header">
    <h1 class="page-title">📅 سجل الحضور الشهري</h1>
    <div class="action-buttons">
        <button class="btn-in" onclick="openQr('checkin')">✅ حضور</button>
        <button class="btn-out" onclick="openQr('checkout')">🚪 انصراف</button>
    </div>
</div>

{{-- ===== Stats ===== --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-title">⏱ ساعات الشهر</div>
        <div class="stat-value green">{{ number_format($monthlyTotalHours,1) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-title">✅ أيام الحضور</div>
        <div class="stat-value blue">{{ $daysPresent }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-title">❌ أيام الغياب</div>
        <div class="stat-value red">{{ $daysAbsent }}</div>
    </div>
</div>

{{-- ===== Table ===== --}}
<div class="calendar-wrapper">
@php
    $day=$startOfMonth->copy();
    $today=now()->toDateString();
    $open=$openSessions->first();
@endphp

<table class="attendance-table">
<thead>
<tr>
    <th>الأحد</th><th>الإثنين</th><th>الثلاثاء</th>
    <th>الأربعاء</th><th>الخميس</th><th>الجمعة</th><th>السبت</th>
</tr>
</thead>
<tbody>
@while($day <= $endOfMonth)
<tr>
@for($i=0;$i<7;$i++)
@php
$d=$day->copy()->addDays($i);
$date=$d->toDateString();
$info=$dailyHours[$date]??['total'=>0,'isCurrentMonth'=>false,'hasAttendance'=>false];
$h=floor($info['total']); $m=round(($info['total']-$h)*60);
@endphp
<td class="{{ !$info['isCurrentMonth']?'other':'' }} {{ $date==$today?'today':'' }} {{ $info['hasAttendance']?'has':'no' }}">
@if($info['isCurrentMonth'])
<div class="day-number">{{ $d->format('d') }}</div>
@if($info['hasAttendance'])
<div class="duration">{{ $h }}س {{ $m }}د</div>
@else
<div class="no-mark">❌</div>
@endif
@if($open && $date==$today)
<div class="live-session">⏱ <span id="live">00:00:00</span></div>
@endif
@endif
</td>
@endfor
</tr>
@php $day->addWeek(); @endphp
@endwhile
</tbody>
</table>
</div>

{{-- ===== QR MODAL ===== --}}
<div id="qrModal">
    <div class="qr-box">
        <div class="qr-header">
            <h3 id="qrTitle"></h3>
            <button class="switch-btn" onclick="switchCamera()">🔄 تبديل</button>
        </div>
        <div id="qr-reader">
            <div id="qr-overlay"></div>
        </div>
        <button class="close-btn" onclick="closeQr()">إغلاق</button>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
let scanner = null, mode = 'checkin', cams = [], camIndex = 0, locked = false;

// Function to format seconds into HH:MM:SS
function formatTime(seconds) {
    const h = Math.floor(seconds / 3600);
    const m = Math.floor((seconds % 3600) / 60);
    const s = seconds % 60;
    return [h, m, s]
        .map(v => v.toString().padStart(2, '0'))
        .join(':');
}

function openQr(m) {
    mode = m; locked = false;
    document.getElementById('qrTitle').innerText = m === 'checkin' ? 'مسح رمز الحضور' : 'مسح رمز الانصراف';
    document.getElementById('qrModal').classList.add('active');

    // Delay initialization to ensure DOM is fully rendered
    setTimeout(() => {
        if (!document.getElementById('qr-reader')) {
            console.error("QR reader element not found!");
            return;
        }
        scanner = new Html5Qrcode("qr-reader");

        Html5Qrcode.getCameras().then(list => {
            cams = list;
            // Prefer back camera
            camIndex = list.findIndex(c => c.label.toLowerCase().includes('back')) !== -1
                ? list.findIndex(c => c.label.toLowerCase().includes('back'))
                : 0;
            startCam();
        }).catch(err => {
            console.error("Error getting cameras:", err);
            alert("خطأ في الوصول إلى الكاميرا: " + err.message);
        });
    }, 100);
}

function startCam() {
    const config = {
        fps: 10,
        qrbox: { width: 250, height: 250 }, // Match the CSS dimensions
        aspectRatio: 1.0 // Enforce square aspect ratio for detection
    };

    if (cams.length === 0) {
        console.error("No cameras available.");
        alert("لا توجد كاميرات متاحة.");
        return;
    }

    const selectedCameraId = cams[camIndex].id;
    scanner.start(
        selectedCameraId,
        config,
        (decodedText, decodedResult) => {
            if (locked) return;
            locked = true;
            console.log("QR Code scanned: ", decodedText);
            scanner.stop().then(() => {
                send(decodedText);
            }).catch(err => {
                console.error("Error stopping scanner: ", err);
                // Still try to send the data even if stopping fails
                send(decodedText);
            });
        }
    ).catch(err => {
        console.error("Error starting camera: ", err);
        alert("خطأ في بدء الكاميرا: " + err.message);
        closeQr(); // Close modal on error
    });
}

function switchCamera() {
    if (!scanner || cams.length < 2) return;

    scanner.stop().then(() => {
        camIndex = (camIndex + 1) % cams.length;
        startCam();
    }).catch(err => {
        console.error("Error switching camera: ", err);
        // Fallback: just try to restart with the same camera
        startCam();
    });
}

function closeQr() {
    if (scanner) {
        scanner.stop().catch(() => {
            // Ignore errors when stopping during close
        }).finally(() => {
            document.getElementById('qrModal').classList.remove('active');
            locked = false; // Reset lock state when closing
        });
    } else {
        document.getElementById('qrModal').classList.remove('active');
    }
}

function send(qr) {
    // Request geolocation
    navigator.geolocation.getCurrentPosition(
        pos => {
            fetch(
                mode === 'checkin' ? '{{ route("attendance.checkin.qr") }}' : '{{ route("attendance.checkout.qr") }}',
                {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        qr_code: qr,
                        lat: pos.coords.latitude,
                        lng: pos.coords.longitude
                    })
                }
            )
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                location.reload();
            })
            .catch(error => {
                console.error('Fetch Error:', error);
                alert('حدث خطأ أثناء الاتصال.');
                locked = false; // Unlock in case of error
            });
        },
        error => {
            console.error("Geolocation error:", error);
            alert("يرجى السماح بتحديد الموقع الجغرافي للحضور/الانصراف.");
            // Optionally still send without location if allowed by backend
            // For now, we'll just unlock and let the user know.
            locked = false;
        }
    );
}

// Handle live session timer
@if($open)
(function() {
    const liveSessionElement = document.getElementById('live');
    if (!liveSessionElement) return; // Exit if element doesn't exist

    const startTime = new Date("{{ $open->check_in_at }}".replace(" ", "T")).getTime();
    const updateTimer = () => {
        const currentTime = new Date().getTime();
        const elapsedSeconds = Math.floor((currentTime - startTime) / 1000);
        liveSessionElement.textContent = formatTime(elapsedSeconds);
    };

    updateTimer(); // Initial update
    const intervalId = setInterval(updateTimer, 1000);

    // Optional: Clear interval if needed later (e.g., on page unload)
    window.addEventListener('beforeunload', () => {
        clearInterval(intervalId);
    });

})();
@endif

</script>
@endsection
