<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStudioRequest;
use App\Http\Requests\Admin\UpdateStudioRequest;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\StudioRepositoryInterface;
use App\Repositories\Interfaces\StudioTypeRepositoryInterface;
use App\Repositories\Interfaces\SeatRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class StudioController extends Controller
{
    protected $studioRepo;
    protected $studioTypeRepo;
    protected $seatRepo;

    public function __construct(
        StudioRepositoryInterface $studioRepo,
        StudioTypeRepositoryInterface $studioTypeRepo,
        SeatRepositoryInterface $seatRepo
    ) {
        $this->studioRepo = $studioRepo;
        $this->studioTypeRepo = $studioTypeRepo;
        $this->seatRepo = $seatRepo;
    }

    public function apiIndex(Request $request)
    {
        $query = $this->studioRepo->getStudiosDatatable($request->only('search'));

        $studios = $query->orderBy('name', 'asc')->cursorPaginate(5);

        return response()->json($studios);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->studioRepo->getStudiosDatatable($request->only('type_filter'));

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('type', function($row){
                    return $row->studioType ? $row->studioType->name : 'Regular';
                })
                ->addColumn('action', function($row){
                    $editUrl = route('admin.studios.edit', $row->id);
                    $deleteUrl = route('admin.studios.destroy', $row->id);
                    $btn = '<div class="flex space-x-2">';
                    $btn .= '<a href="'.$editUrl.'" class="text-blue-500 hover:text-blue-700 p-1 bg-blue-500/10 rounded transition-colors" title="Edit Studio"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></a>';
                    $btn .= '<form action="'.$deleteUrl.'" method="POST" class="inline-block" onsubmit="confirmDelete(event, \'' . __('Are you sure you want to delete this studio?') . '\');">
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
        
        $studioTypes = $this->studioTypeRepo->all();
        return view('admin.studios.index', compact('studioTypes'));
    }

    public function create()
    {
        $studioTypes = $this->studioTypeRepo->all();
        return view('admin.studios.create', compact('studioTypes'));
    }

    public function store(StoreStudioRequest $request)
    {
        $this->studioRepo->createWithSeats($request->only('name', 'total_seats', 'studio_type_id'));

        return redirect()->route('admin.studios.index')->with('success', 'Studio created successfully.');
    }

    public function show(string $id)
    {
        $studio = $this->studioRepo->find($id);
        return view('admin.studios.show', compact('studio'));
    }

    public function edit(string $id)
    {
        $studio = $this->studioRepo->find($id);
        $studioType = $this->studioTypeRepo->find($studio->studio_type_id)->name;
        return view('admin.studios.edit', compact('studio', 'studioType'));
    }

    public function update(UpdateStudioRequest $request, string $id)
    {
        $this->studioRepo->update($id, $request->only('name', 'studio_type_id'));

        return redirect()->route('admin.studios.index')->with('success', 'Studio updated successfully. Note: Seat count cannot be modified.');
    }

    public function destroy(string $id)
    {
        $this->studioRepo->delete($id);

        return redirect()->route('admin.studios.index')->with('success', 'Studio deleted successfully.');
    }
}
