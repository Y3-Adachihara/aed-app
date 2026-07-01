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
        Schema::create('aeds', function (Blueprint $table) {
            $table->id();
            $table->string('municipality_code'); // 💡 ①案2: 自治体コードカラムを追加！
            $table->string('name');              // 施設名は必須（Not Null）
            
            // 💡 ③対策: 不備のあるデータでもクラッシュしないよう、nullable() を一斉付与！
            $table->string('postcode')->nullable();
            $table->string('prefecture')->nullable();
            $table->string('municipality')->nullable();
            $table->string('address')->nullable(); // ここは「番地」のみが入る
            $table->text('description')->nullable(); // 文字数が増えてもいいように string から text に変更
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            
            $table->timestamps();

            // 💡 データベースの検索を高速化し、重複を防ぐためのインデックス設定
            $table->unique(['municipality_code', 'name', 'address'], 'aeds_unique_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aed');
    }
};
