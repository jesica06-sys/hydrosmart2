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

        <div class="status">
            <div class="status-top">
                <p><strong>Status System</strong></p>
                <span class="online-text">Online</span>
            </div>
            <div class="status-info">
                <img src="{{ asset('img/wifi.png') }}" class="wifi-icon">
                <small>The system runs normally</small>
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
        <div class="monitoring-content">

            <!-- GRAPH -->
            <div class="monitoring-graph">
                <div class="graph-header">
                    <h3 id="chart-title">Temperature Chart (°C)</h3>
                    <div class="time-filter">
                        <button class="active">1 jam</button>
                        <button>6 jam</button>
                        <button>12 jam</button>
                        <button>1 hari</button>
                        <button>7 hari</button>
                    </div>
                </div>

                <canvas id="monitoringChart"></canvas>

                <div class="stats">
                    <div>
                        <p>Minimum</p>
                        <h4 id="stat-min">25.0 °C</h4>
                        <span class="time" id="stat-min-time">08:00</span>
                    </div>
                    <div>
                        <p>Average</p>
                        <h4 id="stat-avg">28.2 °C</h4>
                        <span class="time">Today</span>
                    </div>
                    <div>
                        <p>Maximum</p>
                        <h4 id="stat-max">31.0 °C</h4>
                        <span class="time" id="stat-max-time">12:00</span>
                    </div>
                </div>
            </div>

            <!-- RIGHT PANEL -->
            <div class="right-panel" id="mon-panel">
                <div class="status-box">
                    <h3>System Status</h3>
                    <p>Sensor <span>Normal</span></p>
                    <p>Water Cycle <span>Walk</span></p>
                    <p>Internet Connection <span>Online</span></p>
                </div>

                <div class="history">
                    <h3>Latest Data History</h3>
                    <div class="history-item">
                        <img src="{{ asset('img/temperature.png') }}">
                        <p>Suhu</p>
                        <div class="history-text">
                            <p>28.0 °C</p>
                            <span class="update">23 April 2026 12:00</span>
                        </div>
                    </div>
                    <div class="history-item">
                        <img src="{{ asset('img/water.png') }}">
                        <p>Water pH</p>
                        <div class="history-text">
                            <p>6.5</p>
                            <span class="update">23 April 2026 12:00</span>
                        </div>
                    </div>
                    <div class="history-item">
                        <img src="{{ asset('img/tds.png') }}">
                        <p>Water TDS</p>
                        <div class="history-text">
                            <p>700 ppm</p>
                            <span class="update">23 April 2026 12:00</span>
                        </div>
                    </div>
                    <div class="history-item">
                        <img src="{{ asset('img/uv.png') }}">
                        <p>UV Light</p>
                        <div class="history-text">
                            <p>3.2</p>
                            <span class="update">23 April 2026 12:00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const chartData = {
    temperature: {
        title: 'Temperature Chart (°C)',
        data1: [28.0, 27.5, 28.5, 29.0, 28.2, 27.8, 28.8, 29.2, 28.5, 28.0],
        data2: [27.0, 26.5, 27.5, 28.0, 27.2, 26.8, 27.8, 28.2, 27.5, 27.0],
        color1: '#16A34A', color2: '#86efac',
        min: '25.0 °C', avg: '28.2 °C', max: '31.0 °C',
        minTime: '08:00', maxTime: '12:00'
    },
    ph: {
        title: 'Water pH Chart',
        data1: [6.5, 6.8, 7.0, 6.9, 6.4, 6.6, 7.1, 6.7, 6.5, 6.8],
        data2: [6.2, 6.5, 6.8, 7.0, 6.3, 6.7, 6.9, 6.6, 6.4, 6.7],
        color1: '#3B82F6', color2: '#93c5fd',
        min: '6.4', avg: '6.7', max: '7.1',
        minTime: '09:00', maxTime: '13:00'
    },
    tds: {
        title: 'Water TDS Chart (ppm)',
        data1: [700, 720, 680, 750, 710, 690, 730, 700, 715, 695],
        data2: [680, 700, 660, 730, 690, 670, 710, 680, 695, 675],
        color1: '#8B5CF6', color2: '#c4b5fd',
        min: '660 ppm', avg: '700 ppm', max: '750 ppm',
        minTime: '10:00', maxTime: '14:00'
    },
    uv: {
        title: 'UV Light Chart',
        data1: [3.2, 3.5, 3.8, 3.1, 2.9, 3.4, 3.7, 3.3, 3.0, 3.6],
        data2: [3.0, 3.3, 3.6, 2.9, 2.7, 3.2, 3.5, 3.1, 2.8, 3.4],
        color1: '#F59E0B', color2: '#fcd34d',
        min: '2.7', avg: '3.3', max: '3.8',
        minTime: '08:30', maxTime: '14:45'
    }
};

const labels = ['08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00'];

const ctx = document.getElementById('monitoringChart');
const myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [
            {
                label: 'Data',
                data: chartData.temperature.data1,
                borderColor: chartData.temperature.color1,
                borderWidth: 2, tension: 0.4,
                pointBackgroundColor: '#fff',
                pointBorderColor: chartData.temperature.color1,
                pointRadius: 4, fill: false
            },
            {
                label: 'Comparison',
                data: chartData.temperature.data2,
                borderColor: chartData.temperature.color2,
                borderWidth: 2, tension: 0.4,
                pointBackgroundColor: '#fff',
                pointBorderColor: chartData.temperature.color2,
                pointRadius: 4, fill: false
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: false } }
    }
});

function updateChart(type) {
    const d = chartData[type];
    myChart.data.datasets[0].data = d.data1;
    myChart.data.datasets[0].borderColor = d.color1;
    myChart.data.datasets[0].pointBorderColor = d.color1;
    myChart.data.datasets[1].data = d.data2;
    myChart.data.datasets[1].borderColor = d.color2;
    myChart.data.datasets[1].pointBorderColor = d.color2;
    myChart.update();
    document.getElementById('chart-title').textContent = d.title;
    document.getElementById('stat-min').textContent = d.min;
    document.getElementById('stat-avg').textContent = d.avg;
    document.getElementById('stat-max').textContent = d.max;
    document.getElementById('stat-min-time').textContent = d.minTime;
    document.getElementById('stat-max-time').textContent = d.maxTime;
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