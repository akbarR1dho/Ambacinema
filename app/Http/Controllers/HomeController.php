<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;

class HomeController extends Controller
{
    public function index()
    {
        $movies = Movie::whereHas('showtimes', function ($query) {
            $query->whereDate('start_time', now()->toDateString())
                  ->where('start_time', '>', now());
        })->latest()->get();
        
        return view('home', compact('movies'));
    }
}
