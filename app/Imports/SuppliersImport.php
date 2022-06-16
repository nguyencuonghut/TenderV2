<?php

namespace App\Imports;

use App\Models\Material;
use App\Models\MaterialSupplier;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class SuppliersImport implements ToCollection
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
                $data = Supplier::where('code', $row['1'])->get();
                if($data->count() == 0){
                    //Create Supplier
                    Supplier::create([
                        'code'          => $row[1],
                        'name'          => $row[2],
                        'created_at'    => Carbon::now(),
                        'updated_at'    => Carbon::now(),
                    ]);

                    //Create new MaterialSupplier
                    $material = Material::where('name', $row[3])->first();
                    $supplier = Supplier::where('code', $row[1])->first();
                    MaterialSupplier::create([
                        'material_id'   => $material->id,
                        'supplier_id'   => $supplier->id,
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
