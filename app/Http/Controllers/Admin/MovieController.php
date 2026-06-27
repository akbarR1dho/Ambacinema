<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Movie;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Movie::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('poster', function($row){
                    if($row->poster) {
                        return '<img src="'.Storage::url($row->poster).'" class="h-16 w-12 object-cover rounded shadow">';
                    }
                    return 'No Image';
                })
                ->addColumn('action', function($row){
                    $editUrl = route('admin.movies.edit', $row->id);
                    $deleteUrl = route('admin.movies.destroy', $row->id);
                    $btn = '<div class="flex space-x-2">';
                    $btn .= '<a href="'.$editUrl.'" class="text-blue-500 hover:text-blue-700 p-1 bg-blue-500/10 rounded transition-colors" title="Edit Movie"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></a>';
                    $btn .= '<form action="'.$deleteUrl.'" method="POST" class="inline-block" onsubmit="confirmDelete(event, \'Are you sure you want to delete this movie?\');">
                                '.csrf_field().'
                                '.method_field("DELETE").'
                                <button type="submit" class="text-red-500 hover:text-red-700 p-1 bg-red-500/10 rounded transition-colors" title="Delete Movie"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                            </form>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['poster', 'action'])
                ->make(true);
        }
        return view('admin.movies.index');
    }

    public function create()
    {
        return view('admin.movies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:movies,title',
            'description' => 'required|string',
            'duration' => 'required|integer|min:1',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->except('poster');
        
        if ($request->hasFile('poster')) {
            $data['poster'] = $request->file('poster')->store('posters');
        }

        Movie::create($data);

        return redirect()->route('admin.movies.index')->with('success', 'Movie created successfully.');
    }

    public function show(string $id)
    {
        $movie = Movie::findOrFail($id);
        return view('admin.movies.show', compact('movie'));
    }

    public function edit(string $id)
    {
        $movie = Movie::findOrFail($id);
        return view('admin.movies.edit', compact('movie'));
    }

    public function update(Request $request, string $id)
    {
        $movie = Movie::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255|unique:movies,title,' . $id,
            'description' => 'required|string',
            'duration' => 'required|integer|min:1',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->only('title', 'description', 'duration');

        if ($request->hasFile('poster')) {
            // Delete old poster if exists
            if ($movie->poster && Storage::exists($movie->poster)) {
                Storage::delete($movie->poster);
            }
            
            $path = $request->file('poster')->store('posters');
            $data['poster'] = $path;
        }

        $movie->update($data);

        return redirect()->route('admin.movies.index')->with('success', 'Movie updated successfully.');
    }

    public function destroy(string $id)
    {
        $movie = Movie::findOrFail($id);
        if ($movie->poster && Storage::exists($movie->poster)) {
            Storage::delete($movie->poster);
        }
        $movie->delete();

        return redirect()->route('admin.movies.index')->with('success', 'Movie deleted successfully.');
    }
}
