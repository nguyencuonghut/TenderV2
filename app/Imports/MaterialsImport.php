<?php

namespace App\Imports;

use App\Models\Material;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class MaterialsImport implements ToCollection
{
    private $rows = 0;
    private $duplicates = 0;

    public function collection(Collection $rows)
    {
        $i = 0;
        foreach ($rows as $row)
        {
            $i++;
            //Skip the heading row (first row)
            if($i > 1){
                $data = Material::where('code', $row['1'])->get();
                if($data->count() == 0){
                    //Create Material
                    Material::create([
                        'code'          => $row[1],
                        'name'          => $row[2],
                        'quality'       => $row[3],
                        'created_at'    => Carbon::now(),
                        'updated_at'    => Carbon::now(),
                    ]);
                    ++$this->rows;
                }else{
                    ++$this->duplicates;
                }
            }
        }
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }

    public function getDuplicateCount(): int
    {
        return $this->duplicates;
    }
}
