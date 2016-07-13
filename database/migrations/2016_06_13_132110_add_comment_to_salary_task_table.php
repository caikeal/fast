<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCommentToSalaryTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('salary_task', function (Blueprint $table) {
            $table->integer('manager_id')->comment("发布者id")->change();
            $table->integer('company_id')->comment("任务企业id")->change();
            $table->integer('receive_id')->comment("接收者id")->change();
            $table->integer('by_id')->comment("经手人id")->change();
            $table->text('memo')->comment("备注")->change();
            $table->integer('deal_time')->comment("处理时间范围")->change();
            $table->integer('salary_day')->comment("发薪日")->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('salary_task', function (Blueprint $table) {
            //
        });
    }
}
