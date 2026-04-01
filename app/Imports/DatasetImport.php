<?php

namespace App\Imports;

use App\Models\Dataset;
use Maatwebsite\Excel\Concerns\ToModel;

class DatasetImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Dataset([
            'tanggal' => $row[0],
            'hari' => $row[1],
            'bulan' => $row[2],
            'poli' => $row[3],
            'jumlah_pasien' => $row[4],
        ]);
    }
}
