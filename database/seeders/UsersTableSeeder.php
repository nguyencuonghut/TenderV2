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
                    'name' => 'Mr.Vương',
                    'email' => 'vuongtanlong@gmail.com',
                    'password' => bcrypt('Hongha@123'),
                    'email_verified_at' => null,
                    'remember_token' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            1 =>
                array (
                    'id' => 2,
                    'name' => 'Ms.Mơ',
                    'email' => 'mo@gmail.com',
                    'password' => bcrypt('Hongha@123'),
                    'email_verified_at' => null,
                    'remember_token' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
            ),
            2 =>
                array (
                    'id' => 3,
                    'name' => 'Mr.Thường',
                    'email' => 'teddy.dang@cj.net',
                    'password' => bcrypt('Hongha@123'),
                    'email_verified_at' => null,
                    'remember_token' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            3 =>
                array (
                    'id' => 4,
                    'name' => 'Ms.Ngan',
                    'email' => 'thinga.tran@gmail.com',
                    'password' => bcrypt('Hongha@123'),
                    'email_verified_at' => null,
                    'remember_token' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            4 =>
                array (
                    'id' => 5,
                    'name' => 'Ms.Huong',
                    'email' => 'huong.crossland@gmail.com',
                    'password' => bcrypt('Hongha@123'),
                    'email_verified_at' => null,
                    'remember_token' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            5 =>
                array (
                    'id' => 6,
                    'name' => 'Mr.Long',
                    'email' => 'long.enerfo@gmail.com',
                    'password' => bcrypt('Hongha@123'),
                    'email_verified_at' => null,
                    'remember_token' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            6 =>
                array (
                    'id' => 7,
                    'name' => 'Ms.Tina',
                    'email' => 'tina-acc@gmail.com',
                    'password' => bcrypt('Hongha@123'),
                    'email_verified_at' => null,
                    'remember_token' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            7 =>
                array (
                    'id' => 8,
                    'name' => 'Ms.Ngọc',
                    'email' => 'ngoc.samaco@gmail.com',
                    'password' => bcrypt('Hongha@123'),
                    'email_verified_at' => null,
                    'remember_token' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
        ));
    }
}
