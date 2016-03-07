<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalaryCategory extends Model
{
    protected $table='salary_category';

    protected $fillable=[
        'name','level','manager_id'
    ];
}
