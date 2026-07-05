<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreShowtimeRequest;
use App\Http\Requests\Admin\UpdateShowtimeRequest;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\ShowtimeRepositoryInterface;
use App\Repositories\Interfaces\MovieRepositoryInterface;
use App\Repositories\Interfaces\StudioRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class ShowtimeController extends Controller
{
    protected $showtimeRepo;
    protected $movieRepo;
    protected $studioRepo;

    public function __construct(
        ShowtimeRepositoryInterface $showtimeRepo,
        MovieRepositoryInterface $movieRepo,
        StudioRepositoryInterface $studioRepo
    ) {
        $this->showtimeRepo = $showtimeRepo;
        $this->movieRepo = $movieRepo;
        $this->studioRepo = $studioRepo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $filters = [
                'movie_id' => $request->get('movie_id'),
                'studio_id' => $request->get('studio_id'),
            ];
            $data = $this->showtimeRepo->getShowtimesDatatable($filters);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('movie_title', function($row){
                    return $row->movie->title;
                })
                ->addColumn('studio_name', function($row){
                    return $row->studio->name;
                })
                ->editColumn('start_time', function($row){
                    return Carbon::parse($row->start_time)->format('Y-m-d H:i');
                })
                ->editColumn('end_time', function($row){
                    return Carbon::parse($row->end_time)->format('Y-m-d H:i');
                })
                ->addColumn('action', function($row){
                    $editUrl = route('admin.showtimes.edit', $row->id);
                    $deleteUrl = route('admin.showtimes.destroy', $row->id);
                    $btn = '<div class="flex space-x-2">';
                    $btn .= '<a href="'.$editUrl.'" class="text-blue-500 hover:text-blue-700 p-1 bg-blue-500/10 rounded transition-colors" title="Edit Showtime"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></a>';
                    $btn .= '<form action="'.$deleteUrl.'" method="POST" class="inline-block" onsubmit="confirmDelete(event, \'' . __('Are you sure you want to delete this showtime?') . '\');">
                                '.csrf_field().'
                                '.method_field("DELETE").'
                                <button type="submit" class="text-red-500 hover:text-red-700 p-1 bg-red-500/10 rounded transition-colors" title="Delete Showtime"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                            </form>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        $movies = $this->movieRepo->all();
        $studios = $this->studioRepo->all();
        return view('admin.showtimes.index', compact('movies', 'studios'));
    }

    public function create()
    {
        $movies = $this->movieRepo->all();
        $studios = $this->studioRepo->all();
        return view('admin.showtimes.create', compact('movies', 'studios'));
    }

    public function store(StoreShowtimeRequest $request)
    {
        $startTime = Carbon::parse($request->start_time);
        
        // Validation: must be at least the start of the next hour
        $minTime = now()->copy()->addHour()->startOfHour();
        if ($startTime < $minTime) {
            return back()->withErrors(['start_time' => 'Showtime must be scheduled for ' . $minTime->format('d M Y, H:i') . ' onwards.'])->withInput();
        }

        $movie = $this->movieRepo->find($request->movie_id);
        // Add 10 minutes overhead for ads
        $endTime = $startTime->copy()->addMinutes($movie->duration)->addMinutes(10);

        // Validation: check for overlap in the same studio
        $overlap = $this->showtimeRepo->checkOverlap($request->studio_id, $startTime, $endTime);

        if ($overlap) {
            return back()->withErrors(['start_time' => 'The selected studio is already booked for this time period.'])->withInput();
        }

        $this->showtimeRepo->create([
            'movie_id' => $request->movie_id,
            'studio_id' => $request->studio_id,
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);

        return redirect()->route('admin.showtimes.index')->with('success', 'Showtime created successfully.');
    }

    public function show(string $id)
    {
        $showtime = $this->showtimeRepo->findWithRelations($id);
        return view('admin.showtimes.show', compact('showtime'));
    }

    public function edit(string $id)
    {
        $showtime = $this->showtimeRepo->find($id);
        $movie = $this->movieRepo->find($showtime->movie_id)->title;
        $studio = $this->studioRepo->find($showtime->studio_id)->name;
        return view('admin.showtimes.edit', compact('showtime', 'movie', 'studio'));
    }

    public function update(UpdateShowtimeRequest $request, string $id)
    {
        $showtime = $this->showtimeRepo->find($id);

        $startTime = Carbon::parse($request->start_time);
        
        // Skip current hour validation on update if the time didn't change, otherwise enforce it
        if ($startTime->format('Y-m-d H:i') !== Carbon::parse($showtime->start_time)->format('Y-m-d H:i')) {
            $minTime = now()->copy()->addHour()->startOfHour();
            if ($startTime < $minTime) {
                return back()->withErrors(['start_time' => 'Showtime must be scheduled for ' . $minTime->format('d M Y, H:i') . ' onwards.'])->withInput();
            }
        }

        $movie = $this->movieRepo->find($request->movie_id);
        // Add 10 minutes overhead for ads
        $endTime = $startTime->copy()->addMinutes($movie->duration)->addMinutes(10);

        // Validation: check for overlap in the same studio, excluding this specific showtime
        $overlap = $this->showtimeRepo->checkOverlap($request->studio_id, $startTime, $endTime, $id);

        if ($overlap) {
            return back()->withErrors(['start_time' => 'The selected studio is already booked for this time period.'])->withInput();
        }

        $this->showtimeRepo->update($id, [
            'movie_id' => $request->movie_id,
            'studio_id' => $request->studio_id,
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);

        return redirect()->route('admin.showtimes.index')->with('success', 'Showtime updated successfully.');
    }

    public function destroy(string $id)
    {
        $this->showtimeRepo->delete($id);
        return redirect()->route('admin.showtimes.index')->with('success', 'Showtime deleted successfully.');
    }
}
