<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCommentToSalaryUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('salary_uploads', function (Blueprint $table) {
            $table->integer('manager_id')->comment("上传者id")->change();
            $table->integer('base_id')->comment("上传的模版id")->change();
            $table->string('upload')->comment("上传地址")->change();;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('salary_uploads', function (Blueprint $table) {
            //
        });
    }
}
