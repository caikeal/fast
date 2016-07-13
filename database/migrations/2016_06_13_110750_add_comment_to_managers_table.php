<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCommentToManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('managers', function (Blueprint $table) {
            $table->string('name')->comment("员工姓名")->change();
            $table->string('poster')->comment("头像")->change();
            $table->string('phone',20)->comment("联系号码")->change();
            $table->string('password',60)->comment("密码")->change();
            $table->string('email',255)->comment("联系邮箱")->change();
            $table->integer('pid')->comment("隶属关系")->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('managers', function (Blueprint $table) {
            //
        });
    }
}
