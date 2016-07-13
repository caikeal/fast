<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutoTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auto_task', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('creator')->comment('创建者id')->unsigned()->index();
            $table->integer('receiver')->comment('接受者id')->unsigned()->index();
            $table->integer('by')->comment('经手人id')->unsigned()->index()->nullable();
            $table->integer('company_id')->comment('创建企业id')->unsigned()->index();
            $table->tinyInteger('type')->comment('类型：1工资，2社保')->unsigned()->default(1);
            $table->integer('deal_time',false,false)->comment('处理时间范围');
            $table->text('memo')->comment('备注');
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
        Schema::drop('auto_task');
    }
}
