<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public function father()
    {
        return $this->belongsTo('App\Role', 'pid');
    }

    public function permissions(){
        return $this->belongsToMany(Permission::class,'permission_role');
    }

    public function givePermission(Permission $permission){
        return $this->permissions()->save($permission);
    }
}
