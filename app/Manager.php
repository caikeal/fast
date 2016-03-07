<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Manager extends Authenticatable
{
    protected $table="managers";
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
}
