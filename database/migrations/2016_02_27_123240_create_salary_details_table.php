<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalaryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->integer('base_id')->unsigned()->index();
            $table->integer('company_id')->unsigned()->index();
            $table->tinyInteger('type')->unsigned()->default(1);
            $table->text('wages');
            $table->text('memo');
            $table->integer('salary_day');
            $table->integer('manager_id')->unsigned();
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
        Schema::drop('salary_details');
    }
}
