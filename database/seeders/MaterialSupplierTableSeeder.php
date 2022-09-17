<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaterialSupplierTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('material_supplier')->delete();

        DB::table('material_supplier')->insert(array (
            0 =>
                array (
                    'id' => 1,
                    'material_id' => 1,
                    'supplier_id' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            1 =>
                array (
                    'id' => 2,
                    'material_id' => 1,
                    'supplier_id' => 2,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
            ),
            2 =>
                array (
                    'id' => 3,
                    'material_id' => 1,
                    'supplier_id' => 3,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            3 =>
                array (
                    'id' => 4,
                    'material_id' => 1,
                    'supplier_id' => 4,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            4 =>
                array (
                    'id' => 5,
                    'material_id' => 1,
                    'supplier_id' => 5,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            5 =>
                array (
                    'id' => 6,
                    'material_id' => 2,
                    'supplier_id' => 6,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            6 =>
                array (
                    'id' => 7,
                    'material_id' => 2,
                    'supplier_id' => 7,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            7 =>
                array (
                    'id' => 8,
                    'material_id' => 2,
                    'supplier_id' => 8,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            8 =>
                array (
                    'id' => 9,
                    'material_id' => 1,
                    'supplier_id' => 9,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            9 =>
                array (
                    'id' => 10,
                    'material_id' => 4,
                    'supplier_id' => 10,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            10 =>
                array (
                    'id' => 11,
                    'material_id' => 4,
                    'supplier_id' => 11,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            11 =>
                array (
                    'id' => 12,
                    'material_id' => 4,
                    'supplier_id' => 12,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            12 =>
                array (
                    'id' => 13,
                    'material_id' => 4,
                    'supplier_id' => 13,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            13 =>
                array (
                    'id' => 14,
                    'material_id' => 4,
                    'supplier_id' => 14,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            14 =>
                array (
                    'id' => 15,
                    'material_id' => 4,
                    'supplier_id' => 15,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            15 =>
                array (
                    'id' => 16,
                    'material_id' => 4,
                    'supplier_id' => 16,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            16 =>
                array (
                    'id' => 17,
                    'material_id' => 5,
                    'supplier_id' => 10,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            17 =>
                array (
                    'id' => 18,
                    'material_id' => 5,
                    'supplier_id' => 11,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            18 =>
                array (
                    'id' => 19,
                    'material_id' => 5,
                    'supplier_id' => 12,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            19=>
                array (
                    'id' => 20,
                    'material_id' => 5,
                    'supplier_id' => 13,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            20 =>
                array (
                    'id' => 21,
                    'material_id' => 5,
                    'supplier_id' => 14,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            21 =>
                array (
                    'id' => 22,
                    'material_id' => 5,
                    'supplier_id' => 15,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            22 =>
                array (
                    'id' => 23,
                    'material_id' => 5,
                    'supplier_id' => 16,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            23 =>
                array (
                    'id' => 24,
                    'material_id' => 6,
                    'supplier_id' => 10,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            24 =>
                array (
                    'id' => 25,
                    'material_id' => 6,
                    'supplier_id' => 11,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            25 =>
                array (
                    'id' => 26,
                    'material_id' => 6,
                    'supplier_id' => 12,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            26=>
                array (
                    'id' => 27,
                    'material_id' => 6,
                    'supplier_id' => 13,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            27 =>
                array (
                    'id' => 28,
                    'material_id' => 6,
                    'supplier_id' => 14,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            28 =>
                array (
                    'id' => 29,
                    'material_id' => 6,
                    'supplier_id' => 15,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            29 =>
                array (
                    'id' => 30,
                    'material_id' => 6,
                    'supplier_id' => 16,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            30 =>
                array (
                    'id' => 31,
                    'material_id' => 7,
                    'supplier_id' => 10,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            31 =>
                array (
                    'id' => 32,
                    'material_id' => 7,
                    'supplier_id' => 11,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            32 =>
                array (
                    'id' => 33,
                    'material_id' => 7,
                    'supplier_id' => 12,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            33=>
                array (
                    'id' => 34,
                    'material_id' => 7,
                    'supplier_id' => 13,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            34 =>
                array (
                    'id' => 35,
                    'material_id' => 7,
                    'supplier_id' => 14,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            35 =>
                array (
                    'id' => 36,
                    'material_id' => 7,
                    'supplier_id' => 15,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            36 =>
                array (
                    'id' => 37,
                    'material_id' => 7,
                    'supplier_id' => 16,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            37 =>
                array (
                    'id' => 38,
                    'material_id' => 8,
                    'supplier_id' => 10,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            38 =>
                array (
                    'id' => 39,
                    'material_id' => 8,
                    'supplier_id' => 11,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            39 =>
                array (
                    'id' => 40,
                    'material_id' => 8,
                    'supplier_id' => 12,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            40=>
                array (
                    'id' => 41,
                    'material_id' => 8,
                    'supplier_id' => 13,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            41 =>
                array (
                    'id' => 42,
                    'material_id' => 8,
                    'supplier_id' => 14,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            42 =>
                array (
                    'id' => 43,
                    'material_id' => 8,
                    'supplier_id' => 15,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            43 =>
                array (
                    'id' => 44,
                    'material_id' => 8,
                    'supplier_id' => 16,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            44 =>
                array (
                    'id' => 45,
                    'material_id' => 9,
                    'supplier_id' => 10,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            45 =>
                array (
                    'id' => 46,
                    'material_id' => 9,
                    'supplier_id' => 11,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            46 =>
                array (
                    'id' => 47,
                    'material_id' => 9,
                    'supplier_id' => 12,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            47=>
                array (
                    'id' => 48,
                    'material_id' => 9,
                    'supplier_id' => 13,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            48 =>
                array (
                    'id' => 49,
                    'material_id' => 9,
                    'supplier_id' => 14,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            49 =>
                array (
                    'id' => 50,
                    'material_id' => 9,
                    'supplier_id' => 15,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            50 =>
                array (
                    'id' => 51,
                    'material_id' => 9,
                    'supplier_id' => 16,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            51 =>
                array (
                    'id' => 52,
                    'material_id' => 10,
                    'supplier_id' => 10,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            52 =>
                array (
                    'id' => 53,
                    'material_id' => 10,
                    'supplier_id' => 11,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            53 =>
                array (
                    'id' => 54,
                    'material_id' => 10,
                    'supplier_id' => 12,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            54=>
                array (
                    'id' => 55,
                    'material_id' => 10,
                    'supplier_id' => 13,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            55 =>
                array (
                    'id' => 56,
                    'material_id' => 10,
                    'supplier_id' => 14,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            56 =>
                array (
                    'id' => 57,
                    'material_id' => 10,
                    'supplier_id' => 15,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            57 =>
                array (
                    'id' => 58,
                    'material_id' => 10,
                    'supplier_id' => 16,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            58 =>
                array (
                    'id' => 59,
                    'material_id' => 11,
                    'supplier_id' => 10,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            59 =>
                array (
                    'id' => 60,
                    'material_id' => 11,
                    'supplier_id' => 11,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            60 =>
                array (
                    'id' => 61,
                    'material_id' => 11,
                    'supplier_id' => 12,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            61=>
                array (
                    'id' => 62,
                    'material_id' => 11,
                    'supplier_id' => 13,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            62 =>
                array (
                    'id' => 63,
                    'material_id' => 11,
                    'supplier_id' => 14,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            63 =>
                array (
                    'id' => 64,
                    'material_id' => 11,
                    'supplier_id' => 15,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            64 =>
                array (
                    'id' => 65,
                    'material_id' => 11,
                    'supplier_id' => 16,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            65 =>
                array (
                    'id' => 66,
                    'material_id' => 12,
                    'supplier_id' => 10,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            66 =>
                array (
                    'id' => 67,
                    'material_id' => 12,
                    'supplier_id' => 11,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            67 =>
                array (
                    'id' => 68,
                    'material_id' => 12,
                    'supplier_id' => 12,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            68=>
                array (
                    'id' => 69,
                    'material_id' => 12,
                    'supplier_id' => 13,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            69 =>
                array (
                    'id' => 70,
                    'material_id' => 12,
                    'supplier_id' => 14,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            70 =>
                array (
                    'id' => 71,
                    'material_id' => 12,
                    'supplier_id' => 15,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            71 =>
                array (
                    'id' => 72,
                    'material_id' => 12,
                    'supplier_id' => 16,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            72 =>
                array (
                    'id' => 73,
                    'material_id' => 13,
                    'supplier_id' => 17,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            73 =>
                array (
                    'id' => 74,
                    'material_id' => 13,
                    'supplier_id' => 18,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            74 =>
                array (
                    'id' => 75,
                    'material_id' => 13,
                    'supplier_id' => 19,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            75 =>
                array (
                    'id' => 76,
                    'material_id' => 13,
                    'supplier_id' => 20,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            76 =>
                array (
                    'id' => 77,
                    'material_id' => 14,
                    'supplier_id' => 17,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            77 =>
                array (
                    'id' => 78,
                    'material_id' => 14,
                    'supplier_id' => 18,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            78 =>
                array (
                    'id' => 79,
                    'material_id' => 14,
                    'supplier_id' => 19,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            79 =>
                array (
                    'id' => 80,
                    'material_id' => 14,
                    'supplier_id' => 20,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            80 =>
                array (
                    'id' => 81,
                    'material_id' => 15,
                    'supplier_id' => 17,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            81 =>
                array (
                    'id' => 82,
                    'material_id' => 15,
                    'supplier_id' => 18,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            82 =>
                array (
                    'id' => 83,
                    'material_id' => 15,
                    'supplier_id' => 19,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            83 =>
                array (
                    'id' => 84,
                    'material_id' => 15,
                    'supplier_id' => 20,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),

        ));
    }
}
