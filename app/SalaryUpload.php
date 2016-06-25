<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalaryUpload extends Model
{
    protected $table='salary_uploads';

    protected $fillable=[
        'manager_id','base_id','upload','company_id','type'
    ];

    public function application()
    {
        return $this->hasMany('App\ReuploadApplication','upload_id');
    }

    public function company()
    {
        return $this->belongsTo('App\Company','company_id');
    }
}
