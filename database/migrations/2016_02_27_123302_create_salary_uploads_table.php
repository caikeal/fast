<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalaryUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_uploads', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('manager_id')->unsigned()->index();
            $table->integer('base_id')->unsigned()->index();
            $table->integer('company_id')->unsigned()->index();
            $table->tinyInteger('type')->unsigned()->index();
            $table->string('upload');
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
        Schema::drop('salary_uploads');
    }
}
