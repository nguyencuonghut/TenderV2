<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->delete();

        DB::table('admins')->insert(array (
            0 =>
                array (
                    'id' => 1,
                    'name' => 'Tony Nguyen',
                    'email' => 'nguyenvancuong@honghafeed.com.vn',
                    'password' => bcrypt('Hongha@123'),
                    'remember_token' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            1 =>
                array (
                    'id' => 2,
                    'name' => 'Bùi Thị Nụ',
                    'email' => 'buithinu@honghafeed.com.vn',
                    'password' => bcrypt('Hongha@123'),
                    'remember_token' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            2 =>
                array (
                    'id' => 3,
                    'name' => 'Nguyễn Xuân Hảo',
                    'email' => 'nguyenxuanhao@honghafeed.com.vn',
                    'password' => bcrypt('Hongha@123'),
                    'remember_token' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            3 =>
                array (
                    'id' => 4,
                    'name' => 'Đỗ Đức Thanh',
                    'email' => 'doducthanh@longhaigroup.com.vn',
                    'password' => bcrypt('Hongha@123'),
                    'remember_token' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
        ));
    }
}
