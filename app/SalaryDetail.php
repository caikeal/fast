<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalaryDetail extends Model
{
    protected $table='salary_details';

    protected $fillable=[
        'user_id','base_id','company_id','wages','salary_day','memo','manager_id'
    ];

    public function baseCategory(){
        return $this->belongsTo('App\SalaryBaseCategory','base_id','base_id');
    }
}
