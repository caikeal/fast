<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalaryBaseCategory extends Model
{
    protected $table='salary_base_category';

    protected $fillable=[
        'base_id','category_id','memo','place'
    ];
}
