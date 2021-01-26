<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classifications', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default(0);//狀態 0停用; 1起用; 
            $table->string('classification_name');//類別名稱
            $table->softDeletes();//軟刪除
            $table->timestamps();
        });
        Schema::create('houses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classification_id');	//classification_id
            $table->string('status')->default(0);//狀態 0停用; 1起用; 
            $table->string('name');//房屋物件名稱
            $table->string('medias_1_url');//媒體1位置
            $table->string('medias_1_type');//媒體1類型
             $table->string('medias_2_url')->nullable();//媒體2位置
            $table->string('medias_2_type')->nullable();//媒體2類型
             $table->string('medias_3_url')->nullable();//媒體3位置
            $table->string('medias_3_type')->nullable();//媒體3類型
             $table->string('medias_4_url')->nullable();//媒體4位置
            $table->string('medias_4_type')->nullable();//媒體4類型
             $table->string('medias_5_url')->nullable();//媒體5位置
            $table->string('medias_5_type')->nullable();//媒體5類型
             $table->string('medias_6_url')->nullable();//媒體6位置
            $table->string('medias_6_type')->nullable();//媒體6類型
             $table->string('medias_7_url')->nullable();//媒體7位置
            $table->string('medias_7_type')->nullable();//媒體7類型
             $table->string('medias_8_url')->nullable();//媒體8位置
            $table->string('medias_8_type')->nullable();//媒體8類型
            $table->decimal('price', 8, 2);//價格
            $table->text('note')->nullable();//文字備註
            $table->text('description')->nullable();//文字備註
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classifications');
        Schema::dropIfExists('houses');
    }
}
