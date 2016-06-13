<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCommentToCompanysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companys', function (Blueprint $table) {
            $table->string('name')->comment("公司名")->change();
            $table->string('poster')->comment("公司logo")->change();
            $table->string('phone',30)->comment("联系电话")->change();
            $table->string('email')->comment("联系邮箱")->change();
            $table->integer('manager_id')->comment("我方负责对接的人")->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companys', function (Blueprint $table) {
            //
        });
    }
}
