<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class PasienController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $pasiens = User::where('role', 'pasien')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->paginate(10);

        return view('admin.pasien.index', compact('pasiens', 'search'));
    }

    public function create()
    {
        return view('admin.pasien.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6',
    ]);

    $lastRM = User::whereNotNull('no_rm')
        ->orderBy('no_rm', 'desc')
        ->value('no_rm');

    $newRM = $lastRM
        ? str_pad(((int)$lastRM) + 1, 6, '0', STR_PAD_LEFT)
        : '000001';

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'role' => 'pasien',
        'no_rm' => $newRM,
    ]);

    return redirect()
        ->route('admin.pasien.index')
        ->with('success', 'Pasien berhasil ditambahkan');
}

    public function edit(User $pasien)
    {
        return view('admin.pasien.edit', compact('pasien'));
    }

    public function update(Request $request, User $pasien)
    {
        $pasien->update($request->only('name','email'));

        return redirect()->route('admin.pasien.index')
            ->with('success', 'Pasien berhasil diupdate');
    }

    public function destroy(User $pasien)
    {
        $pasien->delete();

        return back()->with('success', 'Pasien dihapus');
    }
}
