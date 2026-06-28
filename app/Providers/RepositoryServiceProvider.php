<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\Interfaces\BaseRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Interfaces\MovieRepositoryInterface;
use App\Repositories\Eloquent\MovieRepository;
use App\Repositories\Interfaces\ShowtimeRepositoryInterface;
use App\Repositories\Eloquent\ShowtimeRepository;
use App\Repositories\Interfaces\SeatRepositoryInterface;
use App\Repositories\Eloquent\SeatRepository;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Eloquent\OrderRepository;
use App\Repositories\Interfaces\StudioRepositoryInterface;
use App\Repositories\Eloquent\StudioRepository;
use App\Repositories\Interfaces\StudioTypeRepositoryInterface;
use App\Repositories\Eloquent\StudioTypeRepository;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Eloquent\UserRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(MovieRepositoryInterface::class, MovieRepository::class);
        $this->app->bind(ShowtimeRepositoryInterface::class, ShowtimeRepository::class);
        $this->app->bind(SeatRepositoryInterface::class, SeatRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(StudioRepositoryInterface::class, StudioRepository::class);
        $this->app->bind(StudioTypeRepositoryInterface::class, StudioTypeRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
