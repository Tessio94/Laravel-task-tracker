<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware('guest')->group(function() {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.post')
        ->middleware('throttle:login');
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    Route::get('/forgot-password', [PasswordResetController::class, 'showPasswordResetRequestForm'])
        ->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendPasswordResetEmail'])
        ->middleware('throttle:password-reset-request')
        ->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showPasswordResetForm'])
        ->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])
        ->middleware('throttle:password-reset')
        ->name('password.store');
});

Route::middleware('auth')->group(function() {
    Route::get('/email/verify', [EmailVerificationController::class, 'index'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware(['signed', ])
        ->name('verification.verify');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
        ->middleware('throttle:5,1') // rate limit of 5 request per limit
        ->name('verification.send');
});

Route::middleware(['auth', 'verified'])->group(function() {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('categories', CategoryController::class)
        ->except(['show'])
        ->middlewareFor(['edit, update, destroy'],'can:manage,category');

    Route::resource('tasks', TaskController::class)
        ->except('show')
        ->middlewareFor(['edit, update, destroy'],'can:manage,task');

    Route::patch('/tasks/{task}/toggle-completion', [TaskController::class, 'toggleCompletion'])
        ->name('tasks.toggle-completion')
        ->middleware('can:manage,task');

    Route::redirect('/', '/dashboard');
});
