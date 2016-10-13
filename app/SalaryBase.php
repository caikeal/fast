<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalaryBase extends Model
{
    protected $table='salary_base';
    use SoftDeletes;

    protected $fillable=[
        'title','manager_id','memo'
    ];

    public function categories(){
        return $this->belongsToMany('App\SalaryCategory','salary_base_category','base_id','category_id')->withPivot('place');
    }
}
