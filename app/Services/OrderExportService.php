<?php

namespace App\Services;

use Spatie\SimpleExcel\SimpleExcelWriter;
use App\Repositories\Interfaces\OrderRepositoryInterface;

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

        $fileName = 'orders_export_' . date('Y-m-d_H-i-s') . '.xlsx';

        $writer = SimpleExcelWriter::streamDownload($fileName);
        
        foreach ($orders as $index => $order) {
            $writer->addRow([
                'No' => $index + 1,
                'Customer' => $order->user->name ?? '-',
                'Movie' => $order->showtime->movie->title ?? '-',
                'Studio' => $order->showtime->studio->name ?? '-',
                'Total Price' => $order->total_price,
                'Status' => ucfirst($order->status),
                'Date' => $order->created_at->format('Y-m-d H:i:s'),
            ]);
        }
        
        return $writer->toBrowser();
    }
}
