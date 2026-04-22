<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DokterController extends Controller
{
    public function index (){
        $dokters = User::where('role', 'dokter')->get();
        return view('admin.dokter.index', compact('dokters'));
    }

    public function create (){
        return view('admin.dokter.create');
    }

    public function store (Request $request){
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6',
            'poli' => 'required'
        ]);
        User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'role'=>'dokter',
            'poli'=> $request->poli,
        ]);
        return redirect()->route ('admin.dokter.index')
        ->with('success', 'Dokter Berhasil Ditambahkan');
    }

    public function edit(User $dokter){
        return view('admin.dokter.edit', compact('dokter'));
    }

    public function update (Request $request, User $dokter){
        $request-> validate([
            'name'=>'required',
            'email'=>'required|email|unique:users,email,' . $dokter->id
        ]);
        $dokter->update($request->only('name','email'));

        return redirect()->route('admin.dokter.index')
        ->with('success', 'Data Dokter Berhasil Diupdate');
    }

    public function destroy(User $dokter){
        $dokter->delete();

        return redirect()->route('admin.dokter.index')
        ->with('success', 'Dokter berhasil dihapus');
    }
}
