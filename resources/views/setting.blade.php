@extends('layouts.app')

@section('content')
<style>
.setting-main { overflow-y: hidden; align-self: stretch; }
.s-row { display: flex; gap: 20px; margin-top: 8px; align-items: flex-start;}
.s-card { flex: 1; background: #fff; border: 2px solid #ddd; border-radius: 15px; padding: 20px; height: 100%; box-sizing: border-box; }
.s-card h3 { font-size: 14px; font-weight: 700; font-family: 'Poppins', sans-serif; margin-bottom: 8px; color: #1a1a1a; }
.s-radio-group { display: flex; gap: 30px; margin-bottom: 15px; }
.s-radio-label { display: flex; align-items: center; gap: 8px; font-size: 13px; font-family: 'Poppins', sans-serif; cursor: pointer; }
.s-radio-label input[type="radio"] { accent-color: #16A34A; width: 16px; height: 16px; }
.s-speed-label { font-size: 13px; font-weight: 600; font-family: 'Poppins', sans-serif; margin-bottom: 20px; color: #000; }
.s-slider-group { display: flex; align-items: center; gap: 12px; }
.s-slider-group input[type="range"] { flex: 1; accent-color: #16A34A; cursor: pointer; }
.s-slider-group span { font-size: 13px; font-weight: 600; color: #000; min-width: 25px; }
.s-toggle { position: relative; display: inline-block; width: 44px; height: 24px; flex-shrink: 0; }
.s-toggle input { opacity: 0; width: 0; height: 0; }
.s-toggle-bar { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background: #ccc; border-radius: 24px; transition: 0.3s; }
.s-toggle-bar::before { content: ""; position: absolute; width: 18px; height: 18px; left: 3px; bottom: 3px; background: #fff; border-radius: 50%; transition: 0.3s; }
.s-toggle input:checked + .s-toggle-bar { background: #16A34A; }
.s-toggle input:checked + .s-toggle-bar::before { transform: translateX(20px); }
.s-schedule-row { display: flex; gap: 20px; margin-bottom: 20px; }
.s-schedule-item { display: flex; align-items: center; gap: 10px; }
.s-schedule-name { font-size: 12px; font-weight: 700; font-family: 'Poppins', sans-serif; color: #1a1a1a; margin: 0; }
.s-schedule-time { font-size: 10px; color: #787A91; margin: 0; }
.s-add-btn { width: 100%; padding: 7px; border: 2px solid #16A34A; border-radius: 10px; background: #fff; color: #16A34A; font-size: 12px; font-weight: 600; cursor: pointer; }
.s-add-btn:hover { background: #f0fdf4; }
.s-notif-item { display: flex; justify-content: space-between; align-items: center; background: #f0f4f8; border-radius: 10px; padding: 10px 12px; margin-bottom: 20px; }
.s-notif-title { font-size: 12px; font-weight: 700; font-family: 'Poppins', sans-serif; color: #1a1a1a; margin-bottom: 1px; }
.s-notif-desc { font-size: 10px; color: #787A91; }
</style>

<div class="container">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="logo">
            <div class="logo-top">
                <img src="{{ asset('img/daun.png') }}">
                <h2>Smart <span>Hydroponic</span></h2>
            </div>
            <p class="logo-desc">IoT Hydroponic System</p>
        </div>

        <div class="profile" onclick="window.location.href='{{ url('/profile') }}'" style="cursor:pointer;">
            <img src="{{ asset('img/profile.png') }}">
            <div>
                <h4>Halo, {{ session('userName', 'Admin') }}!</h4>
                <p>{{ session('userEmail', 'Welcome Back') }}</p>
            </div>
        </div>

        <div class="menu">
            <button onclick="window.location.href='{{ url('/dashboard') }}'">
                <img src="{{ asset('img/dashboard2.png') }}">
                <span>Dashboard</span>
            </button>
            <button onclick="window.location.href='{{ url('/monitoring') }}'">
                <img src="{{ asset('img/chart.png') }}">
                <span>Monitoring Chart</span>
            </button>
            <button class="active">
                <img src="{{ asset('img/Settings.png') }}">
                <span>Setting</span>
            </button>
            <button class="exit" onclick="window.location.href='{{ url('/') }}'">
                <img src="{{ asset('img/out.png') }}">
                <span>Exit</span>
            </button>
        </div>

        <div class="status" id="status-system">
            <div class="status-top">
                <p><strong>Status System</strong></p>
                <span class="online-text" id="status-text">Online</span>
            </div>
            <div class="status-info">
                <img src="{{ asset('img/wifi.png') }}" class="wifi-icon" id="wifi-icon">
                <small id="status-desc">The system runs normally</small>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main setting-main">

        <!-- HEADER -->
        <div class="header">
            <div>
                <h2>System Setting</h2>
                <p>Device Control</p>
            </div>
            <div class="top-info">
                <span>
                    <img src="{{ asset('img/tanggal.png') }}">
                    <span id="current-date">-</span>
                </span>
                <span>
                    <img src="{{ asset('img/clock.png') }}">
                    <span id="current-time">-</span>
                </span>
                <div class="notif">
                    <img src="{{ asset('img/notif.png') }}">
                </div>
            </div>
        </div>

        <!-- ROW 1 -->
        <div class="s-row">
            <div class="s-card">
                <h3>Pump Schedule</h3>
                <div class="s-schedule-row">
                    <div class="s-schedule-item">
                        <label class="s-toggle">
                            <input type="checkbox" id="toggle-pump-a" checked onchange="toggleScheduleActive('Nutrition Pump A', this.checked)">
                            <span class="s-toggle-bar"></span>
                        </label>
                        <div>
                            <p class="s-schedule-name">Nutrition Pump A</p>
                            <p class="s-schedule-time" id="water-pump-time">06.00 - 08.00</p>
                        </div>
                    </div>
                    <div class="s-schedule-item">
                        <label class="s-toggle">
                            <input type="checkbox" id="toggle-pump-b" checked onchange="toggleScheduleActive('Nutrition Pump B', this.checked)">
                            <span class="s-toggle-bar"></span>
                        </label>
                        <div>
                            <p class="s-schedule-name">Nutrition Pump B</p>
                            <p class="s-schedule-time" id="nutrition-pump-time">17.00 - 19.00</p>
                        </div>
                    </div>
                </div>
                <button class="s-add-btn" onclick="document.getElementById('scheduleModal').style.display='flex'">+ Add Schedule</button>
            </div>
        </div>

        <!-- ROW 2 -->
        <div class="s-row">
            <div class="s-card">
                <h3>Notification</h3>
                <div class="s-notif-item">
                    <div><p class="s-notif-title">pH Warning</p><p class="s-notif-desc">Active if the pH is not normal</p></div>
                    <label class="s-toggle"><input type="checkbox" checked><span class="s-toggle-bar"></span></label>
                </div>
                <div class="s-notif-item">
                    <div><p class="s-notif-title">TDS Warning</p><p class="s-notif-desc">Active if the TDS is not normal</p></div>
                    <label class="s-toggle"><input type="checkbox" checked><span class="s-toggle-bar"></span></label>
                </div>
                <div class="s-notif-item">
                    <div><p class="s-notif-title">UV Warning</p><p class="s-notif-desc">Active if the UV is not normal</p></div>
                    <label class="s-toggle"><input type="checkbox" checked><span class="s-toggle-bar"></span></label>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- MODAL ADD SCHEDULE -->
<div id="scheduleModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.4); z-index:999; justify-content:center; align-items:center;">
    <div style="background:#fff; border-radius:16px; padding:30px; width:400px; box-shadow:0 10px 30px rgba(0,0,0,0.2);">

        <h3 style="font-family:'Poppins',sans-serif; font-size:16px; font-weight:700; margin-bottom:20px;">Add Schedule</h3>

        <div style="margin-bottom:15px;">
            <label style="font-size:12px; font-weight:600; font-family:'Poppins',sans-serif;">Schedule Name</label>
            <select id="schedule-name" style="width:100%; padding:10px; border:2px solid #ddd; border-radius:10px; margin-top:5px; font-family:'Poppins',sans-serif; font-size:13px;">
                <option>Nutrition Pump A</option>
                <option>Nutrition Pump B</option>
                <option>Custom</option>
            </select>
        </div>

        <div style="display:flex; gap:15px; margin-bottom:15px;">
            <div style="flex:1;">
                <label style="font-size:12px; font-weight:600; font-family:'Poppins',sans-serif;">Start Time</label>
                <input type="time" id="schedule-start" step="1" style="width:100%; padding:10px; border:2px solid #ddd; border-radius:10px; margin-top:5px; font-family:'Poppins',sans-serif; font-size:13px; box-sizing:border-box;">
            </div>
            <div style="flex:1;">
                <label style="font-size:12px; font-weight:600; font-family:'Poppins',sans-serif;">End Time</label>
                <input type="time" id="schedule-end" step="1" style="width:100%; padding:10px; border:2px solid #ddd; border-radius:10px; margin-top:5px; font-family:'Poppins',sans-serif; font-size:13px; box-sizing:border-box;">
            </div>
        </div>

        <div style="display:flex; align-items:center; gap:10px; margin-bottom:20px;">
            <label class="s-toggle"><input type="checkbox" id="schedule-active" checked><span class="s-toggle-bar"></span></label>
            <span style="font-size:13px; font-family:'Poppins',sans-serif;">Active</span>
        </div>

        <div style="display:flex; gap:10px;">
            <button onclick="document.getElementById('scheduleModal').style.display='none'"
                style="flex:1; padding:10px; border:2px solid #ddd; border-radius:10px; background:#fff; font-family:'Poppins',sans-serif; font-size:13px; cursor:pointer;">
                Cancel
            </button>
            <button onclick="saveSchedule()"
                style="flex:1; padding:10px; border:none; border-radius:10px; background:#16A34A; color:#fff; font-family:'Poppins',sans-serif; font-size:13px; font-weight:600; cursor:pointer;">
                Save Schedule
            </button>
        </div>

    </div>
</div>

<script>
// ===================== DATE TIME =====================
function updateDateTime() {
    const now = new Date();
    document.getElementById('current-date').textContent = now.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
    document.getElementById('current-time').textContent = now.toLocaleTimeString('id-ID');
}
updateDateTime();
setInterval(updateDateTime, 1000);

// ===================== FIREBASE =====================
const FIREBASE_URL          = '{{ env("FIREBASE_DATABASE_URL") }}/sensors.json';
const FIREBASE_SCHEDULE_URL = '{{ env("FIREBASE_DATABASE_URL") }}/schedule';

// ===================== NOTIFICATION PERMISSION =====================
if ('Notification' in window && Notification.permission === 'default') {
    Notification.requestPermission();
}

// Simpan state toggle notifikasi
const notifState = {
    ph:         true,
    tds:        true,
    uv:         true,
    connection: true,
};

// Ambil semua toggle notifikasi
const notifToggles = document.querySelectorAll('.s-notif-item .s-toggle input');
const notifKeys    = ['ph', 'tds', 'uv', 'connection'];

notifToggles.forEach(function(toggle, index) {
    toggle.checked = notifState[notifKeys[index]];
    toggle.addEventListener('change', function() {
        notifState[notifKeys[index]] = this.checked;
    });
});

// Tracking notifikasi agar tidak spam
const lastNotified  = { ph: 0, tds: 0, uv: 0, connection: 0 };
const NOTIF_COOLDOWN = 60000;

function sendNotif(key, title, body) {
    if (!notifState[key]) return;
    const now = Date.now();
    if (now - lastNotified[key] < NOTIF_COOLDOWN) return;
    lastNotified[key] = now;

    if ('Notification' in window && Notification.permission === 'granted') {
        new Notification(title, { body: body, icon: '/img/daun.png' });
    }
}

// ===================== SYSTEM STATUS =====================
function updateSystemStatus(isOnline) {
    const statusSystem = document.getElementById('status-system');
    const statusText   = document.getElementById('status-text');
    const statusDesc   = document.getElementById('status-desc');
    const wifiIcon     = document.getElementById('wifi-icon');

    if (isOnline) {
        statusText.textContent        = 'Online';
        statusDesc.textContent        = 'The system runs normally';
        statusSystem.style.background = '#16A34A';
        wifiIcon.style.filter         = 'brightness(0) invert(1)';
        wifiIcon.src                  = "{{ asset('img/wifi.png') }}";
    } else {
        statusText.textContent        = 'Offline';
        statusDesc.textContent        = 'No Internet Connection';
        statusSystem.style.background = '#DC2626';
        wifiIcon.style.filter         = 'brightness(0) invert(1)';
        wifiIcon.src                  = "{{ asset('img/wifi-off.svg') }}";

        sendNotif('connection', '⚠️ Smart Hydroponic', 'Internet connection lost! System is offline.');
    }
}

// ===================== CHECK SENSOR & NOTIF =====================
function checkSensorAndNotify(data) {
    if (!data) return;

    const ph  = parseFloat(data.ph  ?? 6.5);
    const tds = parseFloat(data.tds ?? 700);
    const uv  = parseFloat(data.uv  ?? 3.2);

    if (ph < 5.5 || ph > 7.5) {
        sendNotif('ph', '⚠️ pH Warning', `Water pH is ${ph} — outside normal range (5.5 - 7.5)`);
    }
    if (tds < 500 || tds > 1000) {
        sendNotif('tds', '⚠️ TDS Warning', `Water TDS is ${tds} ppm — outside normal range (500 - 1000 ppm)`);
    }
    if (uv < 2.5 || uv > 5.0) {
        sendNotif('uv', '⚠️ UV Warning', `UV Light is ${uv} — outside normal range (2.5 - 5.0)`);
    }
}

// ===================== FETCH SENSOR =====================
function checkConnection() {
    const controller = new AbortController();
    const timeout    = setTimeout(() => controller.abort(), 3000);

    fetch(FIREBASE_URL, { signal: controller.signal })
        .then(res => { clearTimeout(timeout); return res.json(); })
        .then(data => {
            updateSystemStatus(true);
            checkSensorAndNotify(data);
        })
        .catch(err => {
            clearTimeout(timeout);
            updateSystemStatus(false);
        });
}

// ===================== SCHEDULE =====================
let scheduleKeys = {}; // simpan key Firebase per nama schedule, agar bisa di-update / di-toggle

function saveSchedule() {
    const name   = document.getElementById('schedule-name').value;
    const start  = document.getElementById('schedule-start').value;
    const end    = document.getElementById('schedule-end').value;
    const active = document.getElementById('schedule-active').checked;

    if (!start || !end) {
        alert('Harap isi waktu mulai dan selesai!');
        return;
    }

    const data = {
        name: name, start_time: start, end_time: end,
        is_active: active, created_at: new Date().toISOString()
    };

    fetch(FIREBASE_SCHEDULE_URL + '.json', {
        method: 'POST',
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(() => {
        alert('Schedule berhasil disimpan!');
        document.getElementById('scheduleModal').style.display = 'none';
        loadSchedules();
    })
    .catch(() => alert('Gagal menyimpan schedule!'));
}

function loadSchedules() {
    fetch(FIREBASE_SCHEDULE_URL + '.json')
        .then(res => res.json())
        .then(data => {
            // Reset dulu ke default sebelum dicek ulang
            let foundA = false;
            let foundB = false;

            if (data) {
                Object.entries(data).forEach(([key, schedule]) => {
                    if (schedule.name === 'Nutrition Pump A') {
                        foundA = true;
                        scheduleKeys['Nutrition Pump A'] = key;
                        document.getElementById('water-pump-time').textContent =
                            schedule.start_time + ' - ' + schedule.end_time;
                        document.getElementById('toggle-pump-a').checked = schedule.is_active;
                    }
                    if (schedule.name === 'Nutrition Pump B') {
                        foundB = true;
                        scheduleKeys['Nutrition Pump B'] = key;
                        document.getElementById('nutrition-pump-time').textContent =
                            schedule.start_time + ' - ' + schedule.end_time;
                        document.getElementById('toggle-pump-b').checked = schedule.is_active;
                    }
                });
            }

            // Kalau tidak ditemukan di Firebase, berarti sudah dihapus -> reset ke 00.00.00
            if (!foundA) {
                document.getElementById('water-pump-time').textContent = '00.00.00 - 00.00.00';
                document.getElementById('toggle-pump-a').checked = false;
                delete scheduleKeys['Nutrition Pump A'];
            }
            if (!foundB) {
                document.getElementById('nutrition-pump-time').textContent = '00.00.00 - 00.00.00';
                document.getElementById('toggle-pump-b').checked = false;
                delete scheduleKeys['Nutrition Pump B'];
            }
        });
}

// Toggle aktif/non-aktif schedule langsung dari card (tanpa modal)
function toggleScheduleActive(name, isActive) {
    const key = scheduleKeys[name];

    if (!key) {
        // Belum ada schedule tersimpan untuk pump ini di Firebase
        alert('Belum ada schedule tersimpan untuk ' + name + '. Tambahkan schedule terlebih dahulu lewat "+ Add Schedule".');
        // kembalikan toggle ke posisi semula
        if (name === 'Nutrition Pump A') document.getElementById('toggle-pump-a').checked = !isActive;
        if (name === 'Nutrition Pump B') document.getElementById('toggle-pump-b').checked = !isActive;
        return;
    }

    fetch(FIREBASE_SCHEDULE_URL + '/' + key + '.json', {
        method: 'PATCH',
        body: JSON.stringify({ is_active: isActive })
    })
    .catch(() => alert('Gagal mengubah status schedule!'));
}

// ===================== START =====================
loadSchedules();
checkConnection();
setInterval(checkConnection, 5000);
setInterval(loadSchedules, 5000);
</script>

@endsection