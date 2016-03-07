<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalaryBaseCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_base_category', function (Blueprint $table) {
            $table->integer('base_id')->unsigned();
            $table->integer('category_id')->unsigned();
            $table->text('memo');
            $table->tinyInteger('place');
            $table->timestamps();
            $table->primary(['base_id','category_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('salary_base_category');
    }
}
