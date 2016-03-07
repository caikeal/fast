<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table="companys";

    protected $fillable=[
        'name','poster','phone','email','manager_id'
    ];
}
