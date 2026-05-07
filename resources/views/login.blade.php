@extends('layouts.app')

@section('content')

<div class="container">

    <!-- LEFT SIDE -->
    <div class="left">
        <div class="overlay"></div>
        <div class="left-content">
            <img src="{{ asset('img/daun.png') }}" class="logo-daun">
            <h2>Smart</h2>
            <h1>Hydroponic</h1>
            <p>IoT Hydroponic System</p>
        </div>
    </div>

    <!-- RIGHT SIDE -->
    <div class="right">
        <div class="form-box">
            <h1>Welcome Back</h1>
            <p>Sign in to your account</p>

            @if ($errors->any())
                <div class="alert-error">
                    {{ $errors->first('login') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="input-group">
                    <img src="{{ asset('img/email.png') }}" class="icon-left">
                    <input type="email" name="email" placeholder="Enter your email" required>
                </div>

                <div class="input-group">
                    <img src="{{ asset('img/kunci.png') }}" class="icon-left">
                    <input type="password" name="password" id="password" placeholder="Enter your password" required>
                    <img src="{{ asset('img/sandi-off.png') }}" class="icon-right" id="icon-password" onclick="togglePassword()">
                </div>

                <button type="submit" class="login-btn">Login</button>
            </form>

            {{-- Tambahan tombol Google --}}
            <div class="divider">
                <span>or continue with</span>
            </div>

            <a href="{{ route('auth.google') }}" class="google-btn">
                <img src="https://developers.google.com/identity/images/g-logo.png" width="20">
                Continue with Google
            </a>

        </div>
    </div>

</div>

<script>
function togglePassword() {
    const pass = document.getElementById("password");
    const icon = document.getElementById("icon-password");
    if (pass.type === "password") {
        pass.type = "text";
        icon.src = "{{ asset('img/sandi-open.png') }}";
    } else {
        pass.type = "password";
        icon.src = "{{ asset('img/sandi-off.png') }}";
    }
}
</script>

@endsection