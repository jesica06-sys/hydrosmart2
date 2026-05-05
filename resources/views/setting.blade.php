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
                <h3>Pump Control</h3>
                <div class="s-radio-group">
                    <label class="s-radio-label">
                        <input type="radio" name="pump-mode" value="automatic" checked> Automatic
                    </label>
                    <label class="s-radio-label">
                        <input type="radio" name="pump-mode" value="manual"> Manual
                    </label>
                </div>
                <p class="s-speed-label">Pump Speed</p>
                <div class="s-slider-group">
                    <input type="range" min="0" max="100" value="80" id="pump-speed"
                        oninput="document.getElementById('pump-speed-val').textContent = this.value + '%'">
                    <span id="pump-speed-val">80%</span>
                </div>
            </div>

            <div class="s-card">
                <h3>Pump Schedule</h3>
                <div class="s-schedule-row">
                    <div class="s-schedule-item">
                        <label class="s-toggle"><input type="checkbox" checked><span class="s-toggle-bar"></span></label>
                        <div>
                            <p class="s-schedule-name">Water Pump</p>
                            <p class="s-schedule-time">06.00 - 08.00</p>
                        </div>
                    </div>
                    <div class="s-schedule-item">
                        <label class="s-toggle"><input type="checkbox" checked><span class="s-toggle-bar"></span></label>
                        <div>
                            <p class="s-schedule-name">Nutrition Pump</p>
                            <p class="s-schedule-time">17.00 - 19.00</p>
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
                    <div><p class="s-notif-title">Temperature Warning</p><p class="s-notif-desc">Active if the temperature is abnormal</p></div>
                    <label class="s-toggle"><input type="checkbox" checked><span class="s-toggle-bar"></span></label>
                </div>
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
                <div class="s-notif-item">
                    <div><p class="s-notif-title">Internet Connection</p><p class="s-notif-desc">Notification if the connection is lost</p></div>
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
            <select style="width:100%; padding:10px; border:2px solid #ddd; border-radius:10px; margin-top:5px; font-family:'Poppins',sans-serif; font-size:13px;">
                <option>Water Pump</option>
                <option>Nutrition Pump</option>
                <option>Custom</option>
            </select>
        </div>

        <div style="display:flex; gap:15px; margin-bottom:15px;">
            <div style="flex:1;">
                <label style="font-size:12px; font-weight:600; font-family:'Poppins',sans-serif;">Start Time</label>
                <input type="time" style="width:100%; padding:10px; border:2px solid #ddd; border-radius:10px; margin-top:5px; font-family:'Poppins',sans-serif; font-size:13px; box-sizing:border-box;">
            </div>
            <div style="flex:1;">
                <label style="font-size:12px; font-weight:600; font-family:'Poppins',sans-serif;">End Time</label>
                <input type="time" style="width:100%; padding:10px; border:2px solid #ddd; border-radius:10px; margin-top:5px; font-family:'Poppins',sans-serif; font-size:13px; box-sizing:border-box;">
            </div>
        </div>

        <div style="display:flex; align-items:center; gap:10px; margin-bottom:20px;">
            <label class="s-toggle"><input type="checkbox" checked><span class="s-toggle-bar"></span></label>
            <span style="font-size:13px; font-family:'Poppins',sans-serif;">Active</span>
        </div>

        <div style="display:flex; gap:10px;">
            <button onclick="document.getElementById('scheduleModal').style.display='none'"
                style="flex:1; padding:10px; border:2px solid #ddd; border-radius:10px; background:#fff; font-family:'Poppins',sans-serif; font-size:13px; cursor:pointer;">
                Cancel
            </button>
            <button onclick="document.getElementById('scheduleModal').style.display='none'"
                style="flex:1; padding:10px; border:none; border-radius:10px; background:#16A34A; color:#fff; font-family:'Poppins',sans-serif; font-size:13px; font-weight:600; cursor:pointer;">
                Save Schedule
            </button>
        </div>

    </div>
</div>

<script>
function updateDateTime() {
    const now = new Date();
    const dateOptions = { day: 'numeric', month: 'long', year: 'numeric' };
    document.getElementById('current-date').textContent = now.toLocaleDateString('id-ID', dateOptions);
    document.getElementById('current-time').textContent = now.toLocaleTimeString('id-ID');
}
updateDateTime();
setInterval(updateDateTime, 1000);

const FIREBASE_URL = '{{ env('FIREBASE_DATABASE_URL') }}/sensors.json';

function checkConnection() {
    const controller = new AbortController();
    const timeout = setTimeout(() => controller.abort(), 3000);

    fetch(FIREBASE_URL, { signal: controller.signal })
        .then(res => {
            clearTimeout(timeout);
            updateSystemStatus(true);
        })
        .catch(err => {
            clearTimeout(timeout);
            updateSystemStatus(false);
        });
}

function updateSystemStatus(isOnline) {
    const statusSystem = document.getElementById('status-system');
    const statusText = document.getElementById('status-text');
    const statusDesc = document.getElementById('status-desc');
    const wifiIcon = document.getElementById('wifi-icon');

    if (isOnline) {
        statusText.textContent = 'Online';
        statusDesc.textContent = 'The system runs normally';
        statusSystem.style.background = '#16A34A';
        wifiIcon.style.filter = 'brightness(0) invert(1)';
        wifiIcon.style.opacity = '1';
        wifiIcon.src = '/img/wifi.png';
    } else {
        statusText.textContent = 'Offline';
        statusDesc.textContent = 'System disconnected!';
        statusSystem.style.background = '#DC2626';
        wifiIcon.style.filter = 'brightness(0) invert(1)';
        wifiIcon.style.opacity = '1';
        wifiIcon.src = '/img/wifi-off.svg';
    }
}

checkConnection();
setInterval(checkConnection, 5000);
</script>

@endsection