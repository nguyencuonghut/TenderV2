<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaterialsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('materials')->delete();

        DB::table('materials')->insert(array (
            0 =>
                array (
                    'id' => 1,
                    'code' => 100003,
                    'name' => 'Ngô hạt',
                    'quality' => '- Màu vàng đặc trưng, mùi vị đặc trưng không có mùi hôi, mốc, mùi lạ khác, không có mọt và VSV gây bệnh.
                    - Độ ẩm ≤ 14.5%, Tạp chất ≤ 2,0%.
                    - Aflatoxin ≤ 50 ppb.',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            1 =>
                array (
                    'id' => 2,
                    'code' => 100037,
                    'name' => 'Bã ngô 27%',
                    'quality' => '- Độ ẩm ≤ 0.5%, Béo ≥ 98%.
                    - AV ≤ 3 %.
                    - Mùi đặc trưng, không có mùi khét, mùi ôi, mùi hóa chất.
                    - Màu trắng trắng đục.- Độ ẩm ≤ 12%, Độ đạm ≥ 26%.
                    - Xơ ≤ 10%, Profat > 34%.
                    - Béo > 6%.
                    - Aflatoxin ≤ 20 ppb, Vomitoxin ≤ 5 ppm.
                    - Không có mọt sống, ko lẫn kim loại vật lạ.
                    - Màu vàng sậm, vàng nhạt, không cháy, mùi đặc trưng.',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
            ),
            2 =>
                array (
                    'id' => 3,
                    'code' => 450003,
                    'name' => 'Mỡ cá',
                    'quality' => '- Độ ẩm ≤ 0.5%, Béo ≥ 98%.
                    - AV ≤ 3 %.
                    - Mùi đặc trưng, không có mùi khét, mùi ôi, mùi hóa chất.
                    - Màu trắng trắng đục.',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
        ));
    }
}
