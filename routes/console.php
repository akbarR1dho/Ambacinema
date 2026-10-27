<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Repositories\Interfaces\OrderRepositoryInterface;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('orders:release-expired-holds', function (OrderRepositoryInterface $orderRepo) {
    $count = $orderRepo->releaseExpiredOrders();
    $this->info("Released {$count} expired pending order hold(s).");
})->purpose('Release temporary seat holds for expired pending orders');

Schedule::command('orders:release-expired-holds')->everyMinute();

