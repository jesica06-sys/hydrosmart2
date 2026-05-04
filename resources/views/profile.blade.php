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

        <div class="profile active-profile" onclick="window.location.href='{{ url('/profile') }}'" style="cursor:pointer;">
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
    <div class="main">

        <!-- HEADER -->
        <div class="header">
            <div>
                <h2>Profile</h2>
                <p>Complete Biodata</p>
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
                <span class="online">Online System</span>
            </div>
        </div>

        <!-- PROFILE CONTENT -->
        <div class="main-profile">

            <div class="profile-header-card">
                <img src="{{ asset('img/profile.png') }}" id="profile-picture">
                <div>
                    <h2>{{ session('userName', 'Admin') }}</h2>
                    <p>{{ session('userEmail', '') }}</p>
                </div>
            </div>

            <p class="biodata-title">Biodata</p>

            <div class="biodata-item">
                <img src="{{ asset('img/profile.png') }}" style="border-radius:50%;">
                <div>
                    <p class="bio-label">Name</p>
                    <p class="bio-value">{{ session('userName', 'Admin') }}</p>
                </div>
            </div>

            <div class="biodata-item">
                <img src="{{ asset('img/email3.png') }}">
                <div>
                    <p class="bio-label">Email</p>
                    <p class="bio-value">{{ session('userEmail', '') }}</p>
                </div>
            </div>

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
</script>

@endsection