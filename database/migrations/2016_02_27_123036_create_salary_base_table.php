<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalaryBaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_base', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title',60);
            $table->integer('manager_id')->unsigned()->index();
            $table->integer('company_id')->unsigned()->index();
            $table->text('memo');
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
        Schema::drop('salary_base');
    }
}
