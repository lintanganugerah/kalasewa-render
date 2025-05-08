<?php

namespace App\Http\Controllers;

use App\Models\Series;
use Illuminate\Http\Request;

class SeriesController extends Controller
{
    public function search(Request $request)
    {
        if ($request->has('search')) {
            $searchQuery = $request->search;
            $series = Series::where('series', 'LIKE', "%" . $searchQuery . '%')->paginate(10);
        } else {
            $series = Series::paginate(10);
        }

        return view('admin.series.index', compact('series'));
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Series::query();

        if ($search) {
            $query->where('series', 'like', '%' . $search . '%');
        }

        $series = $query->orderByRaw('GREATEST(created_at, updated_at) DESC')->paginate(10)->appends(['search' => $search]);

        return view('admin.series.index', compact('series', 'search'));
    }


    public function create()
    {
        return view('admin.series.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'series' => 'required|string|max:255',
        ]);

        $existingSeries = Series::where('series', $request->series)->first();
        if ($existingSeries) {
            return redirect()->back()->withErrors(['series' => 'Nama series sudah tersedia.'])->withInput();
        }

        Series::create([
            'series' => $request->series,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.series.index')->with('success', 'Series berhasil ditambahkan.');
    }

    public function show($id)
    {
        $series = Series::findOrFail($id);
        return view('admin.series.show', compact('series'));
    }

    public function edit($id)
    {
        $series = Series::findOrFail($id);
        return view('admin.series.edit', compact('series'));
    }

    public function update(Request $request, Series $series)
    {
        $request->validate([
            'series' => 'required|string|max:255',
        ]);

        $existingSeries = Series::where('series', $request->series)->first();
        if ($existingSeries) {
            return redirect()->back()->withErrors(['series' => 'Nama series sudah tersedia.'])->withInput();
        }

        $series->update([
            'series' => $request->series,
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.series.index')->with('success', 'Series berhasil diperbarui.');
    }

    public function destroy(Series $series)
    {
        $series->delete();

        return redirect()->route('admin.series.index')
            ->with('success', 'Series berhasil dihapus.');
    }
}