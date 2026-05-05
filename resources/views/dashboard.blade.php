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
                <img src="{{ asset('img/temperature.png') }}">
                <div class="card-text">
                    <p class="title">Temperature</p>
                    <h3 id="val-temperature">{{ $sensorData['temperature'] ?? '28.0' }}</h3>
                    <span class="status normal" id="status-temperature">
                        {{ isset($sensorData['temperature']) && $sensorData['temperature'] >= 20 && $sensorData['temperature'] <= 35 ? 'Normal' : 'Normal' }}
                    </span>
                </div>
            </div>
            <div class="card">
                <img src="{{ asset('img/water.png') }}">
                <div class="card-text">
                    <p class="title">Water pH</p>
                    <h3 id="val-ph">{{ $sensorData['ph'] ?? '6.5' }}</h3>
                    <span class="status normal" id="status-ph">
                        {{ isset($sensorData['ph']) && $sensorData['ph'] >= 5.5 && $sensorData['ph'] <= 7.5 ? 'Normal' : 'Normal' }}
                    </span>
                </div>
            </div>
            <div class="card">
                <img src="{{ asset('img/tds.png') }}">
                <div class="card-text">
                    <p class="title">Water TDS</p>
                    <h3 id="val-tds">{{ $sensorData['tds'] ?? '700' }} ppm</h3>
                    <span class="status normal" id="status-tds">
                        {{ isset($sensorData['tds']) && $sensorData['tds'] >= 500 && $sensorData['tds'] <= 1000 ? 'Normal' : 'Normal' }}
                    </span>
                </div>
            </div>
            <div class="card">
                <img src="{{ asset('img/pompa.png') }}">
                <div class="card-text">
                    <p class="title">Water Pump</p>
                    <h3 id="val-pump">{{ $sensorData['pump'] ?? 'ON' }}</h3>
                    <span class="status normal">Nutrition Circulation</span>
                </div>
            </div>
            <div class="card">
                <img src="{{ asset('img/uv.png') }}">
                <div class="card-text">
                    <p class="title">UV Light</p>
                    <h3 id="val-uv">{{ $sensorData['uv'] ?? '3.2' }}</h3>
                    <span class="status normal" id="status-uv">
                        {{ isset($sensorData['uv']) && $sensorData['uv'] >= 2.5 && $sensorData['uv'] <= 5.0 ? 'Optimal' : 'Optimal' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="chart-tabs">
            <div class="tab active">
                <img src="{{ asset('img/temperature.png') }}">
                <span>Temperature</span>
            </div>
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
                    <div class="time-filter">
                        <button class="active">1 jam</button>
                        <button>6 jam</button>
                        <button>12 jam</button>
                        <button>1 hari</button>
                        <button>7 hari</button>
                    </div>
                </div>
                <canvas id="myChart"></canvas>
                <div class="stats">
                    <div>
                        <p>Minimum</p>
                        <h4>25.0 °C</h4>
                        <span class="time">08:30</span>
                    </div>
                    <div>
                        <p>Average</p>
                        <h4>28.2 °C</h4>
                        <span class="time">12:00</span>
                    </div>
                    <div>
                        <p>Maximum</p>
                        <h4>31.0 °C</h4>
                        <span class="time">14:45</span>
                    </div>
                </div>
            </div>

            <!-- RIGHT PANEL -->
            <div class="right-panel">
                <div class="status-box">
                    <h3>System Status</h3>
                    <p>Sensor <span>Normal</span></p>
                    <p>Water Cycle <span>Walk</span></p>
                    <p>Internet Connection <span>Online</span></p>
                </div>

                <div class="history">
                    <h3>Latest Data</h3>
                    <div class="history-item">
                        <img src="{{ asset('img/temperature.png') }}">
                        <p>Temperature</p>
                        <div class="history-text">
                            <p id="history-temperature">{{ $sensorData['temperature'] ?? '28.0' }} °C</p>
                            <span class="update" id="history-time">-</span>
                        </div>
                    </div>
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
const FIREBASE_URL = '{{ env('FIREBASE_DATABASE_URL') }}/sensors.json';

// Fetch data Firebase setiap 5 detik
function fetchSensorData() {
    const controller = new AbortController();
    const timeout = setTimeout(() => controller.abort(), 1000); // timeout 3 detik

    fetch(FIREBASE_URL, { signal: controller.signal })
        .then(res => {
            clearTimeout(timeout);
            return res.json();
        })
        .then(data => {
            updateSystemStatus(true);
            if (!data) return;

            if (data.temperature !== undefined) {
                document.getElementById('val-temperature').textContent = data.temperature;
                document.getElementById('history-temperature').textContent = data.temperature + ' °C';
            }
            if (data.ph !== undefined) {
                document.getElementById('val-ph').textContent = data.ph;
                document.getElementById('history-ph').textContent = data.ph;
            }
            if (data.tds !== undefined) {
                document.getElementById('val-tds').textContent = data.tds + ' ppm';
                document.getElementById('history-tds').textContent = data.tds + ' ppm';
            }
            if (data.pump !== undefined) {
                document.getElementById('val-pump').textContent = data.pump;
            }
            if (data.uv !== undefined) {
                document.getElementById('val-uv').textContent = data.uv;
                document.getElementById('history-uv').textContent = data.uv;
            }

            const now = new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
            const time = new Date().toLocaleTimeString('id-ID');
            const datetime = now + ' ' + time;
            document.querySelectorAll('.update').forEach(el => el.textContent = datetime);
        })
        .catch(err => {
            clearTimeout(timeout);
            updateSystemStatus(false);
            console.log('Firebase error:', err);
        });
}
function updateSystemStatus(isOnline) {
    const statusSystem = document.getElementById('status-system');
    const statusText = document.getElementById('status-text');
    const statusDesc = document.getElementById('status-desc');
    const wifiIcon = document.getElementById('wifi-icon');
    const onlineBadge = document.getElementById('online-badge');

    if (isOnline) {
        statusText.textContent = 'Online';
        statusDesc.textContent = 'The system runs normally';
        statusSystem.style.background = '#16A34A';
        wifiIcon.style.filter = 'brightness(0) invert(1)';
        wifiIcon.style.opacity = '1';
        wifiIcon.src = "{{ asset('img/wifi.png') }}";
        document.getElementById('badge-dot').style.color = '#16A34A';
        document.getElementById('badge-text').textContent = 'Online System';
        onlineBadge.style.color = '#16A34A';
        onlineBadge.style.background = '#f0fdf4';
        onlineBadge.style.border = '1.5px solid #16A34A';
    } else {
        statusText.textContent = 'Offline';
        statusDesc.textContent = 'No Internet Connection';
        statusSystem.style.background = '#DC2626';
        wifiIcon.style.filter = 'brightness(0) invert(1)';
        wifiIcon.style.opacity = '1';
        wifiIcon.src = "{{ asset('img/wifi-off.svg') }}";
        document.getElementById('badge-dot').style.color = '#DC2626';
        document.getElementById('badge-text').textContent = 'Offline System';
        onlineBadge.style.color = '#DC2626';
        onlineBadge.style.background = '#fef2f2';
        onlineBadge.style.border = '1.5px solid #DC2626';
    }
}
fetchSensorData();
setInterval(fetchSensorData, 5000);

const chartData = {
    temperature: {
        title: 'Temperature Chart (°C)',
        data: [28.0, 27.5, 28.5, 29.0, 28.2, 27.8, 28.8, 29.2, 28.5, 28.0],
        min: '25.0 °C', avg: '28.2 °C', max: '31.0 °C', color: '#16A34A'
    },
    ph: {
        title: 'Water pH Chart',
        data: [6.5, 6.8, 7.0, 6.9, 6.4, 6.6, 7.1, 6.7, 6.5, 6.8],
        min: '6.4', avg: '6.7', max: '7.1', color: '#3B82F6'
    },
    tds: {
        title: 'Water TDS Chart (ppm)',
        data: [700, 720, 680, 750, 710, 690, 730, 700, 715, 695],
        min: '660 ppm', avg: '700 ppm', max: '750 ppm', color: '#8B5CF6'
    },
    uv: {
        title: 'UV Light Chart',
        data: [3.2, 3.5, 3.8, 3.1, 2.9, 3.4, 3.7, 3.3, 3.0, 3.6],
        min: '2.7', avg: '3.3', max: '3.8', color: '#F59E0B'
    }
};

const labels = ['08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00'];

const ctx = document.getElementById('myChart');
const myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Temperature',
            data: chartData.temperature.data,
            borderColor: chartData.temperature.color,
            backgroundColor: 'rgba(22,163,74,0.1)',
            borderWidth: 2,
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: false } }
    }
});

function updateChart(type) {
    const d = chartData[type];
    myChart.data.datasets[0].data = d.data;
    myChart.data.datasets[0].borderColor = d.color;
    myChart.data.datasets[0].backgroundColor = d.color + '1A';
    myChart.update();
    document.querySelector('.graph-header h3').textContent = d.title;
    const stats = document.querySelectorAll('.stats div h4');
    stats[0].textContent = d.min;
    stats[1].textContent = d.avg;
    stats[2].textContent = d.max;
}

const tabs = document.querySelectorAll('.chart-tabs .tab');
const tabKeys = ['temperature', 'ph', 'tds', 'uv'];
tabs.forEach(function(tab, index) {
    tab.addEventListener('click', function() {
        tabs.forEach(function(t) { t.classList.remove('active'); });
        this.classList.add('active');
        updateChart(tabKeys[index]);
    });
});

const timeButtons = document.querySelectorAll('.time-filter button');
timeButtons.forEach(function(btn) {
    btn.addEventListener('click', function() {
        timeButtons.forEach(function(b) { b.classList.remove('active'); });
        this.classList.add('active');
    });
});

function updateDateTime() {
    const now = new Date();
    const dateOptions = { day: 'numeric', month: 'long', year: 'numeric' };
    document.getElementById('current-date').textContent = now.toLocaleDateString('id-ID', dateOptions);
    document.getElementById('current-time').textContent = now.toLocaleTimeString('id-ID');
}
updateDateTime();
setInterval(updateDateTime, 1000);
</script>

@endsection