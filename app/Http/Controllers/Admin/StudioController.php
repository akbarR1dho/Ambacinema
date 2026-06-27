<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Studio;
use App\Models\StudioType;
use App\Models\Seat;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class StudioController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Studio::with('studioType')->select('studios.*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('type', function($row){
                    return $row->studioType ? $row->studioType->name : 'Regular';
                })
                ->addColumn('price', function($row){
                    if (!$row->studioType) {
                        return 'Rp 50.000';
                    }
                    $prices = [
                        $row->studioType->price_weekday,
                        $row->studioType->price_friday,
                        $row->studioType->price_weekend
                    ];
                    $min = min($prices);
                    $max = max($prices);
                    if ($min == $max) {
                        return 'Rp ' . number_format($min, 0, ',', '.');
                    }
                    return 'Rp ' . number_format($min, 0, ',', '.') . ' - Rp ' . number_format($max, 0, ',', '.');
                })
                ->addColumn('action', function($row){
                    $editUrl = route('admin.studios.edit', $row->id);
                    $deleteUrl = route('admin.studios.destroy', $row->id);
                    $btn = '<div class="flex space-x-2">';
                    $btn .= '<a href="'.$editUrl.'" class="text-blue-500 hover:text-blue-700 p-1 bg-blue-500/10 rounded transition-colors" title="Edit Studio"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></a>';
                    $btn .= '<form action="'.$deleteUrl.'" method="POST" class="inline-block" onsubmit="confirmDelete(event, \'Are you sure you want to delete this studio?\');">
                                '.csrf_field().'
                                '.method_field("DELETE").'
                                <button type="submit" class="text-red-500 hover:text-red-700 p-1 bg-red-500/10 rounded transition-colors" title="Delete Studio"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                            </form>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.studios.index');
    }

    public function create()
    {
        $studioTypes = StudioType::all();
        return view('admin.studios.create', compact('studioTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:studios,name',
            'total_seats' => 'required|integer|min:1',
            'studio_type_id' => 'required|exists:studio_types,id',
        ]);

        DB::transaction(function () use ($request) {
            $studio = Studio::create($request->only('name', 'total_seats', 'studio_type_id'));
            
            // Auto generate seats
            $seats = [];
            for ($i = 1; $i <= $request->total_seats; $i++) {
                // simple naming logic e.g., A1, A2...
                $row = chr(65 + floor(($i - 1) / 10)); // A, B, C...
                $col = (($i - 1) % 10) + 1;
                $seats[] = [
                    'studio_id' => $studio->id,
                    'seat_number' => $row . str_pad($col, 2, '0', STR_PAD_LEFT),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            Seat::insert($seats);
        });

        return redirect()->route('admin.studios.index')->with('success', 'Studio created successfully.');
    }

    public function show(string $id)
    {
        $studio = Studio::findOrFail($id);
        return view('admin.studios.show', compact('studio'));
    }

    public function edit(string $id)
    {
        $studio = Studio::findOrFail($id);
        $studioTypes = StudioType::all();
        return view('admin.studios.edit', compact('studio', 'studioTypes'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:studios,name,' . $id,
            'studio_type_id' => 'required|exists:studio_types,id',
        ]);

        $studio = Studio::findOrFail($id);
        $studio->update($request->only('name', 'studio_type_id'));

        return redirect()->route('admin.studios.index')->with('success', 'Studio updated successfully. Note: Seat count cannot be modified.');
    }

    public function destroy(string $id)
    {
        $studio = Studio::findOrFail($id);
        $studio->delete();

        return redirect()->route('admin.studios.index')->with('success', 'Studio deleted successfully.');
    }

    private function generateSeats(Studio $studio)
    {
        $total = $studio->total_seats;
        $rows = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
        
        $seatCount = 0;
        $seatsToInsert = [];
        foreach ($rows as $row) {
            for ($num = 1; $num <= 10; $num++) {
                if ($seatCount >= $total) break 2;
                $seatsToInsert[] = [
                    'studio_id' => $studio->id,
                    'seat_number' => $row . $num,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $seatCount++;
            }
        }
        
        Seat::insert($seatsToInsert);
    }
}
