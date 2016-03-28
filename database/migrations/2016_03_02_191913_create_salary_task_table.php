<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalaryTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_task', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('manager_id')->unsigned()->index();
            $table->integer('company_id')->unsigned()->index();
            $table->integer('receive_id')->unsigned()->index();
            $table->integer('by_id')->unsigned()->index();
            $table->text('memo');
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('type')->default(1);
            $table->integer('deal_time')->unsigned()->index();
            $table->integer('salary_day')->unsigned()->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('salary_task');
    }
}
