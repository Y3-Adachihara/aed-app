<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('csv_mappings', function (Blueprint $blueprint) {
            $blueprint->id();
            
            // 💡 どの自治体の設定なのかを識別するカラム
            $blueprint->string('municipality_code')->unique(); // 自治体コード（例: 222097 ※焼津市）
            $blueprint->string('municipality_name');           // 自治体名（例: 焼津市）

            // 💡 ここを追加！自治体ごとのオープンデータセットIDを保存
            $blueprint->string('dataset_id');

            // 💡 ここに入力するのは「施設名」そのものではなく、「CSVの列名（例: '名称' や '施設名'）」です
            $blueprint->string('name_column');                 // 名称が格納されているCSVの列名
            
            // 💡 緯度経度の列名がない自治体もあるため、nullable() にします
            $blueprint->string('latitude_column')->nullable();  // 緯度が格納されているCSVの列名
            $blueprint->string('longitude_column')->nullable(); // 経度が格納されているCSVの列名
            
            // 💡 カンマ区切りの文字列を入れるため string または text 型にします
            $blueprint->text('address_columns');               // 住所を構成するCSVの列名群（カンマ区切り）
            $blueprint->text('description_columns')->nullable(); // 説明を構成するCSVの列名群（カンマ区切り。無くても良いようにnullable）
            
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('csv_mappings');
    }
};