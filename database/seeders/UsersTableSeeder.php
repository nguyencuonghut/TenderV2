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
        ));
    }
}
