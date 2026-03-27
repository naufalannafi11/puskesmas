<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $obats = Obat::when($search, function ($query) use ($search){
            $query->where('nama_obat', 'like', "%{$search}%");
        })->paginate(10);

        return view('admin.obat.index', compact('obats', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.obat.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_obat'=>'required',
            'stok'=>'required|integer',
            'harga'=>'required|numeric',
        ]);

        Obat::create($request->all());

        return redirect()->route('admin.obat.index')
        ->with('success', 'Obat berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Obat $obat)
    {
        return view('admin.obat.edit', compact('obat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Obat $obat)
    {
        $obat->update($request->all());
        return redirect()->route('admin.obat.index')
        ->with('success', 'Obat berhasil di update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Obat $obat)
    {
        $obat->delete();

        return back()->with('success', 'Obat dihapus');
    }
}
