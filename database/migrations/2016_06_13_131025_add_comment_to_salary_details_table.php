<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCommentToSalaryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('salary_details', function (Blueprint $table) {
            $table->integer('user_id')->comment("用户id")->change();
            $table->integer('base_id')->comment("模版id")->change();
            $table->integer('company_id')->comment("所属公司")->change();
            $table->text('wages')->comment("薪资数据以||分隔")->change();
            $table->text('memo')->comment("备注")->change();
            $table->integer('salary_day')->comment("发薪日")->change();
            $table->integer('manager_id')->comment("管理者id")->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('salary_details', function (Blueprint $table) {
            //
        });
    }
}
