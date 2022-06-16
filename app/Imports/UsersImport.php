<?php

namespace App\Imports;

use App\Models\Supplier;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;

class UsersImport implements ToCollection
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
                $data = User::where('email', $row['2'])->get();
                $supplier = Supplier::where('name', $row[4])->first();
                if($data->count() == 0){
                    //Create User
                    User::create([
                        'name'          => $row[1],
                        'email'         => $row[2],
                        'password'      => Hash::make($row[3]),
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
