<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\StudioRepositoryInterface;
use App\Repositories\Interfaces\MovieRepositoryInterface;
use App\Repositories\Interfaces\ShowtimeRepositoryInterface;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $orderRepo;
    protected $studioRepo;
    protected $movieRepo;
    protected $showtimeRepo;

    public function __construct(
        OrderRepositoryInterface $orderRepo,
        StudioRepositoryInterface $studioRepo,
        MovieRepositoryInterface $movieRepo,
        ShowtimeRepositoryInterface $showtimeRepo
    ) {
        $this->orderRepo = $orderRepo;
        $this->studioRepo = $studioRepo;
        $this->movieRepo = $movieRepo;
        $this->showtimeRepo = $showtimeRepo;
    }

    public function index()
    {
        $studios = $this->studioRepo->all();
        $movies = $this->movieRepo->all();
        $total = [
            'studio' => $this->studioRepo->all()->count(),
            'movie' => $this->movieRepo->all()->count(),
            'order' => $this->orderRepo->all()->count(),
            'showtime' => $this->showtimeRepo->all()->count(),
        ];
        
        return view('admin.dashboard', compact('studios', 'movies', 'total'));
    }

    public function chartData(Request $request)
    {
        $data = $this->orderRepo->getChartData($request->all());

        return response()->json([
            'labels' => $data->pluck('date'),
            'data' => $data->pluck('revenue')
        ]);
    }
}
