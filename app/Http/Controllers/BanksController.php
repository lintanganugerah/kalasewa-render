<?php

namespace App\Http\Controllers;

use App\Models\TujuanRekening;
use Illuminate\Http\Request;

class BanksController extends Controller
{
    // Metode untuk melakukan pencarian
    public function search(Request $request)
    {
        if ($request->has('search')) {
            $searchQuery = $request->search;
            $banks = TujuanRekening::where('nama', 'LIKE', "%" . $searchQuery . '%')->paginate(10);
        } else {
            $banks = TujuanRekening::paginate(10);
        }

        return view('admin.banks.index', compact('banks'));
    }


    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = TujuanRekening::query();

        if ($search) {
            $query->where('nama', 'like', '%' . $search . '%');
        }

        $banks = $query->orderByRaw('GREATEST(created_at, updated_at) DESC')->paginate(10)->appends(['search' => $search]);

        return view('admin.banks.index', compact('banks', 'search'));
    }


    public function create()
    {
        return view('admin.banks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $existingBanks = TujuanRekening::where('nama', $request->nama)->first();
        if ($existingBanks) {
            return redirect()->back()->withErrors(['nama' => 'Nama bank sudah tersedia.'])->withInput();
        }

        TujuanRekening::create([
            'nama' => $request->nama,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.banks.index')->with('success', 'Nama Bank berhasil ditambahkan.');
    }


    public function show($id)
    {
        $banks = TujuanRekening::findOrFail($id);
        return view('admin.banks.show', compact('banks'));
    }

    public function edit($id)
    {
        $banks = TujuanRekening::findOrFail($id);
        return view('admin.banks.edit', compact('banks'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $existingBank = TujuanRekening::where('nama', $request->nama)->first();
        if ($existingBank && $existingBank->id != $id) {
            return redirect()->back()->withErrors(['nama' => 'Nama bank sudah tersedia.'])->withInput();
        }

        $banks = TujuanRekening::findOrFail($id);
        $banks->update([
            'nama' => $request->nama,
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.banks.index')->with('success', 'Nama Bank berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $bank = TujuanRekening::findOrFail($id);
        $bank->delete();

        return redirect()->route('admin.banks.index')
            ->with('success', 'Nama Bank berhasil dihapus.');
    }

}