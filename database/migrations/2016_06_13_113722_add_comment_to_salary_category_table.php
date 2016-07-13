<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCommentToSalaryCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('salary_category', function (Blueprint $table) {
            $table->string('name',60)->comment("模块名")->change();
            $table->integer('manager_id')->comment("创建者")->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('salary_category', function (Blueprint $table) {
            //
        });
    }
}
