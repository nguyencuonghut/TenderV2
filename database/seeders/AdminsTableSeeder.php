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
                    'role_id' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            1 =>
                array (
                    'id' => 2,
                    'name' => 'Nhân viên Kiểm Soát',
                    'email' => 'nvkiemsoat@honghafeed.com.vn',
                    'password' => bcrypt('Hongha@123'),
                    'remember_token' => null,
                    'role_id' => 4,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            2 =>
                array (
                    'id' => 3,
                    'name' => 'Nhân viên Thu Mua',
                    'email' => 'nvthumua@honghafeed.com.vn',
                    'password' => bcrypt('Hongha@123'),
                    'role_id' => 3,
                    'remember_token' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            3 =>
                array (
                    'id' => 4,
                    'name' => 'Trưởng phòng Thu Mua',
                    'email' => 'truongphongmua@honghafeed.com.vn',
                    'password' => bcrypt('Hongha@123'),
                    'role_id' => 5,
                    'remember_token' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            4 =>
                array (
                    'id' => 5,
                    'name' => 'Quản lý',
                    'email' => 'quanly@honghafeed.com.vn',
                    'password' => bcrypt('Hongha@123'),
                    'role_id' => 2,
                    'remember_token' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
        ));
    }
}
