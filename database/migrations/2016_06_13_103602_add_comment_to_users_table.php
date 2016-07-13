<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCommentToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->comment("用户名")->change();
            $table->string('phone')->comment("绑定号码")->change();
            $table->string('id_card')->comment("身份证")->change();
            $table->string('password', 60)->comment("密码")->change();
            $table->integer('manager_id')->comment("负责管理员")->change();
            $table->integer('company_id')->comment("隶属企业")->change();
            $table->rememberToken()->comment("网页登录的token")->change();
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
            //
        });
    }
}
