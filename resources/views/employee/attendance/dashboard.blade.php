@extends('layouts.employee-layout')
@section('title', 'لوحة الحضور')

<style>
    /* ===== CSS Variables ===== */
    :root {
        --bg: #020617;
        --card: #0f172a;
        --border: rgba(148, 163, 184, .25);
        --green: #22c55e;
        --red: #ef4444;
        --blue: #60a5fa;
        --yellow: #facc15;
        --purple: #a78bfa;
        --text: #e5e7eb;
        --text-secondary: #94a3b8;
        --header-height: 60px;
        --bottom-nav-height: 60px;
        --primary-btn-bg: linear-gradient(135deg, var(--green), #16a34a);
        --secondary-btn-bg: linear-gradient(135deg, var(--red), #dc2626);
        --action-btn-bg: linear-gradient(135deg, var(--blue), var(--purple));
        --forgotten-btn-bg: linear-gradient(135deg, #f59e0b, #d97706);
    }

    body {
        background: var(--bg);
        color: var(--text);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
        padding-bottom: var(--bottom-nav-height);
        direction: rtl;
        overflow-x: hidden;
    }

    .header {
        background: var(--card);
        padding: 12px 16px;
        position: sticky;
        top: 0;
        z-index: 100;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-bottom: 1px solid var(--border);
    }

    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, var(--blue), var(--purple));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
    }

    .month-selector {
        background: var(--bg);
        border: 1px solid var(--border);
        color: var(--text);
        border-radius: 8px;
        padding: 6px 10px;
        font-size: 0.9rem;
    }

    .stats-container {
        padding: 16px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 12px;
    }

    .stat-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 12px;
        text-align: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: transform 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
    }

    .stat-title {
        font-size: 0.8rem;
        color: var(--text-secondary);
        margin-bottom: 4px;
    }

    .stat-value {
        font-size: 1.8rem;
        font-weight: 800;
        margin: 0;
    }

    .stat-value.green {
        color: var(--green);
    }

    .stat-value.blue {
        color: var(--blue);
    }

    .stat-value.red {
        color: var(--red);
    }

    .forgotten-alert {
        background: var(--card);
        border: 1px solid var(--yellow);
        border-radius: 12px;
        padding: 12px 16px;
        margin: 0 16px 16px 16px;
        text-align: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }

    .forgotten-alert h3 {
        color: var(--yellow);
        margin: 0;
        font-size: 1.1rem;
    }

    .forgotten-alert p {
        margin: 0;
        font-size: 0.9rem;
        color: var(--text-secondary);
    }

    .calendar-container {
        padding: 0 16px 16px 16px;
    }

    .calendar-header {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        background: var(--bg);
        border-bottom: 1px solid var(--border);
        padding: 8px 0;
    }

    .calendar-header-day {
        text-align: center;
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--text-secondary);
    }

    .calendar-body {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 8px;
    }

    .calendar-day {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 8px;
        min-height: 80px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        text-align: center;
        transition: transform 0.2s ease;
        position: relative;
    }

    .calendar-day:hover {
        transform: scale(1.02);
        background: #1e293b;
        z-index: 2;
    }

    .day-number {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--blue);
        margin-bottom: 4px;
    }

    .duration {
        color: var(--green);
        font-weight: 600;
        font-size: 0.9rem;
        margin-top: 2px;
    }

    .no-mark {
        color: var(--red);
        font-size: 1.2rem;
    }

    .live-session {
        margin-top: 4px;
        font-size: 0.8rem;
        color: var(--yellow);
        font-weight: 600;
        background: rgba(250, 204, 21, 0.1);
        padding: 2px 4px;
        border-radius: 4px;
    }

    .today {
        outline: 2px solid var(--yellow);
        outline-offset: -2px;
    }

    .has-attendance {
        outline: 2px solid var(--green);
        outline-offset: -2px;
    }

    .other-month {
        opacity: 0.4;
    }

    /* =============================
       🪄 HOVER TOOLTIP (NEW)
    ============================= */
    .attendance-tooltip {
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 10px;
        width: 220px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.6);
        z-index: 10;
        display: none;
        direction: rtl;
        font-size: 0.9rem;
        backdrop-filter: blur(8px);
        margin-bottom: 8px;
    }

    .calendar-day:hover .attendance-tooltip {
        display: block;
    }

    .tooltip-row {
        display: flex;
        justify-content: space-between;
        margin: 4px 0;
    }

    .tooltip-label {
        color: var(--text-secondary);
        font-weight: 500;
    }

    .tooltip-value {
        color: var(--text);
        font-weight: 600;
    }

    .tooltip-value.in {
        color: var(--green);
    }

    .tooltip-value.out {
        color: var(--red);
    }

    .tooltip-divider {
        height: 1px;
        background: var(--border);
        margin: 6px 0;
    }

    /* Modal & Bottom Nav (unchanged) */
    .modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(2, 6, 23, 0.95);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 10000;
        padding: 20px;
        box-sizing: border-box;
    }

    .modal.active {
        display: flex;
    }

    .modal-content {
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: 16px;
        width: 100%;
        max-width: 450px;
        padding: 20px;
        box-sizing: border-box;
        position: relative;
        display: flex;
        flex-direction: column;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .modal-title {
        font-size: 1.2rem;
        font-weight: 700;
        margin: 0;
    }

    .switch-btn {
        background: #334155;
        border: none;
        color: var(--text);
        padding: 6px 10px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.9rem;
    }

    .qr-scanner-container {
        width: 100%;
        max-width: 300px;
        height: 300px;
        margin: 0 auto 16px;
        position: relative;
        overflow: hidden;
        border-radius: 12px;
        background-color: #000;
    }

    #qr-reader {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #qr-reader video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .qr-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        box-shadow: inset 0 0 0 1000px rgba(0, 0, 0, 0.5);
    }

    .qr-overlay::before,
    .qr-overlay::after,
    .qr-overlay-bottom-right,
    .qr-overlay-bottom-left {
        content: '';
        position: absolute;
        width: 20px;
        height: 20px;
        border-color: var(--green);
        border-style: solid;
    }

    .qr-overlay::before {
        top: calc(50% - 150px);
        left: calc(50% - 150px);
        border-width: 0 0 3px 3px;
    }

    .qr-overlay::after {
        top: calc(50% - 150px);
        right: calc(50% - 150px);
        border-width: 0 3px 3px 0;
    }

    .qr-overlay-bottom-right {
        bottom: 0;
        right: 0;
        border-width: 3px 0 0 3px;
    }

    .qr-overlay-bottom-left {
        bottom: 0;
        left: 0;
        border-width: 0 3px 3px 0;
    }

    .close-btn {
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: 10px;
        background: var(--secondary-btn-bg);
        color: white;
        font-weight: 700;
        cursor: pointer;
        font-size: 1rem;
    }

    /* فيديو النجاح — الفيديو فقط فوق كل الواجهات */
    #successVideoModal {
        z-index: 2147483647;
        background: #000;
        padding: 0;
        margin: 0;
        align-items: center;
        justify-content: center;
    }
    #successVideoModal.success-video-modal.active {
        display: flex !important;
    }
    body.success-video-open {
        overflow: hidden;
        position: fixed;
        width: 100%;
        height: 100%;
    }
    .success-video-full {
        width: 100%;
        height: 100%;
        object-fit: contain;
        display: block;
    }

    /* =============================
       ⬆️ TOP ACTION BUTTONS
    ============================= */
    .header-actions {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .header-btn {
        padding: 8px 14px;
        border-radius: 10px;
        border: none;
        font-weight: 700;
        font-size: 0.85rem;
        color: white;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: transform .15s ease, box-shadow .15s ease;
    }

    .header-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, .3);
    }

    .header-btn-checkin {
        background: var(--primary-btn-bg);
    }

    .header-btn-checkout {
        background: var(--secondary-btn-bg);
    }

    @media (max-width: 768px) {
        .header {
            flex-direction: column;
            align-items: stretch;
            gap: 10px;
        }

        .header-actions {
            justify-content: space-between;
        }

        .header-btn {
            flex: 1;
            justify-content: center;
            padding: 10px;
            font-size: 0.9rem;
        }
    }

    @media (min-width: 768px) {
        body {
            padding-bottom: 0;
        }

        .calendar-day {
            min-height: 100px;
            padding: 12px;
        }

        .bottom-nav {
            display: none;
        }

        .header {
            padding: 16px 24px;
        }

        .stats-container,
        .calendar-container {
            padding: 16px 24px;
        }

        .page-title {
            font-size: 1.8rem;
        }

        .stat-card {
            padding: 16px;
        }

        .stat-value {
            font-size: 2rem;
        }
    }
</style>

@section('content')
    <!-- Header -->
    <div class="header">
        <h1 class="page-title">📅 لوحة الحضور</h1>

        <div class="header-actions">
            <button class="header-btn header-btn-checkin" onclick="openQr('checkin')">
                ✅ حضور
            </button>

            <button class="header-btn header-btn-checkout" onclick="openQr('checkout')">
                🚪 انصراف
            </button>

            <select class="month-selector" id="monthSelector" onchange="changeMonth(this.value)">
                @for ($i = -6; $i <= 6; $i++)
                    @php
                        $date = now()->addMonths($i)->startOfMonth();
                        $formatted = $date->format('Y-m');
                    @endphp
                    <option value="{{ $formatted }}" {{ $currentMonth->format('Y-m') == $formatted ? 'selected' : '' }}>
                        {{ $date->translatedFormat('F Y') }}
                    </option>
                @endfor
            </select>
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-container">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-title">⏱ ساعات الشهر</div>
                <div class="stat-value green">{{ number_format($monthlyTotalHours, 1) }}</div>
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
    </div>

    <!-- Forgotten Session Alert -->
    @if ($forgottenSession)
        <div class="forgotten-alert">
            <h3>⚠️ جلسة مفتوحة من يوم سابق!</h3>
            <p>تم العثور على جلسة حضور مفتوحة منذ:
                <strong>{{ $forgottenSession->check_in_at->format('Y-m-d H:i A') }}</strong>
            </p>
            <button class="nav-btn nav-btn-forgotten" onclick="closeForgottenSession()">
                <i>✅</i> إغلاق الجلسة المنسية
            </button>
        </div>
    @endif

    <!-- Calendar View -->
    <div class="calendar-container">
        <div class="calendar-header">
            <div class="calendar-header-day">الأحد</div>
            <div class="calendar-header-day">الإثنين</div>
            <div class="calendar-header-day">الثلاثاء</div>
            <div class="calendar-header-day">الأربعاء</div>
            <div class="calendar-header-day">الخميس</div>
            <div class="calendar-header-day">الجمعة</div>
            <div class="calendar-header-day">السبت</div>
        </div>
        <div class="calendar-body">
            @php
                $day = $startOfMonth->copy();
                $today = now()->toDateString();
            @endphp
            @while ($day <= $endOfMonth)
                @for ($i = 0; $i < 7; $i++)
                    @php
                        $d = $day->copy()->addDays($i);
                        $date = $d->toDateString();
                        $info = $dailyHours[$date] ?? [
                            'total' => 0,
                            'isCurrentMonth' => false,
                            'hasAttendance' => false,
                            'check_in_at' => null,
                            'check_out_at' => null,
                            'location_in' => null,
                            'location_out' => null,
                        ];
                        $h = floor($info['total']);
                        $m = round(($info['total'] - $h) * 60);
                    @endphp
                    <div
                        class="calendar-day
                        {{ !$info['isCurrentMonth'] ? 'other-month' : '' }}
                        {{ $date == $today ? 'today' : '' }}
                        {{ $info['hasAttendance'] ? 'has-attendance' : '' }}">
                        @if ($info['isCurrentMonth'])
                            <div class="day-number">{{ $d->format('d') }}</div>
                            @if ($info['hasAttendance'])
                                <div class="duration">{{ $h }}س {{ $m }}د</div>
                            @else
                                <div class="no-mark">❌</div>
                            @endif
                            @if ($currentOpenSession && $date == $today)
                                <div class="live-session">⏱ <span id="live-{{ $today }}">00:00:00</span></div>
                            @endif
                        @else
                            <div class="day-number">{{ $d->format('d') }}</div>
                        @endif

                        {{-- Tooltip - يظهر فقط عند التمرير على يوم فيه حضور --}}
                        @if ($info['hasAttendance'])
                            <div class="attendance-tooltip">
                                <div class="tooltip-row">
                                    <span class="tooltip-label">الدخول:</span>
                                    <span
                                        class="tooltip-value in">{{ $info['check_in_at'] ? $info['check_in_at']->format('h:i A') : '—' }}</span>
                                </div>
                                <div class="tooltip-row">
                                    <span class="tooltip-label">الموقع:</span>
                                    <span class="tooltip-value">{{ $info['location_in'] ?? 'غير متوفر' }}</span>
                                </div>
                                <div class="tooltip-divider"></div>
                                <div class="tooltip-row">
                                    <span class="tooltip-label">الخروج:</span>
                                    <span
                                        class="tooltip-value out">{{ $info['check_out_at'] ? $info['check_out_at']->format('h:i A') : 'لم يخرج بعد' }}</span>
                                </div>
                                @if ($info['check_out_at'])
                                    <div class="tooltip-row">
                                        <span class="tooltip-label">الموقع:</span>
                                        <span class="tooltip-value">{{ $info['location_out'] ?? 'غير متوفر' }}</span>
                                    </div>
                                @endif
                                <div class="tooltip-divider"></div>
                                <div class="tooltip-row">
                                    <span class="tooltip-label">المدة:</span>
                                    <span class="tooltip-value">{{ $h }}س {{ $m }}د</span>
                                </div>
                            </div>
                        @endif
                    </div>
                @endfor
                @php $day->addWeek(); @endphp
            @endwhile
        </div>
    </div>

    <!-- QR Modal -->
    <div id="qrModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="qrModalTitle">مسح رمز الحضور</h3>
                <button class="switch-btn" onclick="switchCamera()">🔄 تبديل</button>
            </div>
            <div class="qr-scanner-container">
                <div id="qr-reader">
                    <div class="qr-overlay">
                        <div class="qr-overlay-bottom-right"></div>
                        <div class="qr-overlay-bottom-left"></div>
                    </div>
                </div>
            </div>
            <button class="close-btn" onclick="closeQr()">إغلاق</button>
        </div>
    </div>

    <!-- فيديو النجاح — الفيديو فقط بدون تفاصيل -->
    <div id="successVideoModal" class="modal success-video-modal" onclick="closeSuccessVideo()">
        <video id="successVideo" class="success-video-full" playsinline muted preload="auto" onclick="event.stopPropagation();"></video>
    </div>

@endsection

@section('scripts')
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        let scanner = null,
            mode = 'checkin',
            cams = [],
            camIndex = 0,
            locked = false;

        function formatTime(seconds) {
            const h = Math.floor(seconds / 3600);
            const m = Math.floor((seconds % 3600) / 60);
            const s = seconds % 60;
            return [h, m, s].map(v => v.toString().padStart(2, '0')).join(':');
        }

        function openQr(m) {
            mode = m;
            locked = false;
            document.getElementById('qrModalTitle').innerText = m === 'checkin' ? 'مسح رمز الحضور' : 'مسح رمز الانصراف';
            document.getElementById('qrModal').classList.add('active');

            setTimeout(() => {
                if (!document.getElementById('qr-reader')) return;
                scanner = new Html5Qrcode("qr-reader");

                Html5Qrcode.getCameras().then(list => {
                    cams = list;
                    camIndex = list.findIndex(c => c.label.toLowerCase().includes('back')) !== -1 ?
                        list.findIndex(c => c.label.toLowerCase().includes('back')) :
                        0;
                    startCam();
                }).catch(err => {
                    alert("خطأ في الوصول إلى الكاميرا: " + (err.message || err));
                });
            }, 100);
        }

        function startCam() {
            if (cams.length === 0) {
                alert("لا توجد كاميرات متاحة.");
                return;
            }

            const config = {
                fps: 10,
                qrbox: {
                    width: 240,
                    height: 240
                },
                aspectRatio: 1.0
            };
            const selectedCameraId = cams[camIndex].id;

            scanner.start(selectedCameraId, config, (decodedText) => {
                if (locked) return;
                locked = true;
                scanner.stop().then(() => send(decodedText)).catch(() => send(decodedText));
            }).catch(err => {
                alert("خطأ في بدء الكاميرا: " + (err.message || err));
                closeQr();
            });
        }

        function switchCamera() {
            if (!scanner || cams.length < 2) return;
            scanner.stop().then(() => {
                camIndex = (camIndex + 1) % cams.length;
                startCam();
            });
        }

        function closeQr() {
            if (scanner) {
                scanner.stop().finally(() => {
                    document.getElementById('qrModal').classList.remove('active');
                    locked = false;
                });
            } else {
                document.getElementById('qrModal').classList.remove('active');
            }
        }

        function showSuccessVideo() {
            document.getElementById('qrModal').classList.remove('active');
            const modal = document.getElementById('successVideoModal');
            const video = document.getElementById('successVideo');
            const videoUrl = "{{ asset('attendance-success.mp4') }}";
            document.body.classList.add('success-video-open');
            modal.classList.add('active');
            video.onended = () => { closeSuccessVideo(); };
            video.onerror = () => { closeSuccessVideo(); };
            if (video.src !== videoUrl) {
                video.src = videoUrl;
                video.load();
            }
            video.currentTime = 0;
            video.muted = true;
            function doPlay() {
                video.play().catch(function() { setTimeout(closeSuccessVideo, 500); });
            }
            if (video.readyState >= 2) {
                doPlay();
            } else {
                video.addEventListener('canplay', doPlay, { once: true });
                video.addEventListener('error', function() { setTimeout(closeSuccessVideo, 500); }, { once: true });
            }
        }

        function closeSuccessVideo() {
            const modal = document.getElementById('successVideoModal');
            const video = document.getElementById('successVideo');
            video.pause();
            video.onended = null;
            modal.classList.remove('active');
            document.body.classList.remove('success-video-open');
            location.reload();
        }

        function send(qr) {
            navigator.geolocation.getCurrentPosition(
                pos => {
                    fetch(mode === 'checkin' ? '{{ route('attendance.checkin.qr') }}' :
                            '{{ route('attendance.checkout.qr') }}', {
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
                            })
                        .then(r => r.json())
                        .then(data => {
                            if (data.status && mode === 'checkin') {
                                showSuccessVideo();
                            } else {
                                alert(data.message);
                                if (data.status) location.reload();
                                else locked = false;
                            }
                        })
                        .catch(() => {
                            alert('حدث خطأ أثناء الاتصال.');
                            locked = false;
                        });
                },
                () => {
                    alert("يرجى السماح بتحديد الموقع الجغرافي.");
                    locked = false;
                }
            );
        }

        // Live session timer
        @if ($currentOpenSession)
            (function() {
                const el = document.getElementById('live-{{ $today }}');
                if (!el) return;
                const start = new Date("{{ $currentOpenSession->check_in_at->toIso8601String() }}").getTime();
                const update = () => {
                    const now = new Date().getTime();
                    const sec = Math.floor((now - start) / 1000);
                    el.textContent = formatTime(sec);
                };
                update();
                setInterval(update, 1000);
            })();
        @endif

        function closeForgottenSession() {
            if (!confirm("هل أنت متأكد أنك تريد إغلاق الجلسة المنسية؟")) return;
            fetch('{{ route('attendance.handle_forgotten') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    alert(data.message);
                    if (data.status) location.reload();
                })
                .catch(() => alert('حدث خطأ أثناء إغلاق الجلسة.'));
        }

        function changeMonth(month) {
            window.location.href = '{{ route('attendance.dashboard') }}?month=' + month;
        }
    </script>
@endsection
