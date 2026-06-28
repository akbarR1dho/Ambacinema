<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\MovieRepositoryInterface;

class HomeController extends Controller
{
    protected $movieRepo;

    public function __construct(MovieRepositoryInterface $movieRepo)
    {
        $this->movieRepo = $movieRepo;
    }

    public function index()
    {
        $movies = $this->movieRepo->getActiveMoviesForToday();
        

        return view('home', compact('movies'));
    }
}
