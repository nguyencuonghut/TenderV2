<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            SuppliersTableSeeder::class,
            MaterialsTableSeeder::class,
            MaterialSupplierTableSeeder::class,
            AdminsTableSeeder::class,
            UsersTableSeeder::class,
        ]);
    }
}
