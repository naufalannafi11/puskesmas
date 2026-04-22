<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\DatasetImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class DatasetController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls|max:2048',
            'import_id' => 'required'
        ]);

        set_time_limit(0);

        try {
            // ✅ IMPORT DATA (upsert — tidak menghapus data yang sudah ada)
            Excel::import(new DatasetImport($request->import_id), $request->file('file'));

            return back()->with('success', 'Dataset berhasil diimpor!');
        } catch (\Exception $e) {

            Log::error('Import Error: ' . $e->getMessage());

            return back()->withErrors('Import gagal: ' . $e->getMessage());
        }
    }

    public function progress($id)
    {
        return response()->json([
            'total'   => (int) Cache::get("import_total_{$id}", 0),
            'current' => (int) Cache::get("import_current_{$id}", 0),
        ]);
    }
}