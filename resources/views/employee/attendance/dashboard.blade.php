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
    height: 280px;
    border-radius:12px;
    overflow: hidden;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}
#qr-reader > div {
    width: 230px !important;
    height: 230px !important;
    margin: auto;
}
#qr-reader video {
    object-fit: cover;
    width: 100%;
    height: 100%;
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
<div class="live-session">⏱ <span id="live"></span></div>
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
        <div id="qr-reader"></div>
        <button class="close-btn" onclick="closeQr()">إغلاق</button>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
let scanner=null,mode='checkin',cams=[],camIndex=0,locked=false;

function openQr(m){
    mode=m; locked=false;
    document.getElementById('qrTitle').innerText=m==='checkin'?'مسح رمز الحضور':'مسح رمز الانصراف';
    document.getElementById('qrModal').classList.add('active');

    setTimeout(() => {
        scanner=new Html5Qrcode("qr-reader");
        Html5Qrcode.getCameras().then(list=>{
            cams=list;
            camIndex=list.findIndex(c=>c.label.toLowerCase().includes('back'))!=-1
                ? list.findIndex(c=>c.label.toLowerCase().includes('back'))
                : 0;
            startCam();
        }).catch(err => {
            console.error("Error getting cameras:", err);
            alert("خطأ في الوصول للكاميرا");
        });
    }, 100);
}

function startCam(){
    const config = {
        fps: 10,
        qrbox: { width: 230, height: 230 },
        aspectRatio: 1.0 // Ensure square aspect ratio
    };

    scanner.start(
        cams[camIndex].id,
        config,
        code=>{
            if(locked) return;
            locked=true;
            scanner.stop().then(()=>send(code));
        }
    ).catch(err => {
        console.error("Error starting camera:", err);
        alert("خطأ في بدء الكاميرا");
    });
}

function switchCamera(){
    if(!scanner || cams.length<2) return;
    scanner.stop().then(()=>{
        camIndex=(camIndex+1)%cams.length;
        startCam();
    });
}

function closeQr(){
    if(scanner) {
        scanner.stop().catch(()=>{}).finally(() => {
            document.getElementById('qrModal').classList.remove('active');
        });
    } else {
        document.getElementById('qrModal').classList.remove('active');
    }
}

function send(qr){
    navigator.geolocation.getCurrentPosition(pos=>{
        fetch(
            mode==='checkin'
            ? '{{ route("attendance.checkin.qr") }}'
            : '{{ route("attendance.checkout.qr") }}',
            {
                method:'POST',
                headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
                body:JSON.stringify({qr_code:qr,lat:pos.coords.latitude,lng:pos.coords.longitude})
            }
        ).then(r=>r.json()).then(r=>{
            alert(r.message);
            location.reload();
        }).catch(err => {
            console.error("Error sending data:", err);
            alert("خطأ في الاتصال");
            locked = false;
        });
    }, () => {
        alert("يرجى السماح بتحديد الموقع الجغرافي");
        locked = false;
    });
}

@if($open)
(function(){
    const el=document.getElementById('live');
    const start=new Date("{{ $open->check_in_at }}".replace(" ","T"));

    function updateLiveTime() {
        const now = new Date();
        let diffSeconds = Math.floor((now - start) / 1000);

        // Calculate hours, minutes, seconds
        const hours = Math.floor(diffSeconds / 3600);
        diffSeconds %= 3600;
        const minutes = Math.floor(diffSeconds / 60);
        const seconds = diffSeconds % 60;

        // Format as HH:MM:SS
        const formatted =
            String(hours).padStart(2, '0') + ':' +
            String(minutes).padStart(2, '0') + ':' +
            String(seconds).padStart(2, '0');

        el.innerText = formatted;
    }

    updateLiveTime(); // Initial call
    setInterval(updateLiveTime, 1000);
})();
@endif
</script>
@endsection
