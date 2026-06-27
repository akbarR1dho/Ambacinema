<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderHistoryController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/movie/{id}', [BookingController::class, 'showMovie'])->name('movie.show');
Route::get('/booking/showtime/{id}', [BookingController::class, 'selectSeat'])->name('booking.seat');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process')->middleware('auth');
Route::get('/orders', [OrderHistoryController::class, 'index'])->name('orders.index')->middleware('auth');
Route::get('/orders/{id}', [OrderHistoryController::class, 'show'])->name('orders.show')->middleware('auth');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

use App\Http\Controllers\Admin\StudioController;
use App\Http\Controllers\Admin\MovieController;
use App\Http\Controllers\Admin\ShowtimeController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\StudioTypeController;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('studio-types', StudioTypeController::class)->except(['show']);
    Route::resource('studios', StudioController::class);
    Route::resource('movies', MovieController::class);
    Route::resource('showtimes', ShowtimeController::class);
    Route::resource('orders', OrderController::class)->only(['index', 'show']);
});

// Deployment helper route
Route::get('/install-db', function() {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate:fresh', ['--seed' => true]);
        return 'Database successfully migrated and seeded!';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});
