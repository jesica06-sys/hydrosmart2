<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScheduleController;
use Laravel\Socialite\Facades\Socialite;

// Halaman Login
Route::get('/', function () {
    return view('login');
})->name('login');

Route::post('/login', function () {
    $email = request('email');
    $password = request('password');
    $name = ucfirst(explode('@', $email)[0]);

    // Simpan ke session
    session(['userName' => $name, 'userEmail' => $email]);

    // Simpan ke Firebase
    try {
        $firebase = new \App\Services\FirebaseService();
        $firebase->setData('users/' . str_replace('.', '_', $email), [
            'name'      => $name,
            'email'     => $email,
            'last_login' => now()->toDateTimeString(),
        ]);
    } catch (\Exception $e) {
        // lanjut meski Firebase gagal
    }

    return redirect('/dashboard');
})->name('login.post');

Route::get('/dashboard', function () {
    try {
        $firebase = new \App\Services\FirebaseService();
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
    $schedules = \App\Models\Schedule::all();
    return view('setting', compact('schedules'));
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
    $user = Socialite::driver('google')->stateless()->user();
    session([
        'userName'    => $user->getName(),
        'userEmail'   => $user->getEmail(),
        'userPicture' => $user->getAvatar(),
    ]);
    return redirect('/dashboard');
});