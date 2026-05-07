<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScheduleController;
use Laravel\Socialite\Facades\Socialite;

// Halaman Login
Route::get('/', function () {
    return view('login');
})->name('login');

Route::post('/login', function () {
    $email    = request('email');
    $password = request('password');

    try {
        $firebase = new \App\Services\FirebaseService();
        $user     = $firebase->getUser($email);

        if (!$user || !password_verify($password, $user['password'])) {
            return back()->withErrors(['login' => 'Email atau password salah, atau akun tidak diizinkan.']);
        }

        $firebase->updateLastLogin($email);

        session([
            'userName'  => $user['name'],
            'userEmail' => $email,
        ]);

        return redirect('/dashboard');

    } catch (\Exception $e) {
        return back()->withErrors(['login' => 'Gagal terhubung ke database: ' . $e->getMessage()]);
    }

})->name('login.post');

// Dashboard
Route::get('/dashboard', function () {
    try {
        $firebase   = new \App\Services\FirebaseService();
        $sensorData = $firebase->getData('sensors');
    } catch (\Exception $e) {
        $sensorData = null;
    }
    return view('dashboard', compact('sensorData'));
})->name('dashboard');

Route::get('/monitoring', function () {
    return view('monitoring');
})->name('monitoring');

Route::get('/setting', function () {
    return view('setting');
})->name('setting');

Route::get('/profile', function () {
    return view('profile');
})->name('profile');

// Schedule
Route::post('/schedule', [ScheduleController::class, 'store'])->name('schedule.store');
Route::delete('/schedule/{id}', [ScheduleController::class, 'destroy'])->name('schedule.destroy');

// Google Login
Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
})->name('auth.google');

Route::get('/auth/google/callback', function () {
    $googleUser = Socialite::driver('google')->stateless()->user();
    $email      = $googleUser->getEmail();

    try {
        $firebase = new \App\Services\FirebaseService();
        $user     = $firebase->getUser($email);

        if (!$user) {
            return redirect('/')->withErrors(['login' => 'Akun Google ini tidak diizinkan masuk.']);
        }

        $firebase->updateLastLogin($email);

        session([
            'userName'    => $googleUser->getName(),
            'userEmail'   => $email,
            'userPicture' => $googleUser->getAvatar(),
        ]);

        return redirect('/dashboard');

    } catch (\Exception $e) {
        return redirect('/')->withErrors(['login' => 'Gagal terhubung ke database.']);
    }
});