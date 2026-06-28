<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\StudioRepositoryInterface;
use App\Repositories\Interfaces\MovieRepositoryInterface;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $orderRepo;
    protected $studioRepo;
    protected $movieRepo;

    public function __construct(
        OrderRepositoryInterface $orderRepo,
        StudioRepositoryInterface $studioRepo,
        MovieRepositoryInterface $movieRepo
    ) {
        $this->orderRepo = $orderRepo;
        $this->studioRepo = $studioRepo;
        $this->movieRepo = $movieRepo;
    }

    public function index()
    {
        $studios = $this->studioRepo->all();
        $movies = $this->movieRepo->all();
        
        return view('admin.dashboard', compact('studios', 'movies'));
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
