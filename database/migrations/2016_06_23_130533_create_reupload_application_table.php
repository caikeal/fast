<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReuploadApplicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reupload_application', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('applier',false,true)->comment('申请者id')->index();
            $table->integer('receiver',false,true)->comment('接受者id')->index();
            $table->integer('upload_id',false,true)->comment('上传id')->index();
            $table->tinyInteger('status',false,true)->comment('申请状态, 1:ok,2:fail,3:wait');
            $table->integer('expiration',false,true)->comment('有效期')->default(7);
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
        Schema::drop('reupload_application');
    }
}
