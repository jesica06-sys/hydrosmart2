@extends('layouts.app')

@section('content')
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
            <button class="active">
                <img src="{{ asset('img/dashboard2.png') }}">
                <span>Dashboard</span>
            </button>
            <button onclick="window.location.href='{{ url('/monitoring') }}'">
                <img src="{{ asset('img/chart.png') }}">
                <span>Monitoring Chart</span>
            </button>
            <button onclick="window.location.href='{{ url('/setting') }}'">
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
    <div class="main">

        <!-- HEADER -->
        <div class="header">
            <div>
                <h2>Dashboard</h2>
                <p>Here is real-time information about the Hydroponic system</p>
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
                <span class="online" id="online-badge">
                    <span id="badge-dot">●</span> 
                    <span id="badge-text">Online System</span>
                </span>
            </div>
        </div>

        <!-- CARD -->
        <div class="cards">
            <div class="card">
                <img src="{{ asset('img/water.png') }}">
                <div class="card-text">
                    <p class="title">Water pH</p>
                    <h3 id="val-ph">{{ $sensorData['ph'] ?? '6.5' }}</h3>
                </div>
            </div>

            <div class="card">
                <img src="{{ asset('img/tds.png') }}">
                <div class="card-text">
                    <p class="title">Water TDS</p>
                    <h3 id="val-tds">{{ $sensorData['tds'] ?? '700' }} ppm</h3>
                </div>
            </div>

            <div class="card">
                <img src="{{ asset('img/pompa.png') }}">
                <div class="card-text">
                    <p class="title">Nutrition Pump A</p>
                    <div style="display:flex; align-items:center; gap:8px;">
                        <h3 id="val-pump" style="margin:0;">{{ $sensorData['pump'] ?? 'ON' }}</h3>
                        <label class="s-toggle" style="width:36px; height:20px; transform:scale(0.8); margin-left:40px; ">
                            <input type="checkbox" id="pump-toggle" checked onchange="togglePump(this)">
                            <span class="s-toggle-bar"></span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="card">
                <img src="{{ asset('img/pompa.png') }}">
                <div class="card-text">
                    <p class="title">Nutrition Pump B</p>
                    <div style="display:flex; align-items:center; gap:8px;">
                        <h3 id="val-nutrition" style="margin:0;">ON</h3>
                        <label class="s-toggle" style="width:36px; height:20px; transform:scale(0.8); margin-left:40px;">
                            <input type="checkbox" id="nutrition-toggle" checked onchange="toggleNutrition(this)">
                            <span class="s-toggle-bar"></span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="card">
                <img src="{{ asset('img/uv.png') }}">
                <div class="card-text">
                    <p class="title">UV Light</p>
                    <h3 id="val-uv">{{ $sensorData['uv'] ?? '3.2' }}</h3>
                </div>
            </div>
        </div>

        <div class="chart-tabs">
            <div class="tab">
                <img src="{{ asset('img/water.png') }}">
                <span>Water pH</span>
            </div>
            <div class="tab">
                <img src="{{ asset('img/tds.png') }}">
                <span>Water TDS</span>
            </div>
            <div class="tab">
                <img src="{{ asset('img/uv.png') }}">
                <span>UV Light</span>
            </div>
        </div>

        <!-- CONTENT -->
        <div class="content">

            <!-- GRAPH -->
            <div class="graph">
                <div class="graph-header">
                    <h3>Temperature Chart (°C)</h3>
                </div>
                <canvas id="myChart"></canvas>
                <div class="stats">
                    <div>
                        <p>Minimum</p>
                        <h4>25.0 °C</h4>
                    </div>
                    <div>
                        <p>Average</p>
                        <h4>28.2 °C</h4>
                    </div>
                    <div>
                        <p>Maximum</p>
                        <h4>31.0 °C</h4>
                    </div>
                </div>
            </div>

            <!-- RIGHT PANEL -->
            <div class="right-panel">
                <div class="status-box">
                    <h3>System Status</h3>
                    <p>Sensor <span id="sensor-status">Normal</span></p>
                    <p>Nutrition Pump A<span id="pump-status">Running</span></p>        
                    <p>Nutrition Pump B<span id="nutrition-status">Running</span></p>
                    <p>Internet Connection <span id="internet-status">Online</span></p>
                </div>

                <div class="history">
                    <h3>Latest Data</h3>
                    <div class="history-item">
                        <img src="{{ asset('img/water.png') }}">
                        <p>Water pH</p>
                        <div class="history-text">
                            <p id="history-ph">{{ $sensorData['ph'] ?? '6.5' }}</p>
                            <span class="update" id="history-time-ph">-</span>
                        </div>
                    </div>
                    <div class="history-item">
                        <img src="{{ asset('img/tds.png') }}">
                        <p>Water TDS</p>
                        <div class="history-text">
                            <p id="history-tds">{{ $sensorData['tds'] ?? '700' }} ppm</p>
                            <span class="update" id="history-time-tds">-</span>
                        </div>
                    </div>
                    <div class="history-item">
                        <img src="{{ asset('img/uv.png') }}">
                        <p>UV Light</p>
                        <div class="history-text">
                            <p id="history-uv">{{ $sensorData['uv'] ?? '3.2' }}</p>
                            <span class="update" id="history-time-uv">-</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// ===================== HISTORY DATA =====================
const MAX_POINTS = 20;
const history = {
    ph:  { data: [], labels: [] },
    tds: { data: [], labels: [] },
    uv:  { data: [], labels: [] },
};

let activeTab = 'ph';

// ===================== FIREBASE FETCH =====================
const FIREBASE_URL = '{{ env("FIREBASE_DATABASE_URL") }}/sensors.json';

function fetchSensorData() {
    const controller = new AbortController();
    const timeout = setTimeout(() => controller.abort(), 3000);

    fetch(FIREBASE_URL, { signal: controller.signal })
        .then(res => {
            clearTimeout(timeout);
            return res.json();
        })
        .then(data => {
            updateSystemStatus(true);
            if (!data) return;

            const timeLabel = new Date().toLocaleTimeString('id-ID', {
                hour: '2-digit', minute: '2-digit', second: '2-digit'
            });

            // Update card & history pH
            if (data.ph !== undefined) {
                document.getElementById('val-ph').textContent = data.ph;
                document.getElementById('history-ph').textContent = data.ph;
                pushHistory('ph', data.ph, timeLabel);
            }

            // Update card & history TDS
            if (data.tds !== undefined) {
                document.getElementById('val-tds').textContent = data.tds + ' ppm';
                document.getElementById('history-tds').textContent = data.tds + ' ppm';
                pushHistory('tds', data.tds, timeLabel);
            }

            // Update card & history UV
            if (data.uv !== undefined) {
                document.getElementById('val-uv').textContent = data.uv;
                document.getElementById('history-uv').textContent = data.uv;
                pushHistory('uv', data.uv, timeLabel);
            }

            // Update Pump 1 (pompa_air)
            if (data.pump !== undefined) {
                document.getElementById('val-pump').textContent = data.pump;
                const pumpToggle = document.getElementById('pump-toggle');
                pumpToggle.checked = data.pump === 'ON';
                const pumpStatus = document.getElementById('pump-status');
                if (data.pump === 'ON') {
                    pumpStatus.textContent = 'Running';
                    pumpStatus.style.color = '#16A34A';
                } else {
                    pumpStatus.textContent = 'Stopped';
                    pumpStatus.style.color = '#DC2626';
                }
            }

            // Update Pump 2 (pompa_nutrisi)
            if (data.nutrition !== undefined) {
                document.getElementById('val-nutrition').textContent = data.nutrition;
                const nutritionToggle = document.getElementById('nutrition-toggle');
                nutritionToggle.checked = data.nutrition === 'ON';
                const nutritionStatus = document.getElementById('nutrition-status');
                if (data.nutrition === 'ON') {
                    nutritionStatus.textContent = 'Running';
                    nutritionStatus.style.color = '#16A34A';
                } else {
                    nutritionStatus.textContent = 'Stopped';
                    nutritionStatus.style.color = '#DC2626';
                }
            }

            // Sensor status warning check
            const ph  = parseFloat(data.ph  ?? 6.5);
            const tds = parseFloat(data.tds ?? 700);
            const uv  = parseFloat(data.uv  ?? 3.2);
            const sensorStatus = document.getElementById('sensor-status');
            const isNormal = ph  >= 5.5 && ph  <= 8.5 &&
                             tds >= 0 && tds <= 1500 &&
                             uv  >= 1.0 && uv  <= 6.0;
            if (isNormal) {
                sensorStatus.textContent = 'Normal';
                sensorStatus.style.color = '#16A34A';
            } else {
                sensorStatus.textContent = 'Warning';
                sensorStatus.style.color = '#F59E0B';
            }

            // Update timestamp di Latest Data
            const now  = new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
            const time = new Date().toLocaleTimeString('id-ID');
            document.querySelectorAll('.update').forEach(el => el.textContent = now + ' ' + time);

            // Refresh chart sesuai tab aktif
            refreshChart();
        })
        .catch(err => {
            clearTimeout(timeout);
            updateSystemStatus(false);
            console.log('Firebase error:', err);
        });
}

// ===================== PUSH HISTORY =====================
function pushHistory(key, value, label) {
    history[key].data.push(parseFloat(value));
    history[key].labels.push(label);
    if (history[key].data.length > MAX_POINTS) {
        history[key].data.shift();
        history[key].labels.shift();
    }
}

// ===================== SYSTEM STATUS =====================
function updateSystemStatus(isOnline) {
    const statusSystem   = document.getElementById('status-system');
    const statusText     = document.getElementById('status-text');
    const statusDesc     = document.getElementById('status-desc');
    const wifiIcon       = document.getElementById('wifi-icon');
    const onlineBadge    = document.getElementById('online-badge');
    const internetStatus = document.getElementById('internet-status');

    if (isOnline) {
        statusText.textContent = 'Online';
        statusDesc.textContent = 'The system runs normally';
        statusSystem.style.background = '#16A34A';
        wifiIcon.style.filter  = 'brightness(0) invert(1)';
        wifiIcon.style.opacity = '1';
        wifiIcon.src = "{{ asset('img/wifi.png') }}";
        document.getElementById('badge-dot').style.color   = '#16A34A';
        document.getElementById('badge-text').textContent  = 'Online System';
        onlineBadge.style.color      = '#16A34A';
        onlineBadge.style.background = '#f0fdf4';
        onlineBadge.style.border     = '1.5px solid #16A34A';
        internetStatus.textContent   = 'Online';
        internetStatus.style.color   = '#16A34A';
    } else {
        statusText.textContent = 'Offline';
        statusDesc.textContent = 'No Internet Connection';
        statusSystem.style.background = '#DC2626';
        wifiIcon.style.filter  = 'brightness(0) invert(1)';
        wifiIcon.style.opacity = '1';
        wifiIcon.src = "{{ asset('img/wifi-off.svg') }}";
        document.getElementById('badge-dot').style.color   = '#DC2626';
        document.getElementById('badge-text').textContent  = 'Offline System';
        onlineBadge.style.color      = '#DC2626';
        onlineBadge.style.background = '#fef2f2';
        onlineBadge.style.border     = '1.5px solid #DC2626';
        internetStatus.textContent   = 'Disconnected';
        internetStatus.style.color   = '#DC2626';
    }
}

// ===================== PUMP TOGGLE =====================
function togglePump(checkbox) {
    const status     = checkbox.checked ? 'ON' : 'OFF';
    const statusBool = checkbox.checked;

    document.getElementById('val-pump').textContent = status;
    const pumpStatus = document.getElementById('pump-status');
    pumpStatus.textContent = checkbox.checked ? 'Running' : 'Stopped';
    pumpStatus.style.color = checkbox.checked ? '#16A34A' : '#DC2626';

    fetch('{{ env("FIREBASE_DATABASE_URL") }}/sensors/nutritionA.json', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(status)
    });
    fetch('{{ env("FIREBASE_DATABASE_URL") }}/relay/pompa_nutritionA.json', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(statusBool)
    });
}

function toggleNutrition(checkbox) {
    const status     = checkbox.checked ? 'ON' : 'OFF';
    const statusBool = checkbox.checked;

    document.getElementById('val-nutrition').textContent = status;
    const nutritionStatus = document.getElementById('nutrition-status');
    nutritionStatus.textContent = checkbox.checked ? 'Running' : 'Stopped';
    nutritionStatus.style.color = checkbox.checked ? '#16A34A' : '#DC2626';

    fetch('{{ env("FIREBASE_DATABASE_URL") }}/sensors/nutritionB.json', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(status)
    });
    fetch('{{ env("FIREBASE_DATABASE_URL") }}/relay/pompa_nutritionB.json', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(statusBool)
    });
}

function fetchRelayStatus() {
    fetch('{{ env("FIREBASE_DATABASE_URL") }}/relay.json')
        .then(res => res.json())
        .then(data => {
            if (!data) return;

            // Pompa A
            const pompaA = data.pompa_nutritionA || false;
            document.getElementById('val-pump').textContent = pompaA ? 'ON' : 'OFF';
            document.getElementById('pump-toggle').checked = pompaA;
            const pumpStatus = document.getElementById('pump-status');
            pumpStatus.textContent = pompaA ? 'Running' : 'Stopped';
            pumpStatus.style.color = pompaA ? '#16A34A' : '#DC2626';

            // Pompa B
            const pompaB = data.pompa_nutritionB || false;
            document.getElementById('val-nutrition').textContent = pompaB ? 'ON' : 'OFF';
            document.getElementById('nutrition-toggle').checked = pompaB;
            const nutritionStatus = document.getElementById('nutrition-status');
            nutritionStatus.textContent = pompaB ? 'Running' : 'Stopped';
            nutritionStatus.style.color = pompaB ? '#16A34A' : '#DC2626';
        });
}

// Panggil di bagian START POLLING
fetchRelayStatus();
setInterval(fetchRelayStatus, 3000);

// ===================== CHART SETUP =====================
const chartMeta = {
    ph:  { title: 'Water pH Chart',        color: '#3B82F6', unit: ''     },
    tds: { title: 'Water TDS Chart (ppm)', color: '#8B5CF6', unit: ' ppm' },
    uv:  { title: 'UV Light Chart',        color: '#F59E0B', unit: ''     },
};

const ctx = document.getElementById('myChart');
const myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [],
        datasets: [{
            label: 'Water pH',
            data: [],
            borderColor: chartMeta.ph.color,
            backgroundColor: chartMeta.ph.color + '22',
            borderWidth: 2,
            tension: 0.4,
            fill: true,
            pointRadius: 3,
        }]
    },
    options: {
        responsive: true,
        animation: { duration: 300 },
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: false } }
    }
});

// ===================== REFRESH CHART =====================
function refreshChart() {
    const h    = history[activeTab];
    const meta = chartMeta[activeTab];
    if (h.data.length === 0) return;

    myChart.data.labels                          = [...h.labels];
    myChart.data.datasets[0].data               = [...h.data];
    myChart.data.datasets[0].borderColor        = meta.color;
    myChart.data.datasets[0].backgroundColor    = meta.color + '22';
    myChart.update('none');

    document.querySelector('.graph-header h3').textContent = meta.title;

    const vals = h.data;
    const min  = Math.min(...vals).toFixed(1) + meta.unit;
    const avg  = (vals.reduce((a, b) => a + b, 0) / vals.length).toFixed(1) + meta.unit;
    const max  = Math.max(...vals).toFixed(1) + meta.unit;

    const stats = document.querySelectorAll('.stats div h4');
    stats[0].textContent = min;
    stats[1].textContent = avg;
    stats[2].textContent = max;
}

// ===================== TABS =====================
const tabs    = document.querySelectorAll('.chart-tabs .tab');
const tabKeys = ['ph', 'tds', 'uv']; // temperature dihapus

tabs.forEach(function(tab, index) {
    tab.addEventListener('click', function() {
        tabs.forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        activeTab = tabKeys[index];
        refreshChart();
    });
});

// ===================== TIME FILTER BUTTONS =====================
const timeButtons = document.querySelectorAll('.time-filter button');
timeButtons.forEach(function(btn) {
    btn.addEventListener('click', function() {
        timeButtons.forEach(b => b.classList.remove('active'));
        this.classList.add('active');
    });
});

// ===================== DATE TIME =====================
function updateDateTime() {
    const now = new Date();
    document.getElementById('current-date').textContent = now.toLocaleDateString('id-ID', {
        day: 'numeric', month: 'long', year: 'numeric'
    });
    document.getElementById('current-time').textContent = now.toLocaleTimeString('id-ID');
}
updateDateTime();
setInterval(updateDateTime, 1000);

// ===================== START POLLING =====================
fetchSensorData();
setInterval(fetchSensorData, 5000);
</script>

@endsection