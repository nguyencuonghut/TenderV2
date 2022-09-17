<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

        DB::table('users')->insert(array (
            0 =>
                array (
                    'id' => 1,
                    'name' => 'Cao Thang 1',
                    'email' => 'caothang1@honghafeed.com.vn',
                    'password' => bcrypt('Hongha@123'),
                    'email_verified_at' => null,
                    'remember_token' => null,
                    'supplier_id' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            1 =>
                array (
                    'id' => 2,
                    'name' => 'Cao Thang 2',
                    'email' => 'caothang2@honghafeed.com.vn',
                    'password' => bcrypt('Hongha@123'),
                    'email_verified_at' => null,
                    'remember_token' => null,
                    'supplier_id' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
            ),
            2 =>
                array (
                    'id' => 3,
                    'name' => 'Văn Sơn',
                    'email' => 'vanson@honghafeed.com.vn',
                    'password' => bcrypt('Hongha@123'),
                    'email_verified_at' => null,
                    'remember_token' => null,
                    'supplier_id' => 2,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            3 =>
                array (
                    'id' => 4,
                    'name' => 'CJ',
                    'email' => 'cj@honghafeed.com.vn',
                    'password' => bcrypt('Hongha@123'),
                    'email_verified_at' => null,
                    'remember_token' => null,
                    'supplier_id' => 3,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            4 =>
                array (
                    'id' => 5,
                    'name' => 'Bunge Singapore',
                    'email' => 'bungesingapore@honghafeed.com.vn',
                    'password' => bcrypt('Hongha@123'),
                    'email_verified_at' => null,
                    'remember_token' => null,
                    'supplier_id' => 4,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            5 =>
                array (
                    'id' => 6,
                    'name' => 'Cross Land Singapore',
                    'email' => 'crosslandsingapore@honghafeed.com.vn',
                    'password' => bcrypt('Hongha@123'),
                    'email_verified_at' => null,
                    'remember_token' => null,
                    'supplier_id' => 5,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            6 =>
                array (
                    'id' => 7,
                    'name' => 'Enerfo Singapore',
                    'email' => 'enerfosingapore@honghafeed.com.vn',
                    'password' => bcrypt('Hongha@123'),
                    'email_verified_at' => null,
                    'remember_token' => null,
                    'supplier_id' => 6,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            7 =>
                array (
                    'id' => 8,
                    'name' => 'ACC',
                    'email' => 'acc@honghafeed.com.vn',
                    'password' => bcrypt('Hongha@123'),
                    'email_verified_at' => null,
                    'remember_token' => null,
                    'supplier_id' => 7,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            8 =>
                array (
                    'id' => 9,
                    'name' => 'Samaco 1',
                    'email' => 'samaco1@honghafeed.com.vn',
                    'password' => bcrypt('Hongha@123'),
                    'email_verified_at' => null,
                    'remember_token' => null,
                    'supplier_id' => 8,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            9=>
                array (
                    'id' => 10,
                    'name' => 'Samaco 2',
                    'email' => 'samaco2@honghafeed.com.vn',
                    'password' => bcrypt('Hongha@123'),
                    'email_verified_at' => null,
                    'remember_token' => null,
                    'supplier_id' => 8,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            10 =>
                array (
                    'id' => 11,
                    'name' => 'Samaco 3',
                    'email' => 'samaco3@honghafeed.com.vn',
                    'password' => bcrypt('Hongha@123'),
                    'email_verified_at' => null,
                    'remember_token' => null,
                    'supplier_id' => 8,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),

            11 =>
                array (
                    'id' => 12,
                    'name' => 'Nguyễn Văn Cường',
                    'email' => 'nguyencuonghut55@gmail.com',
                    'password' => bcrypt('Hongha@123'),
                    'email_verified_at' => null,
                    'remember_token' => null,
                    'supplier_id' => 9,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),

            12 =>
                array (
                    'id' => 13,
                    'name' => 'Nutreco',
                    'email' => 'nutreco@honghafeed.com.vn',
                    'password' => bcrypt('Hongha@123'),
                    'email_verified_at' => null,
                    'remember_token' => null,
                    'supplier_id' => 10,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),

            13 =>
                array (
                    'id' => 14,
                    'name' => 'Biomin',
                    'email' => 'biomin@honghafeed.com.vn',
                    'password' => bcrypt('Hongha@123'),
                    'email_verified_at' => null,
                    'remember_token' => null,
                    'supplier_id' => 11,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),

            14 =>
                array (
                    'id' => 15,
                    'name' => 'AB Agri',
                    'email' => 'abagri@honghafeed.com.vn',
                    'password' => bcrypt('Hongha@123'),
                    'email_verified_at' => null,
                    'remember_token' => null,
                    'supplier_id' => 12,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),

            15 =>
                array (
                    'id' => 16,
                    'name' => 'NBC Pacific',
                    'email' => 'nbcpacific@honghafeed.com.vn',
                    'password' => bcrypt('Hongha@123'),
                    'email_verified_at' => null,
                    'remember_token' => null,
                    'supplier_id' => 13,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),

            16 =>
                array (
                    'id' => 17,
                    'name' => 'Màu xanh',
                    'email' => 'mauxanh@honghafeed.com.vn',
                    'password' => bcrypt('Hongha@123'),
                    'email_verified_at' => null,
                    'remember_token' => null,
                    'supplier_id' => 14,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),

            17 =>
                array (
                    'id' => 18,
                    'name' => 'Tiến Lợi',
                    'email' => 'tienloi@honghafeed.com.vn',
                    'password' => bcrypt('Hongha@123'),
                    'email_verified_at' => null,
                    'remember_token' => null,
                    'supplier_id' => 15,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),

            18 =>
                array (
                    'id' => 19,
                    'name' => 'Provimi',
                    'email' => 'provimi@honghafeed.com.vn',
                    'password' => bcrypt('Hongha@123'),
                    'email_verified_at' => null,
                    'remember_token' => null,
                    'supplier_id' => 16,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),

            19 =>
                array (
                    'id' => 20,
                    'name' => 'Hoa Hạ',
                    'email' => 'hoaha@honghafeed.com.vn',
                    'password' => bcrypt('Hongha@123'),
                    'email_verified_at' => null,
                    'remember_token' => null,
                    'supplier_id' => 17,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),

            20 =>
                array (
                    'id' => 21,
                    'name' => 'Đại Hữu',
                    'email' => 'daihuu@honghafeed.com.vn',
                    'password' => bcrypt('Hongha@123'),
                    'email_verified_at' => null,
                    'remember_token' => null,
                    'supplier_id' => 18,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),

            21 =>
                array (
                    'id' => 22,
                    'name' => 'Thái Dương',
                    'email' => 'thaiduong@honghafeed.com.vn',
                    'password' => bcrypt('Hongha@123'),
                    'email_verified_at' => null,
                    'remember_token' => null,
                    'supplier_id' => 20,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),

            22 =>
                array (
                    'id' => 23,
                    'name' => 'Minh Hải',
                    'email' => 'minhhai@honghafeed.com.vn',
                    'password' => bcrypt('Hongha@123'),
                    'email_verified_at' => null,
                    'remember_token' => null,
                    'supplier_id' => 19,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
    ));
    }
}
