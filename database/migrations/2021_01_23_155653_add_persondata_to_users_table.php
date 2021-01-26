<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPersondataToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('uuid')->nullable();//uuid
            $table->string('user_pic_url')->nullable();//頭貼位置
            $table->string('nickname')->nullable();//暱稱
            $table->string('phone')->nullable();//手機
            $table->string('role')->nullable();//腳色
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
                $table->dropColumn(['uuid','user_pic_url', 'nickname', 'phone','role']);
        });
    }
}
