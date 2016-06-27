<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Manager extends Authenticatable
{
    protected $table="managers";
    use SoftDeletes;

    /**
     * 应该被调整为日期的属性
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','poster','phone','manager_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function roles(){
        return $this->belongsToMany('App\Role', 'role_manager');
    }

    public function hasRole($role){
        if(is_string($role)){
            return $this->roles->contains('name',$role);
        }
        return !!$role->intersect($this->roles)->count();
    }

    //收到的任务
    public function tasks(){
        return $this->hasMany('App\SalaryTask','receive_id');
    }

    //布置的任务
    public function assignTasks(){
        return $this->hasMany('App\SalaryTask','manager_id');
    }

    //上一层领导
    public function leader(){
        return $this->belongsTo('App\Manager','pid');
    }

    //创建的用户
    public function ownMember(){
        return $this->hasMany('App\Manager','pid');
    }
}
