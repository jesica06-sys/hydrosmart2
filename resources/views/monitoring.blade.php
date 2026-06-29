@extends('layouts.app')

@section('content')

<button id="hamburger-btn" class="hamburger-btn">☰</button>
<div class="sidebar-overlay" id="sidebar-overlay"></div>

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
            <button class="active">
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
    <div class="main monitoring-main">

        <!-- HEADER -->
        <div class="header">
            <div>
                <h2>Monitoring Chart</h2>
                <p>Monitor sensor data in graphical form in detail</p>
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

        <!-- TABS -->
        <div class="chart-tabs" style="margin-top:20px;">
            <div class="tab active">
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
        <div class="monitoring-content">

            <!-- GRAPH -->
            <div class="monitoring-graph">
                <div class="graph-header">
                    <h3 id="chart-title">Water pH Chart</h3>
                </div>

                <canvas id="monitoringChart"></canvas>

                <!-- STATS: HAPUS history-item dari sini, ganti dengan stats -->
                <div class="stats">
                    <div>
                        <p>Minimum</p>
                        <h4 id="stat-min">-</h4>
                    </div>
                    <div>
                        <p>Average</p>
                        <h4 id="stat-avg">-</h4>
                    </div>
                    <div>
                        <p>Maximum</p>
                        <h4 id="stat-max">-</h4>
                    </div>
                </div>
            </div>

            <!-- RIGHT PANEL — history items HANYA di sini -->
            <div class="right-panel" id="mon-panel">
                <div class="history history-monitoring">
                    <h3>Latest Data History</h3>
                    <div class="history-item">
                        <img src="{{ asset('img/water.png') }}">
                        <p>Water pH</p>
                        <div class="history-text">
                            <p id="history-ph">-</p>
                            <span class="update" id="history-time-ph">-</span>
                        </div>
                    </div>
                    <div class="history-item">
                        <img src="{{ asset('img/tds.png') }}">
                        <p>Water TDS</p>
                        <div class="history-text">
                            <p id="history-tds">-</p>
                            <span class="update" id="history-time-tds">-</span>
                        </div>
                    </div>
                    <div class="history-item">
                        <img src="{{ asset('img/uv.png') }}">
                        <p>UV Light</p>
                        <div class="history-text">
                            <p id="history-uv">-</p>
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
// ===================== HAMBURGER MENU =====================
document.getElementById('hamburger-btn').addEventListener('click', function() {
    document.querySelector('.sidebar').classList.toggle('active');
    document.getElementById('sidebar-overlay').classList.toggle('active');
});

document.getElementById('sidebar-overlay').addEventListener('click', function() {
    document.querySelector('.sidebar').classList.remove('active');
    this.classList.remove('active');
});

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
            const now      = new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
            const time     = new Date().toLocaleTimeString('id-ID');
            const datetime = now + ' ' + time;

            if (data.ph !== undefined) {
                pushHistory('ph', data.ph, timeLabel);
                document.getElementById('history-ph').textContent        = data.ph;
                document.getElementById('history-time-ph').textContent   = datetime;
            }
            if (data.tds !== undefined) {
                pushHistory('tds', data.tds, timeLabel);
                document.getElementById('history-tds').textContent       = data.tds + ' ppm';
                document.getElementById('history-time-tds').textContent  = datetime;
            }
            if (data.uv !== undefined) {
                pushHistory('uv', data.uv, timeLabel);
                document.getElementById('history-uv').textContent        = data.uv;
                document.getElementById('history-time-uv').textContent   = datetime;
            }

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
    const statusSystem = document.getElementById('status-system');
    const statusText   = document.getElementById('status-text');
    const statusDesc   = document.getElementById('status-desc');
    const wifiIcon     = document.getElementById('wifi-icon');

    if (isOnline) {
        statusText.textContent        = 'Online';
        statusDesc.textContent        = 'The system runs normally';
        statusSystem.style.background = '#16A34A';
        wifiIcon.src                  = "{{ asset('img/wifi.png') }}";
        wifiIcon.style.filter         = 'brightness(0) invert(1)';
    } else {
        statusText.textContent        = 'Offline';
        statusDesc.textContent        = 'No Internet Connection';
        statusSystem.style.background = '#DC2626';
        wifiIcon.src                  = "{{ asset('img/wifi-off.svg') }}";
        wifiIcon.style.filter         = 'brightness(0) invert(1)';
    }
}

// ===================== CHART META =====================
const chartMeta = {
    ph:  { title: 'Water pH Chart',        color: '#3B82F6', unit: ''     },
    tds: { title: 'Water TDS Chart (ppm)', color: '#8B5CF6', unit: ' ppm' },
    uv:  { title: 'UV Light Chart',        color: '#F59E0B', unit: ''     },
};

// ===================== CHART INIT =====================
const ctx = document.getElementById('monitoringChart');
const myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [],
        datasets: [{
            label: 'Data',
            data: [],
            borderColor: chartMeta.ph.color,
            backgroundColor: chartMeta.ph.color + '22',
            borderWidth: 2,
            tension: 0.4,
            pointBackgroundColor: '#fff',
            pointBorderColor: chartMeta.ph.color,
            pointRadius: 4,
            fill: true,
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

    myChart.data.labels                       = [...h.labels];
    myChart.data.datasets[0].data             = [...h.data];
    myChart.data.datasets[0].borderColor      = meta.color;
    myChart.data.datasets[0].backgroundColor  = meta.color + '22';
    myChart.data.datasets[0].pointBorderColor = meta.color;
    myChart.update('none');

    document.getElementById('chart-title').textContent = meta.title;

    const vals = h.data;
    const min  = Math.min(...vals).toFixed(1) + meta.unit;
    const avg  = (vals.reduce((a, b) => a + b, 0) / vals.length).toFixed(1) + meta.unit;
    const max  = Math.max(...vals).toFixed(1) + meta.unit;

    document.getElementById('stat-min').textContent = min;
    document.getElementById('stat-avg').textContent = avg;
    document.getElementById('stat-max').textContent = max;
}

// ===================== TABS =====================
const tabs    = document.querySelectorAll('.chart-tabs .tab');
const tabKeys = ['ph', 'tds', 'uv'];

tabs.forEach(function(tab, index) {
    tab.addEventListener('click', function() {
        tabs.forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        activeTab = tabKeys[index];
        refreshChart();
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