<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Order::with(['user', 'showtime.movie'])->select('orders.*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('user_name', function($row){
                    return $row->user->name;
                })
                ->addColumn('movie_title', function($row){
                    return $row->showtime->movie->title;
                })
                ->editColumn('total_price', function($row){
                    return 'Rp ' . number_format($row->total_price, 0, ',', '.');
                })
                ->editColumn('status', function($row){
                    $color = $row->status == 'confirmed' ? 'green' : 'yellow';
                    return '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-'.$color.'-900/50 text-'.$color.'-400 border border-'.$color.'-500">'.ucfirst($row->status).'</span>';
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
        $order = Order::with(['user', 'showtime.movie', 'showtime.studio', 'seats'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }
}
