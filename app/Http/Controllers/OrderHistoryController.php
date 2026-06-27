<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderHistoryController extends Controller
{
    public function index()
    {
        $orders = Order::with(['showtime.movie', 'showtime.studio', 'seats'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['showtime.movie', 'showtime.studio', 'seats'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
            
        return view('orders.show', compact('order'));
    }
}
