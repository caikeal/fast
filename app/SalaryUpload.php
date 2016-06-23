<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalaryUpload extends Model
{
    protected $table='salary_uploads';

    protected $fillable=[
        'manager_id','base_id','upload','company_id','type'
    ];
}
