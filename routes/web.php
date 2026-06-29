<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderHistoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\StudioController;
use App\Http\Controllers\Admin\MovieController;
use App\Http\Controllers\Admin\ShowtimeController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\StudioTypeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\UserDashboardController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/movie/{id}', [BookingController::class, 'showMovie'])->name('movie.show');
Route::get('/booking/showtime/{id}', [BookingController::class, 'selectSeat'])->name('booking.seat')->middleware('auth');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process')->middleware('auth');
Route::get('/orders', [OrderHistoryController::class, 'index'])->name('orders.index')->middleware('auth');
Route::get('/orders/{id}', [OrderHistoryController::class, 'show'])->name('orders.show')->middleware('auth');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session()->put('app_locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'chartData'])->name('dashboard.chartData');
    
    Route::get('/api/studio-types', [StudioTypeController::class, 'apiIndex'])->name('api.studio-types');
    Route::get('/api/studios', [StudioController::class, 'apiIndex'])->name('api.studios');
    Route::get('/api/movies', [MovieController::class, 'apiIndex'])->name('api.movies');

    Route::resource('studio-types', StudioTypeController::class)->except(['show']);
    Route::resource('studios', StudioController::class);
    Route::resource('movies', MovieController::class);
    Route::resource('showtimes', ShowtimeController::class);
    Route::resource('orders', OrderController::class)->only(['index', 'show']);
});
