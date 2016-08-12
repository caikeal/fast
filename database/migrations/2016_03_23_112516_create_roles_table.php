<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');//角色名
            $table->string('label')->nullable();//具体命名
            $table->integer('level')->unsigned();//权限等级
            $table->integer('pid')->unsigned();//父级角色
            $table->timestamps();
        });
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');//权限名
            $table->string('label')->nullable();//具体权限名
            $table->integer('category')->nullable()->comment("1:任务，2:人员，3:系统，4:薪资，5:理赔，6:数据分析");//权限类别
            $table->timestamps();
        });
        Schema::create('permission_role', function (Blueprint $table) {
            $table->integer('permission_id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->timestamps();
            $table->primary(['permission_id','role_id']);
        });
        Schema::create('role_manager', function (Blueprint $table) {
            $table->integer('manager_id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->timestamps();
            $table->primary(['manager_id','role_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('roles');
    }
}
