<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CsvMappingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 💡 複数まとめて登録するために配列を用意します
        $mappings = [
            [
                'municipality_code' => '222097', // 島田市
                'municipality_name' => '島田市',
                'name_column' => '名称',
                'latitude_column' => '緯度',
                'longitude_column' => '経度',
                'address_columns' => '住所', // 1列だけ指定
                'description_columns' => '設置場所',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'municipality_code' => '222127', // 焼津市
                'municipality_name' => '焼津市',
                'name_column' => '施設名',
                'latitude_column' => '緯度',
                'longitude_column' => '経度',
                'address_columns' => '住所',
                'description_columns' => '台数', // 説明の代わりに台数を入れてみる
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'municipality_code' => '221007', // 静岡市
                'municipality_name' => '静岡市',
                'name_column' => '名称',
                
                // 💡 罠対策：データが空なので null を指定し、APIでの取得対象にする！
                'latitude_column' => null,
                'longitude_column' => null,
                
                // 💡 罠対策：中身が入っている列同士をガッチャンコ！
                'address_columns' => '地方公共団体名,所在地_連結表記', 
                
                'description_columns' => '設置位置,備考',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'municipality_code' => '224294', // 川根本町
                'municipality_name' => '川根本町',
                'name_column' => '名称',
                'latitude_column' => null,  // 💡 緯度経度の列がないので null を許可
                'longitude_column' => null, // 💡 経度の列がないので null を許可
                'address_columns' => '所在地_連結表記',
                'description_columns' => '設置位置,利用可能曜日,開始時間,終了時間,備考',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // 💡 一気にデータベースへ保存！
        DB::table('csv_mappings')->insert($mappings);
    }
}
