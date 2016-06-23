<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInsuranceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insurance_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->comment('用户表ID（对应users表id）')->unsigned()->index();
            $table->integer('base_id')->comment('工资模版表ID（对应salary_base表id）')->unsigned()->index();
            $table->integer('company_id')->comment('客户企业ID(对应companys表id)')->unsigned()->index();
            $table->tinyInteger('type')->comment('社保类型，1：社保进度，默认值1')->unsigned()->default(1);
            $table->text('wages')->comment('明细值');
            $table->text('memo')->comment('备注');
            $table->integer('insurance_day')->comment('社保日期');
            $table->integer('manager_id')->comment('操作者ID(对应managers表)')->unsigned();
            $table->softDeletes();
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
        Schema::drop('insurance_details');
    }
}
