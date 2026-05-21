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
        Schema::create('aed', function (Blueprint $table) {
            $table->id();
            $table->string('name');         // 建物や施設の名称
            $table->string('postcode');     // 郵便番号
            $table->string('prefecture');   // 県
            $table->string('municipality'); // 市町村
            $table->string('address');      // 番地
            $table->string('description');  // 建物内の場所の説明
            $table->double('latitude');     // 緯度
            $table->double('longitude');    // 経度
            $table->timestamps();
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
