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
            3 =>
                array (
                    'id' => 4,
                    'code' => 510002,
                    'name' => 'HH Breeder Pig',
                    'quality' => 'Theo công thức Hồng Hà',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            4 =>
                array (
                    'id' => 5,
                    'code' => 510001,
                    'name' => 'HH Broiler',
                    'quality' => 'Theo công thức Hồng Hà',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            5 =>
                array (
                    'id' => 6,
                    'code' => 510008,
                    'name' => 'HH Cow',
                    'quality' => 'Theo công thức Hồng Hà',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            6 =>
                array (
                    'id' => 7,
                    'code' => 510003,
                    'name' => 'HH Finisher Pig 2.0',
                    'quality' => 'Theo công thức Hồng Hà',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            7 =>
                array (
                    'id' => 8,
                    'code' => 510010,
                    'name' => 'HH Fish',
                    'quality' => 'Theo công thức Hồng Hà',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            8 =>
                array (
                    'id' => 9,
                    'code' => 510004,
                    'name' => 'HH Layer',
                    'quality' => 'Theo công thức Hồng Hà',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            9 =>
                array (
                    'id' => 10,
                    'code' => 510019,
                    'name' => 'HH Link 3%',
                    'quality' => 'Theo công thức Hồng Hà',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            10 =>
                array (
                    'id' => 11,
                    'code' => 510015,
                    'name' => 'HH Pig Grower',
                    'quality' => 'Theo công thức Hồng Hà',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            11 =>
                array (
                    'id' => 12,
                    'code' => 510014,
                    'name' => 'HH Piglet',
                    'quality' => 'Theo công thức Hồng Hà',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            12 =>
                array (
                    'id' => 13,
                    'code' => 'PP001',
                    'name' => 'Bao 25kg PP trắng cán tráng trong (cám cá), lồng LDPE',
                    'quality' => 'Chiều rộng: 60±1. Chiều dài: 105±1. Trọng lượng: 130gr±2. In cuộn: in theo mẫu. May đáy: may 2 đường chỉ. Lồng túi PE: 30gr. Đóng kiện: 500c/kiện, 100c/bó.',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            13 =>
                array (
                    'id' => 14,
                    'code' => 'PP002',
                    'name' => 'Bao 25kg PP trắng không tráng, lồng LDPE',
                    'quality' => 'Chiều rộng: 52±1. Chiều dài: 88±1. Trọng lượng: 101gr±2. In cuộn: in theo mẫu. May đáy: may 2 đường chỉ. Lồng túi PE: 21gr. Đóng kiện: 500c/kiện, 100c/bó.',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
            14 =>
                array (
                    'id' => 15,
                    'code' => 'PP003',
                    'name' => 'Bao 45kg PP trắng không tráng, lồng LDPE',
                    'quality' => 'Chiều rộng: 60±1. Chiều dài: 100±1. Trọng lượng: 100gr±2. In cuộn: in theo mẫu. May đáy: may 2 đường chỉ. Lồng túi PE: 30gr. Đóng kiện: 500c/kiện, 100c/bó.',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
        ));
    }
}
