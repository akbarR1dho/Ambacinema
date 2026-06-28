<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class OrderHistoryController extends Controller
{
    protected $orderRepo;

    public function __construct(OrderRepositoryInterface $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }

    public function index(Request $request)
    {
        $filter = $request->query('date_filter', 'today');
        
        $orders = $this->orderRepo->getOrdersForUserWithCursor(Auth::id(), $filter, 6);
        
        if ($request->ajax()) {
            return response()->json([
                'html' => view('orders._cards', compact('orders'))->render(),
                'nextCursor' => $orders->nextCursor() ? $orders->nextCursor()->encode() : null
            ]);
        }
            
        return view('orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = $this->orderRepo->findUserOrderWithRelations($id, Auth::id());
            
        return view('orders.show', compact('order'));
    }
}
