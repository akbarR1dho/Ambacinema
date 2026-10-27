<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Services\PaymentServiceInterface;
use Illuminate\Support\Facades\Auth;

class OrderHistoryController extends Controller
{
    protected $orderRepo;
    protected $paymentService;

    public function __construct(
        OrderRepositoryInterface $orderRepo,
        PaymentServiceInterface $paymentService
    ) {
        $this->orderRepo = $orderRepo;
        $this->paymentService = $paymentService;
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

    public function paymentPage($id)
    {
        $order = $this->orderRepo->findUserOrderWithRelations($id, Auth::id());
        
        if ($order->status === 'confirmed') {
            return redirect()->route('orders.show', $id)->with('info', __('This order has already been paid and confirmed.'));
        }

        if ($order->status === 'pending' && $order->isExpired()) {
            $order->update([
                'status' => 'failed',
                'failed_at' => now(),
            ]);
        }

        return view('orders.payment', compact('order'));
    }

    public function cancelHold($id)
    {
        $order = $this->orderRepo->findUserOrderWithRelations($id, Auth::id());

        if ($order->status === 'pending') {
            $order->update([
                'status' => 'failed',
                'failed_at' => now(),
            ]);

            return redirect()->route('booking.seat', $order->showtime_id)
                ->with('success', __('Seat hold has been released. You can now select new seats.'));
        }

        return redirect()->route('orders.show', $order->id);
    }

    public function checkStatus($id)
    {
        $order = $this->orderRepo->findUserOrderWithRelations($id, Auth::id());
        
        if ($order->status === 'pending' && $order->isExpired()) {
            $order->update([
                'status' => 'failed',
                'failed_at' => now(),
            ]);
            return redirect()->route('orders.show', $order->id)->with('error', __('Payment hold period expired.'));
        }

        $this->paymentService->checkStatus($order);
        
        return redirect()->route('orders.show', $order->id)->with('success', __('Ticket status updated successfully.'));
    }

    public function pollStatus($id)
    {
        $order = $this->orderRepo->findUserOrderWithRelations($id, Auth::id());

        if ($order->status === 'confirmed') {
            return response()->json(['status' => 'confirmed']);
        }

        if ($order->status === 'pending' && $order->isExpired()) {
            $order->update([
                'status' => 'failed',
                'failed_at' => now(),
            ]);
            return response()->json(['status' => 'failed', 'reason' => 'expired']);
        }
        
        $result = $this->paymentService->checkStatus($order);
        return response()->json($result);
    }

    public function midtransCallback(Request $request)
    {
        $this->paymentService->handleNotification($request->all());
        return response()->json(['message' => 'Notification processed successfully']);
    }
}
