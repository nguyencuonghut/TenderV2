<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SuppliersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('suppliers')->delete();

        DB::table('suppliers')->insert(array (
            0 =>
                array (
                    'id' => 1,
                    'code' => 'CT0001',
                    'name' => 'Công ty nông nghiệp Cao Thăng',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            1 =>
                array (
                    'id' => 2,
                    'code' => 'VS0001',
                    'name' => 'Công ty nông nghiệp Văn Sơn',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            2 =>
                array (
                    'id' => 3,
                    'code' => 'CJ0001',
                    'name' => 'Công ty CJ',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            3 =>
                array (
                    'id' => 4,
                    'code' => 'BG0001',
                    'name' => 'Công ty Bunge Singapore',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            4 =>
                array (
                    'id' => 5,
                    'code' => 'CL0001',
                    'name' => 'Công ty Cross Land Singapore',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            5 =>
                array (
                    'id' => 6,
                    'code' => 'EF0001',
                    'name' => 'Công ty Enerfo Singapore',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            6 =>
                array (
                    'id' => 7,
                    'code' => 'AC0001',
                    'name' => 'Công ty ACC',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            7 =>
                array (
                    'id' => 8,
                    'code' => 'SM0001',
                    'name' => 'Công ty Samaco',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            8 =>
                array (
                    'id' => 9,
                    'code' => 'BFG',
                    'name' => 'Tập đoàn BestFood',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            9 =>
                array (
                    'id' => 10,
                    'code' => 'NUT0003',
                    'name' => 'Công ty TNHH Nutreco International (Việt Nam)',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            10 =>
                array (
                    'id' => 11,
                    'code' => 'BOM0001',
                    'name' => 'Công ty TNHH Biomin Việt Nam',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            11 =>
                array (
                    'id' => 12,
                    'code' => 'AB0003',
                    'name' => 'Công ty TNHH AB Agri Việt Nam',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            12 =>
                array (
                    'id' => 13,
                    'code' => 'NBC0001',
                    'name' => 'Công ty TNHH MTV NBC Pacific',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            13 =>
                array (
                    'id' => 14,
                    'code' => 'MX0002',
                    'name' => 'Công ty cổ phần thương mại Màu Xanh',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            14 =>
                array (
                    'id' => 15,
                    'code' => 'TL0001',
                    'name' => 'Công ty TNHH Tiến Lợi',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            15 =>
                array (
                    'id' => 16,
                    'code' => 'RVM004',
                    'name' => 'Công ty TNHH MTV Provimi',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            16 =>
                array (
                    'id' => 17,
                    'code' => 'BBHH0010',
                    'name' => 'Công ty TNHH Hoa Hạ Việt Nam',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            17 =>
                array (
                    'id' => 18,
                    'code' => 'DH0002',
                    'name' => 'Công ty cổ phần Đại Hữu',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            18 =>
                array (
                    'id' => 19,
                    'code' => 'MH0001',
                    'name' => 'Công ty TNHH xuất nhập khẩu Minh Hải',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            19 =>
                array (
                    'id' => 20,
                    'code' => 'TD0007',
                    'name' => 'Công ty cổ phần đầu tư và phát triển Thái Dương',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
        ));
    }
}
