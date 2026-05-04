<?php

use Illuminate\Support\Facades\Route;

// Halaman Login
Route::get('/', function () {
    return view('login');
})->name('login');

Route::post('/login', function () {
    $email = request('email');
    $name = ucfirst(explode('@', $email)[0]); // huruf pertama kapital
    
    session(['userName' => $name, 'userEmail' => $email]);
    
    return redirect('/dashboard');
})->name('login.post');

Route::get('/dashboard', function () {
    return view('dashboard');
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

use Laravel\Socialite\Facades\Socialite;

Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
})->name('auth.google');

Route::get('/auth/google/callback', function () {
    $user = Socialite::driver('google')->stateless()->user();
    
    session([
        'userName'  => $user->getName(),
        'userEmail' => $user->getEmail(),
        'userPicture' => $user->getAvatar(),
    ]);
    
    return redirect('/dashboard');
});