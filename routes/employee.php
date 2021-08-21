<?php

use App\Http\Controllers\Employee\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Employee\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Employee\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Employee\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Employee\Auth\NewPasswordController;
use App\Http\Controllers\Employee\Auth\PasswordResetLinkController;
use App\Http\Controllers\Employee\Auth\RegisteredUserController;
use App\Http\Controllers\Employee\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('employee.welcome');
});

Route::get('/dashboard', function () {
    return view('employee.dashboard');
})->middleware(['auth:employees'])->name('dashboard');

Route::get('/register', [RegisteredUserController::class, 'create'])
    ->middleware('guest')
    ->name('register');

Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest');

Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest');

Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.update');

Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
    ->middleware('auth:employees')
    ->name('verification.notice');

Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['auth:employees', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth:employees', 'throttle:6,1'])
    ->name('verification.send');

Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
    ->middleware('auth:employees')
    ->name('password.confirm');

Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])
    ->middleware('auth:employees');

Route::post('/logout', [AuthenticatedSessionController::class, 'delete'])
    ->middleware('auth:employees')
    ->name('logout');
