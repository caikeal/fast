<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCommentToSalaryBaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('salary_base', function (Blueprint $table) {
            $table->string('title',60)->comment("模版名")->change();
            $table->integer('manager_id')->comment("创建用户")->change();
            $table->integer('company_id')->comment("模版对应公司")->change();
            $table->text('memo')->comment("模版备注")->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('salary_base', function (Blueprint $table) {
            //
        });
    }
}
