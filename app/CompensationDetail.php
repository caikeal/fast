<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompensationDetail extends Model
{
    protected $table = 'compensation_details';

    protected $fillable=[
        'user_id','base_id','company_id','wages','compensation_day','memo','manager_id'
    ];

    public function baseCategory(){
        return $this->belongsTo('App\SalaryBaseCategory','base_id','base_id');
    }

    public function fromer()
    {
        return $this->belongsTo('App\Manager', 'manager_id');
    }
}
