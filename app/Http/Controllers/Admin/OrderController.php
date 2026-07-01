<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    protected $orderRepo;

    public function __construct(OrderRepositoryInterface $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->orderRepo->getOrdersDatatable($request->only(['studio_id', 'movie_id', 'status']));

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('user_name', function($row){
                    return $row->user->name;
                })
                ->addColumn('movie_title', function($row){
                    return $row->showtime->movie->title;
                })
                ->addColumn('studio_name', function($row){
                    return $row->showtime->studio->name;
                })
                ->editColumn('total_price', function($row){
                    return 'Rp ' . number_format($row->total_price, 0, ',', '.');
                })
                ->editColumn('status', function($row){
                    $classes = match($row->status) {
                        'confirmed' => 'border-green-500 text-green-600 bg-green-50',
                        'pending' => 'border-orange-500 text-orange-500 bg-orange-50',
                        'failed', 'expired' => 'border-red-500 text-red-600 bg-red-50',
                        default => 'border-slate-500 text-slate-600 bg-slate-50',
                    };
                    return '<span class="px-3 py-1 text-xs font-bold rounded-full border '.$classes.'">'.ucfirst($row->status).'</span>';
                })
                ->addColumn('action', function($row){
                    $showUrl = route('admin.orders.show', $row->id);
                    $btn = '<div class="flex space-x-2">';
                    $btn .= '<a href="'.$showUrl.'" class="text-blue-500 hover:text-blue-700 p-1 bg-blue-500/10 rounded transition-colors" title="View Details"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg></a>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('admin.orders.index');
    }

    public function show(string $id)
    {
        $order = $this->orderRepo->findAdminOrderWithRelations($id);
        return view('admin.orders.show', compact('order'));
    }

    public function export(Request $request, \App\Services\OrderExportService $exportService)
    {
        return $exportService->downloadExcel($request->only(['studio_id', 'movie_id', 'status']));
    }
}
