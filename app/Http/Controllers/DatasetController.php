<?php

use App\Imports\DatasetImport;
use Maatwebsite\Excel\Facades\Excel;


class DatasetController extends Controller
{
    public function import(Request $request)
    {
        Excel::import(new DatasetImport, $request->file('file'));
        return back() ->with ('success', 'Data berhasil diimpor!');
    }
}
