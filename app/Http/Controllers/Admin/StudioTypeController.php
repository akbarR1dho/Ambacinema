<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudioType;
use Yajra\DataTables\Facades\DataTables;

class StudioTypeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = StudioType::select('*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('price', function($row){
                    $prices = [
                        $row->price_weekday,
                        $row->price_friday,
                        $row->price_weekend
                    ];
                    $min = min($prices);
                    $max = max($prices);
                    
                    if ($min == $max) {
                        return 'Rp ' . number_format($min, 0, ',', '.');
                    }
                    return 'Rp ' . number_format($min, 0, ',', '.') . ' - Rp ' . number_format($max, 0, ',', '.');
                })
                ->addColumn('action', function($row){
                    $editUrl = route('admin.studio-types.edit', $row->id);
                    $deleteUrl = route('admin.studio-types.destroy', $row->id);
                    $btn = '<div class="flex space-x-2">';
                    $btn .= '<a href="'.$editUrl.'" class="text-blue-500 hover:text-blue-700 p-1 bg-blue-500/10 rounded transition-colors" title="Edit Studio Type"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></a>';
                    
                    // Don\'t allow deleting if it\'s the default Regular type (ID 1)
                    if ($row->id != 1) {
                        $btn .= '<form action="'.$deleteUrl.'" method="POST" class="inline-block" onsubmit="confirmDelete(event, \'Are you sure you want to delete this studio type?\');">
                                    '.csrf_field().'
                                    '.method_field("DELETE").'
                                    <button type="submit" class="text-red-500 hover:text-red-700 p-1 bg-red-500/10 rounded transition-colors" title="Delete Studio Type"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                </form>';
                    }
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.studio_types.index');
    }

    public function create()
    {
        return view('admin.studio_types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:studio_types,name',
            'price_weekday' => 'required|integer|min:0',
            'price_friday' => 'required|integer|min:0',
            'price_weekend' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        StudioType::create($request->all());

        return redirect()->route('admin.studio-types.index')->with('success', 'Studio Type created successfully.');
    }

    public function edit(string $id)
    {
        $studioType = StudioType::findOrFail($id);
        return view('admin.studio_types.edit', compact('studioType'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:studio_types,name,' . $id,
            'price_weekday' => 'required|integer|min:0',
            'price_friday' => 'required|integer|min:0',
            'price_weekend' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $studioType = StudioType::findOrFail($id);
        $studioType->update($request->all());

        return redirect()->route('admin.studio-types.index')->with('success', 'Studio Type updated successfully.');
    }

    public function destroy(string $id)
    {
        if ($id == 1) {
            return redirect()->route('admin.studio-types.index')->with('error', 'Cannot delete the default Regular studio type.');
        }
        
        $studioType = StudioType::findOrFail($id);
        
        // Re-assign existing studios to Regular (ID 1)
        foreach ($studioType->studios as $studio) {
            $studio->update(['studio_type_id' => 1]);
        }
        
        $studioType->delete();

        return redirect()->route('admin.studio-types.index')->with('success', 'Studio Type deleted successfully.');
    }
}
