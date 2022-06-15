<?php

namespace App\Imports;

use App\Models\Material;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;

class MaterialsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Material([
            'code'          => $row[1],
            'name'          => $row[2],
            'quality'       => $row[3],
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
    }
}
