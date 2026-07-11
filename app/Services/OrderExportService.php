<?php

namespace App\Services;

use App\Repositories\Interfaces\OrderRepositoryInterface;
use Spatie\SimpleExcel\SimpleExcelWriter;

class OrderExportService
{
    protected $orderRepo;

    public function __construct(OrderRepositoryInterface $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }

    public function downloadExcel(array $filters)
    {
        $orders = $this->orderRepo->getFilteredOrders($filters);

        $fileName = 'orders_export_'.date('Y-m-d_H-i-s').'.xlsx';

        $writer = SimpleExcelWriter::streamDownload($fileName);

        foreach ($orders as $index => $order) {
            $writer->addRow([
                'No' => $index + 1,
                'Customer' => $order->user->name ?? '-',
                'Movie' => $order->showtime->movie->title ?? '-',
                'Studio' => $order->showtime->studio->name ?? '-',
                'Total Price' => $order->total_price,
                'Status' => ucfirst($order->status),
                'Booked At' => $order->pending_at_local ? $order->pending_at_local->format('Y-m-d H:i:s') : '-',
                'Confirmed At' => $order->confirmed_at_local ? $order->confirmed_at_local->format('Y-m-d H:i:s') : '-',
                'Failed At' => $order->failed_at_local ? $order->failed_at_local->format('Y-m-d H:i:s') : '-',
                'Payment Method' => ucfirst($order->payment_type) ?: '-',
            ]);
        }

        return $writer->toBrowser();
    }
}
