<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalaryCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_category', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',60);
            $table->tinyInteger('level');
            $table->integer('manager_id')->unsigned();
            $table->tinyInteger('type')->default(1);
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
        Schema::drop('salary_category');
    }
}
