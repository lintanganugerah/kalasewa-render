<?php

namespace App\Http\Controllers;

use App\Models\Series;
use Illuminate\Http\Request;
use App\Models\Peraturan;
use App\Models\AboutUs;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard', );
    }

    public function series()
    {
        $series = Series::all(); // Ambil semua data series dari tabel 'series'

        return view('admin.series', compact('series'));
    }

    public function indexAboutUs()
    {
        $aboutUs = AboutUs::first();

        return view('admin.aboutUs.index', compact('aboutUs'));
    }

    public function editAboutUs()
    {
        $aboutUs = AboutUs::first();

        return view('admin.aboutUs.edit', compact('aboutUs'));
    }

    public function updateAboutUs(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $aboutUs = AboutUs::first();
        if (!$aboutUs) {
            $aboutUs = new AboutUs();
        }
        $aboutUs->content = $request->content;
        $aboutUs->save();

        return redirect()->route('admin.aboutus.index')->with('success', 'Tentang Kalasewa berhasil diperbarui.');
    }

    public function indexRegulations()
    {
        $peraturansUmum = Peraturan::where('Audience', 'umum')->get();
        $peraturansPenyewa = Peraturan::where('Audience', 'penyewa')->get();
        $peraturansPemilikSewa = Peraturan::where('Audience', 'pemilik sewa')->get();

        return view('admin.regulations.index', compact('peraturansUmum', 'peraturansPenyewa', 'peraturansPemilikSewa'));
    }


    public function editRegulations($id)
    {
        $peraturan = Peraturan::find($id);
        return view('admin.regulations.edit', compact('peraturan'));
    }

    public function updateRegulations(Request $request)
    {
        $request->validate([
            'Peraturan.*' => 'required|string',
        ]);

        foreach ($request->Peraturan as $id => $isi) {
            $peraturan = Peraturan::find($id);
            $peraturan->update([
                'Peraturan' => $isi,
            ]);
        }

        return redirect()->route('admin.regulations.index')->with('success', 'Peraturan berhasil diperbarui');
    }
}