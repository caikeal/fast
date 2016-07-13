<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InsuranceDetail extends Model
{
    protected $table = 'insurance_details';

    protected $fillable=[
        'user_id','base_id','company_id','wages','insurance_day','memo','manager_id'
    ];

    public function baseCategory(){
        return $this->belongsTo('App\SalaryBaseCategory','base_id','base_id');
    }
}
