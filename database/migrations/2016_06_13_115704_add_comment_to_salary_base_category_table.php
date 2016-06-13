<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCommentToSalaryBaseCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('salary_base_category', function (Blueprint $table) {
            $table->integer('base_id')->comment("模版id")->change();
            $table->integer('category_id')->comment("类别id")->change();
            $table->text('memo')->comment("备注")->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('salary_base_category', function (Blueprint $table) {
            //
        });
    }
}
