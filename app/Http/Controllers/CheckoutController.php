<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Booking\CheckoutRequest;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\ShowtimeRepositoryInterface;
use App\Repositories\Interfaces\SeatRepositoryInterface;
use App\Services\PaymentServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    protected $checkoutService;

    public function __construct(
        \App\Services\CheckoutService $checkoutService
    ) {
        $this->checkoutService = $checkoutService;
    }

    public function process(CheckoutRequest $request)
    {
        $validated = $request->validated();

        try {
            $paymentType = $request->input('payment_type');
            $userId = Auth::id();
            
            $order = $this->checkoutService->processCheckout($validated, $paymentType, $userId);

            return redirect()->route('orders.pay', $order->id)->with('success', 'Order created successfully. Please complete your payment.');
        } catch (\Exception $e) {
            return back()->withErrors(['seats' => 'Failed to process order: ' . $e->getMessage()]);
        }
    }
}
