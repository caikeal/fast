<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalaryTask extends Model
{
    use SoftDeletes;
    protected $table="salary_task";

    /**
     * 应该被调整为日期的属性
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $fillable=[
        'manager_id','company_id','receive_id','memo','deal_time','salary_day'
    ];

    public function company(){
        return $this->belongsTo(Company::class,'company_id');
    }

    public function salaryModels(){
        return $this->hasMany(SalaryBase::class,'company_id','company_id');
    }

    public function receiver(){
        return $this->belongsTo('App\Manager','receive_id');
    }
}
