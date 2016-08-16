<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('creator',false,true)->index()->comment("创建者id");
            $table->integer('receiver',false,true)->index()->comment("接受者id");
            $table->string('title')->comment("问题标题");
            $table->text('detail')->comment("详细信息");
            $table->text('answer')->comment("回答信息");
            $table->string('tags')->comment("关键词，以‘,’分割");
            $table->tinyInteger('type')->comment("类型：1工资，2社保，3理赔，4福利");
            $table->tinyInteger('status')->comment("处理状态：1.待处理，2.已处理");
            $table->timestamp('answer_at')->comment("回答时间");
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
        Schema::drop('question');
    }
}
