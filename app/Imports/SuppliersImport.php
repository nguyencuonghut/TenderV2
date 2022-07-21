<?php

namespace App\Imports;

use App\Models\Material;
use App\Models\MaterialSupplier;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;

class SuppliersImport implements ToModel, WithBatchInserts, WithChunkReading, WithStartRow
{
    private $rows = 0;
    private $duplicates = 0;

    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        //Check the Material existed or not
        $material = Material::where('name', $row[3])->first();
        if(null == $material){
            //Log::debug($row[0] . ' - ' . $row[1] .' - ' . $row[3], ['file' => __FILE__, 'line' => __LINE__]);
            return null;
        }else{
            //Find Supplier
            $supplier = Supplier::where('code', $row[1])->first();
            if(null == $supplier){
                //Not found Supplier, then create new Supplier and MaterialSupplier
                $new_supplier = Supplier::create([
                    'code'          => $row[1],
                    'name'          => $row[2],
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ]);

                MaterialSupplier::create([
                    'material_id'   => $material->id,
                    'supplier_id'   => $new_supplier->id,
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ]);
                //Log::debug($row[0] . ' - ' . $row[1] .' - ' . $row[3], ['file' => __FILE__, 'line' => __LINE__]);
            }else{
                //Found Supplier, then check MaterialSupplier existed or not
                $material_supplier = MaterialSupplier::where('material_id', $material->id)->where('supplier_id', $supplier->id)->first();
                if(null == $material_supplier){
                    //Create new MaterialSupplier
                    MaterialSupplier::create([
                        'material_id'   => $material->id,
                        'supplier_id'   => $supplier->id,
                        'created_at'    => Carbon::now(),
                        'updated_at'    => Carbon::now(),
                    ]);
                    //Log::debug($row[0] . ' - ' . $row[1] .' - ' . $row[3], ['file' => __FILE__, 'line' => __LINE__]);
                }else{
                    //Log::debug($row[0] . ' - ' . $row[1] .' - ' . $row[3], ['file' => __FILE__, 'line' => __LINE__]);
                    ++$this->duplicates;
                }
            }
        }
        ++$this->rows;
    }

    public function batchSize(): int
    {
        return 50;
    }

    public function chunkSize(): int
    {
        return 50;
    }

    public function startRow(): int
    {
        return 2;
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
