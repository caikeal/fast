<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sender',false,true)->comment('发送者id')->index();
            $table->integer('receiver',false,true)->comment('接受者id')->index();
            $table->tinyInteger('type',false,true)->comment('消息类型')->index();
            $table->tinyInteger('is_read',false,true)->comment('已读状态，0：未读，1：已读')->default(0);
            $table->tinyInteger('status',false,true)->comment('处理状态，1：ok，2：fail，3：wait，4：finish')->default(3);
            $table->integer('relate_id',false,true)->comment('关联id');
            $table->string('content')->comment('消息内容');
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
        Schema::drop('news');
    }
}
