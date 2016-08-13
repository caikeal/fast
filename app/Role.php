<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public function father()
    {
        return $this->belongsTo('App\Role', 'pid');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class,'permission_role')->withTimestamps();
    }

    /**
     * @param Permission model|Permission collection $permission
     */
    public function givePermission($permission)
    {
        return $this->permissions()->attach($permission);
    }

    /**
     * @param Permission model|Permission collection $permission
     * @return int
     */
    public function deletePermission($permission)
    {
        if ($permission instanceof Collection) {
            $permission = $permission->modelKeys();
        }
        return $this->permissions()->detach($permission);
    }
}
